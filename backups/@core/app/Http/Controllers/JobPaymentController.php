<?php

namespace App\Http\Controllers;

use App\Http\Traits\PaytmTrait;
use App\JobApplicant;
use App\Jobs;
use App\Mail\BasicMail;
use App\Mail\ContactMessage;
use App\Order;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
use Mollie\Laravel\Facades\Mollie;
use Razorpay\Api\Api;
use Stripe\Charge;
use Stripe\Stripe;
use function App\Http\Traits\getChecksumFromArray;

class JobPaymentController extends Controller
{
    use PaytmTrait;
    public function store_jobs_applicant_data(Request $request)
    {
        $jobs_details = Jobs::find($request->job_id);
        $this->validate($request,[
            'email' => 'required|email',
            'name' => 'required|string',
            'job_id' => 'required',
        ],[
            'email.required' => __('email is required'),
            'email.email' => __('enter valid email'),
            'name.required' => __('name is required'),
            'job_id.required' => __('must apply to any job'),
        ]);
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            $this->validate($request,[
                'selected_payment_gateway' => 'required|string'
            ],
                ['selected_payment_gateway.required' => __('You must have to select a payment gateway')]);
        }
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0 && $request->selected_payment_gateway == 'manual_payment'){
            $this->validate($request,[
                'transaction_id' => 'required|string'
            ],
           ['transaction_id.required' => __('You must have to provide your transaction id')]);
        }

        $job_applicant_id = JobApplicant::create([
            'jobs_id' => $request->job_id,
            'payment_gateway' => $request->selected_payment_gateway,
            'email' => $request->email,
            'name' => $request->name,
            'application_fee' => $request->application_fee,
            'track' => Str::random(30),
            'payment_status' => 'pending',
        ])->id;

        $all_attachment = [];
        $all_quote_form_fields = (array) json_decode(get_static_option('apply_job_page_form_fields'));
        $all_field_type = isset($all_quote_form_fields['field_type']) ? $all_quote_form_fields['field_type'] : [];
        $all_field_name = isset($all_quote_form_fields['field_name']) ? $all_quote_form_fields['field_name'] : [];
        $all_field_required = isset($all_quote_form_fields['field_required']) ? $all_quote_form_fields['field_required'] : [];
        $all_field_mimes_type = isset($all_quote_form_fields['mimes_type']) ? $all_quote_form_fields['mimes_type'] : [];

        //get field details from, form request
        $all_field_serialize_data = $request->all();
        unset($all_field_serialize_data['_token'],$all_field_serialize_data['job_id'],$all_field_serialize_data['name'],$all_field_serialize_data['email'],$all_field_serialize_data['selected_payment_gateway']);

        if (!empty($all_field_name)){
            foreach ($all_field_name as $index => $field){
                $is_required = property_exists($all_field_required,$index) ? $all_field_required->$index : '';
                $mime_type = property_exists($all_field_mimes_type,$index) ? $all_field_mimes_type->$index : '';
                $field_type = isset($all_field_type[$index]) ? $all_field_type[$index] : '';
                if (!empty($field_type) && $field_type == 'file'){
                    unset($all_field_serialize_data[$field]);
                }
                $validation_rules = !empty($is_required) ? 'required|': '';
                $validation_rules .= !empty($mime_type) ? $mime_type : '';

                //validate field
                $this->validate($request,[
                    $field => $validation_rules
                ]);

                if ($field_type == 'file' && $request->hasFile($field)) {
                    $filed_instance = $request->file($field);
                    $file_extenstion = $filed_instance->getClientOriginalExtension();
                    $attachment_name = 'attachment-'.$job_applicant_id.'-'. $field .'.'. $file_extenstion;
                    $filed_instance->move('assets/uploads/attachment/applicant', $attachment_name);
                    $all_attachment[$field] = 'assets/uploads/attachment/applicant/' . $attachment_name;
                }
            }
        }


        //update database
         JobApplicant::where('id',$job_applicant_id)->update([
            'form_content' => serialize($all_field_serialize_data),
            'attachment' => serialize($all_attachment)
        ]);
        $job_applicant_details = JobApplicant::where('id',$job_applicant_id)->first();

        //check it application fee applicable or not
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            //have to redirect  to payment gateway route

            if($job_applicant_details->payment_gateway == 'paypal'){
                $payable_amount = $job_applicant_details->application_fee;
                $currency_code = get_static_option('site_global_currency');

                if (!is_paypal_supported_currency()){
                    $payable_amount = get_amount_in_usd($payable_amount,get_static_option('site_global_currency'));
                    if ($payable_amount < 1){
                        return $payable_amount.__('USD amount is not supported by paypal');
                    }
                    $currency_code = 'USD';
                }

                $paypal_details['business'] = get_static_option('paypal_business_email');
                $paypal_details['cbt'] = get_static_option('site_'.get_default_language().'_title');
                $paypal_details['item_name'] = __('Payment For Job Application Id:'). '#'.$job_applicant_details->id.' '.__('Job Title:').' '.$jobs_details->title.' '.__('Applicant Name:').' '.$job_applicant_details->name.' '.__('Applicant Email:').' '.$job_applicant_details->email;
                $paypal_details['custom'] = $job_applicant_details->track;
                $paypal_details['currency_code'] = $currency_code;
                $paypal_details['amount'] = $payable_amount;
                $paypal_details['return'] = route('frontend.job.payment.success',$job_applicant_details->id);
                $paypal_details['cancel_return'] = route('frontend.job.payment.cancel',$job_applicant_details->id);
                $paypal_details['notify_url'] = route('frontend.job.paypal.ipn');

                return view('frontend.payment.paypal')->with(['paypal_details' => $paypal_details]);

            }elseif ($job_applicant_details->payment_gateway == 'paytm'){

                $payable_amount = $job_applicant_details->application_fee;
                if (!is_paytm_supported_currency() ){
                    $payable_amount = get_amount_in_inr($payable_amount,get_static_option('site_global_currency'));
                }

                $data_for_request = $this->handlePaytmRequest( $job_applicant_details->track, $payable_amount );

                $paytm_txn_url = PAYTM_TXN_URL;
                $paramList = $data_for_request['paramList'];
                $checkSum = $data_for_request['checkSum'];

                return view('frontend.payment.paytm')->with([
                    'paytm_txn_url' => $paytm_txn_url,
                    'paramList' => $paramList,
                    'checkSum' => $checkSum,
                ]);

            }elseif ($job_applicant_details->payment_gateway == 'manual_payment'){

                JobApplicant::where( 'id', $job_applicant_details->id )->update([
                    'transaction_id' => $request->transaction_id
                ]);

                return redirect()->route('frontend.job.payment.success',$job_applicant_details->id);

            }elseif ($job_applicant_details->payment_gateway == 'stripe'){
                $stripe_data['title'] = __('Application For:').' '.$jobs_details->title;
                $stripe_data['order_id'] = $job_applicant_details->id;
                $stripe_data['price'] = $job_applicant_details->application_fee;
                $stripe_data['route'] = route('frontend.job.stripe.ipn');

                return view('frontend.payment.stripe')->with('stripe_data' ,$stripe_data);

            }elseif ($job_applicant_details->payment_gateway == 'razorpay'){

                $payable_amount = $job_applicant_details->application_fee;
                $currency_code = get_static_option('site_global_currency');

                if (!is_razorpay_supported_currency()){
                    $payable_amount = get_amount_in_inr($payable_amount,get_static_option('site_global_currency'));
                    $currency_code = 'INR';
                }

                $razorpay_data['currency_symbol'] = $currency_code;
                $razorpay_data['currency'] = $currency_code;
                $razorpay_data['price'] = $payable_amount;
                $razorpay_data['package_name'] = $jobs_details->title;
                $razorpay_data['route'] = route('frontend.job.razorpay.ipn');
                $razorpay_data['order_id'] = $job_applicant_details->id;
                return view('frontend.payment.razorpay')->with('razorpay_data' ,$razorpay_data);

            }elseif ($job_applicant_details->payment_gateway == 'paystack'){

                $payable_amount = $job_applicant_details->application_fee;
                $currency_code = get_static_option('site_global_currency');

                if (!is_paystack_supported_currency()){
                    $payable_amount = get_amount_in_ngn($payable_amount,get_static_option('site_global_currency'));
                    $currency_code = 'NGN';
                }

                $paystack_data['currency'] = $currency_code;
                $paystack_data['price'] = $payable_amount;
                $paystack_data['package_name'] =  $jobs_details->title;
                $paystack_data['name'] = $job_applicant_details->name;
                $paystack_data['email'] = $job_applicant_details->email;
                $paystack_data['order_id'] = $job_applicant_details->id;
                $paystack_data['track'] = $job_applicant_details->track;
                $paystack_data['route'] = route('frontend.paystack.pay');
                $paystack_data['type'] = 'job';
                return view('frontend.payment.paystack')->with(['paystack_data' => $paystack_data]);


            }elseif ($job_applicant_details->payment_gateway == 'mollie'){

                $payable_amount = $job_applicant_details->application_fee;
                $currency_code = get_static_option('site_global_currency');

                if (!is_mollie_supported_currency() ){
                    $payable_amount = get_amount_in_usd($job_applicant_details->application_fee,get_static_option('site_global_currency'));
                    $currency_code = 'USD';
                }


                $payment = Mollie::api()->payments->create([
                    "amount" => [
                        "currency" => $currency_code,
                        "value" => custom_number_format($payable_amount),//"10.00" // You must send the correct number of decimals, thus we enforce the use of strings
                    ],
                    "description" => __('Application Free for Applicant ID')." #".$job_applicant_details->id .' '.__('Name:').' '.$job_applicant_details->name.' '.__('Email:').' '.$job_applicant_details->email,
                    "redirectUrl" => route('frontend.job.mollie.webhook'),
                    "metadata" => [
                        "order_id" => $job_applicant_details->id,
                        "track" => $job_applicant_details->track,
                    ],
                ]);

                $payment = Mollie::api()->payments->get($payment->id);

                session()->put('mollie_payment_id',$payment->id);

                // redirect customer to Mollie checkout page
                return redirect($payment->getCheckoutUrl(), 303);

            }elseif ($job_applicant_details->payment_gateway == 'flutterwave'){

                $payable_amount = $job_applicant_details->application_fee;
                $currency_code = get_static_option('site_global_currency');

                if (!is_flutterwave_supported_currency()){
                    $payable_amount = get_amount_in_usd($payable_amount,get_static_option('site_global_currency'));
                    $currency_code = 'USD';
                }

                $flutterwave_data['currency'] = $currency_code;
                $flutterwave_data['name'] = $request->name;
                $flutterwave_data['form_action'] = route('frontend.job.flutterwave.pay');
                $flutterwave_data['amount'] = custom_number_format($payable_amount);
                $flutterwave_data['description'] = __('Job Applicant ID')." #".$job_applicant_details->id .' '.__('Applicant Name:').' '.$job_applicant_details->name .' '.__('Applicant Email:').' '.$job_applicant_details->email;
                $flutterwave_data['email'] = $job_applicant_details->email;
                $flutterwave_data['country'] = get_visitor_country() ? get_visitor_country() : 'NG';
                $flutterwave_data['metadata'] = [
                    ['metaname' => 'order_id', 'metavalue' => $job_applicant_details->id],
                    ['metaname' => 'track', 'metavalue' => $job_applicant_details->track],
                ];
                return view('frontend.payment.flutterwave')->with('flutterwave_data' ,$flutterwave_data);
            }

            return redirect()->route('homepage');

        }else{
            $succ_msg = get_static_option('apply_job_' . get_user_lang() . '_success_message');
            $success_message = !empty($succ_msg) ? $succ_msg : __('Your Application Is Submitted Successfully!!');
            self::send_order_mail($job_applicant_details->id);
            return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
        }
    }

    public function flutterwave_pay(Request $request){
        Rave::initialize(route('frontend.flutterwave.callback'));
    }


    /**
     * Obtain Rave callback information
     * @return void
     */
    public function flutterwave_callback(Request $request)
    {
        $response = json_decode(request()->resp);
        $txRef =$response->data->transactionobject->txRef;
        $data = Rave::verifyTransaction($txRef);
        $chargeResponsecode = $data->data->chargecode;
        $track = $data->data->meta[1]->metavalue;

        $job_applicant = JobApplicant::where( 'track', $track )->first();
        if (($chargeResponsecode == "00" || $chargeResponsecode == "0")){

            $transaction_id = $txRef;
            //update database
            self::update_database($job_applicant->id,$transaction_id);
            //send success mail to user and admin
            self::send_order_mail($job_applicant->id);

            return redirect()->route('frontend.job.payment.success',$job_applicant->id);

        }else{
            return redirect()->route('frontend.job.payment.cancel',$job_applicant->id);
        }

    }


    public function paypal_ipn(Request $request)
    {

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        // Read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        /*
         * Post IPN data back to PayPal to validate the IPN data is genuine
         * Without this step anyone can fake IPN data
         */
        $paypalURL = get_paypal_form_url();
        $ch = curl_init($paypalURL);
        if ($ch == FALSE) {
            return FALSE;
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));
        $res = curl_exec($ch);

        /*
         * Inspect IPN validation result and act accordingly
         * Split response headers and payload, a better way for strcmp
         */
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));
        if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {

            $receiver_email = $_POST['receiver_email'];
            $mc_currency = $_POST['mc_currency'];
            $mc_gross = $_POST['mc_gross'];
            $track = $_POST['custom'];

            //GRAB DATA FROM DATABASE!!
            $payment_logs = JobApplicant::where('track', $track)->first();
            $paypal_business_email = get_static_option('paypal_business_email');

            if ($receiver_email == $paypal_business_email && $payment_logs->status == 'pending') {
                //database update
                self::update_database($payment_logs->id,$_POST['txn_id']);
                //send mail
                self::send_order_mail($payment_logs->id);

            }
        }
    }

    public function paytm_ipn(Request $request){
        $order_id = $request['ORDERID'];
        $payment_logs = JobApplicant::where( 'track', $order_id )->first();
        if ( 'TXN_SUCCESS' === $request['STATUS'] ) {
            //update database
            self::update_database($payment_logs->id, $request['TXNID']);
            //send mail
            self::send_order_mail($payment_logs->id);

            return redirect()->route('frontend.job.payment.success',$payment_logs->id);

        } else if( 'TXN_FAILURE' === $request['STATUS'] ){
            return redirect()->route('frontend.job.payment.cancel',$payment_logs->id);
        }
    }

    public function stripe_ipn(Request $request)
    {
        // stripe customer payment token
        $stripe_token = $request->stripe_token;
        $applicant_details = JobApplicant::find($request->order_id);
        Stripe::setApiKey( get_static_option('stripe_secret_key') );

        if (!empty($stripe_token)){
            // charge customer with your amount
            $result = Charge::create(array(
                "currency" => get_static_option('site_global_currency'),
                "amount"   => $applicant_details->application_fee * 100, // amount in cents,
                'source' => $stripe_token,
                'description' => __('Payment From').' '. get_static_option('site_'.get_default_language().'_title').'. '.__('Order ID').'#'.$applicant_details->id .', '.__('Payer Name:').' '.$applicant_details->name.', '.__(' Applicant Email:').' '.$applicant_details->email,
            ));
        }

        if ($result->status == 'succeeded'){
            //update database
            self::update_database($applicant_details->id,$result->balance_transaction);
            //send mail to user
            self::send_order_mail($applicant_details->id);
            return redirect()->route('frontend.job.payment.success',$applicant_details->id);
        }
        return redirect()->route('frontend.job.payment.cancel',$applicant_details->id);

    }

    public function razorpay_ipn(Request $request){

        $job_applicant_details = JobApplicant::where('id',$request->order_id)->first();

        //get API Configuration
        $api = new Api(get_static_option('razorpay_key'), get_static_option('razorpay_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if(!empty($request->razorpay_payment_id)) {
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=> $payment['amount']));
            } catch (\Exception $e) {
                return redirect()->route('frontend.job.payment.cancel',$job_applicant_details->id);
            }
            // Do something here for store payment details in database...
            self::update_database($job_applicant_details->id,$payment->id);
            //send mail to user
            self::send_order_mail($job_applicant_details->id);
        }

        return redirect()->route('frontend.job.payment.success',$job_applicant_details->id);

    }

    public function mollie_webhook(){
        $payment_id = session()->get('mollie_payment_id');
        $payment = Mollie::api()->payments->get($payment_id);
        session()->forget('mollie_payment_id');

        $order_details = JobApplicant::find($payment->metadata->order_id);
        if ($payment->isPaid()){
            //database update
            self::update_database($order_details->id,$payment->id);
            //send mail to user
            self::send_order_mail($order_details->id);
            return redirect()->route('frontend.job.payment.success',$payment->metadata->order_id);
        }

        return redirect()->route('frontend.job.payment.cancel',$payment->metadata->order_id);
    }

    public function handlePaytmRequest( $order_id, $amount ) {
        // Load all functions of encdec_paytm.php and config-paytm.php
        $this->getAllEncdecFunc();
        $this->getConfigPaytmSettings();

        $checkSum = "";
        $paramList = array();

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = get_static_option('paytm_merchant_mid');
        $paramList["ORDER_ID"] = $order_id;
        $paramList["CUST_ID"] = $order_id;
        $paramList["INDUSTRY_TYPE_ID"] = 'Retail';
        $paramList["CHANNEL_ID"] = 'WEB';
        $paramList["TXN_AMOUNT"] = $amount;
        $paramList["WEBSITE"] = get_static_option('paytm_merchant_website');
        $paramList["CALLBACK_URL"] = route('frontend.job.paytm.ipn');
        $paytm_merchant_key = get_static_option('paytm_merchant_key');

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );

        return array(
            'checkSum' => $checkSum,
            'paramList' => $paramList
        );
    }

    public function update_database($applicant_id,$transaction_id){
        JobApplicant::where('id',$applicant_id)->update([
            'transaction_id' => $transaction_id,
            'payment_status' => 'complete',
        ]);
    }

    public function send_order_mail($applicant_id){

        $job_applicant_details = JobApplicant::where('id',$applicant_id)->first();
        $jobs_details = Jobs::where('id',$job_applicant_details->jobs_id)->first();
        $receiver_mail_address = !empty(get_static_option('job_single_page_applicant_mail')) ? get_static_option('job_single_page_applicant_mail') : get_static_option('site_global_email');
        //send mail to admin
        $admin_message = '<p>'.__('Hello').',<br> '.__('You have a new job applicant');
        $admin_message .= ' #'.$job_applicant_details->id.' '.__('Name').' '.$job_applicant_details->name;
        $admin_message .= ' '.__('Applied to job post').' "'.$jobs_details->title.'"';
        $admin_message .= ' '.__('applied at').' '.date_format($job_applicant_details->created_at,'d M y h:i:s') ;

        //check for payment details
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            $admin_message .= ' '.__('paid via').' '.str_replace('_',' ',$job_applicant_details->payment_gateway);
            $admin_message .= ' '.__('Transaction Id').' '.$job_applicant_details->transaction_id;
        }

        $admin_message .= ' '.__('check admin panel for more info.') ;
        $admin_message .='</p>';


        Mail::to($receiver_mail_address)->send(new BasicMail([
            'subject' => __('You Have A Job Applicant'),
            'message' => $admin_message,
        ]));

        //send mail to admin
        $applicant_message = '<p>'.__('Hello').', '.$job_applicant_details->name.'<br> '.__('You job application submitted successfully.');
        $applicant_message .= ' #'.$job_applicant_details->id;
        $applicant_message .= ' '.__('Applied to job post').' "'.$jobs_details->title.'"';
        $applicant_message .= ' '.__('applied at').' '.date_format($job_applicant_details->created_at,'d M y h:i:s') ;
        //check for payment details
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            $applicant_message .= ' '.__('paid via').' '.str_replace('_',' ',$job_applicant_details->payment_gateway);
            $applicant_message .= ' '.__('Transaction Id').' '.$job_applicant_details->transaction_id;
        }
        $applicant_message .='</p>';
        //send mail to applicant
        Mail::to($job_applicant_details->email)->send(new BasicMail([
            'subject' => __('Your job application submitted successfully'),
            'message' => $applicant_message,
        ]));
    }

}
