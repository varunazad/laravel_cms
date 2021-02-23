<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Donation;
use App\DonationLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events;
use App\Http\Traits\PaytmTrait;
use App\Mail\DonationMessage;
use App\Mail\PaymentSuccess;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
use Mollie\Laravel\Facades\Mollie;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Razorpay\Api\Api;
use Stripe\Charge;
use Stripe\Stripe;
use Unicodeveloper\Paystack\Facades\Paystack;
use function App\Http\Traits\getChecksumFromArray;

class DonationLogController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function store_donation_logs(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'donation_id' => 'required|string',
            'amount' => 'required|string',
            'anonymous' => 'nullable|string',
            'selected_payment_gateway' => 'required|string',
        ],
        [
            'name.required' => __('Name field is required'),
            'email.required' => __('Email field is required'),
            'amount.required' => __('Amount field is required'),
        ]
        );

        if (!empty($request->order_id)){
            $payment_log_id =  $request->order_id;
        }else{
            $payment_log_id = DonationLogs::create([
                'email' =>  $request->email,
                'name' =>  $request->name,
                'donation_id' =>  $request->donation_id,
                'amount' =>  $request->amount,
                'anonymous' =>  !empty($request->anonymous) ? 1 : 0,
                'payment_gateway' =>  $request->selected_payment_gateway,
                'user_id' =>  auth()->check() ? auth()->user()->id : '',
                'status' =>  'pending',
                'track' =>  Str::random(10). Str::random(10),
            ])->id;
        }
        
        $donation_payment_details = DonationLogs::find($payment_log_id);

        //have to work on below code
        if ($request->selected_payment_gateway == 'paypal'){

            $payable_amount = $donation_payment_details->amount;
            $currency_code = get_static_option('site_global_currency');
            if (!is_paypal_supported_currency()){
                $payable_amount = get_amount_in_usd($payable_amount,get_static_option('site_global_currency'));
                if ($payable_amount < 1){
                    return $payable_amount.__('USD amount is not supported by paypal');
                }
                $currency_code = 'USD';
            }
            /* new code */
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item_1 = new Item();

            $item_1->setName(__('Payment For Donation:').' '.$donation_payment_details->donation->title.' Name: '.$donation_payment_details->name.' Email:'. $donation_payment_details->email) /** item name **/
            ->setCurrency($currency_code)
                ->setQuantity(1)
                ->setPrice($payable_amount); /** unit price **/

            $item_list = new ItemList();
            $item_list->setItems(array($item_1));

            $amount = new Amount();
            $amount->setCurrency($currency_code)
                ->setTotal($payable_amount);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription(__('Payment For Donation:').' '.$donation_payment_details->donation->title.' Name: '.$donation_payment_details->name.' Email:'. $donation_payment_details->email);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(route('frontend.donation.paypal.ipn')) /** Specify return URL **/
            ->setCancelUrl(route('frontend.donation.payment.cancel',$donation_payment_details->id));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {

                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    return redirect()->route('frontend.donation.payment.cancel',$donation_payment_details->id); //connection timeout
                } else {
                    return redirect()->route('frontend.donation.payment.cancel',$donation_payment_details->id); //some error occur, then redirect to cancel page
                }

            }

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref(); //set redirect url for payment
                    break;
                }
            }

            /** add payment ID to session, this need to verify paypal transaction from paypal ipn function **/
            Session::put('paypal_payment_id', $payment->getId());
            Session::put('paypal_track', $donation_payment_details->track);

            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            return redirect_404_page(); //not redirect to paypal, that's why redirect in 404 page

        }elseif ($request->selected_payment_gateway == 'paytm'){

            $payable_amount = $donation_payment_details->amount;
            if (!is_paytm_supported_currency() ){
                $payable_amount = get_amount_in_inr($payable_amount,get_static_option('site_global_currency'));
            }
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order' => $donation_payment_details->id,
                'user' => Str::slug($donation_payment_details->name),
                'mobile_number' => rand(9999,99999999),
                'email' => $donation_payment_details->email,
                'amount' => custom_number_format($payable_amount),
                'callback_url' => route('frontend.donation.paytm.ipn')
            ]);
            return $payment->receive();

        }elseif ($request->selected_payment_gateway == 'manual_payment'){
            $this->validate($request,[
                'transaction_id' => 'required|string'
            ],['transaction_id.required' => __('Transaction ID Required')]);

            DonationLogs::where('donation_id',$request->donation_id)->update(['transaction_id' => $request->transaction_id]);
            $order_id = Str::random(6).$donation_payment_details->id.Str::random(6);
            return redirect()->route('frontend.donation.payment.success',$order_id);

        }elseif ($request->selected_payment_gateway == 'stripe'){

            $payable_amount = $donation_payment_details->amount;

            $stripe_data['title'] = __('Payment of donation:').' '.$donation_payment_details->donation->title;
            $stripe_data['order_id'] = $donation_payment_details->id;
            $stripe_data['price'] = $payable_amount;
            $stripe_data['route'] = route('frontend.donation.stripe.ipn');

            return view('frontend.payment.stripe')->with('stripe_data' ,$stripe_data);
        }
        elseif ($request->selected_payment_gateway == 'razorpay'){
            
            $payable_amount = $donation_payment_details->amount;
            $currency_code = get_static_option('site_global_currency');

            if (!is_razorpay_supported_currency()){
                $payable_amount = get_amount_in_inr($donation_payment_details->amount,get_static_option('site_global_currency'));
                $currency_code = 'INR';
            }

            $razorpay_data['currency_symbol'] = $currency_code;
            $razorpay_data['currency'] = $currency_code;
            $razorpay_data['price'] = $payable_amount;
            $razorpay_data['package_name'] = $donation_payment_details->donation->title;
            $razorpay_data['order_id'] = $donation_payment_details->id;
            $razorpay_data['route'] = route('frontend.donation.razorpay.ipn');
            return view('frontend.payment.razorpay')->with('razorpay_data' ,$razorpay_data);
        }
        elseif ($request->selected_payment_gateway == 'paystack'){
            
            $payable_amount = $donation_payment_details->amount;
            $currency_code = get_static_option('site_global_currency');

            if (!is_paystack_supported_currency()){
                $payable_amount = get_amount_in_ngn($donation_payment_details->amount,get_static_option('site_global_currency'));
                $currency_code = 'NGN';
            }
            $paystack_data['currency'] = $currency_code;
            $paystack_data['price'] = $payable_amount;
            $paystack_data['package_name'] =  $donation_payment_details->donation->title;
            $paystack_data['name'] = $donation_payment_details->name;
            $paystack_data['email'] = $donation_payment_details->email;
            $paystack_data['order_id'] = $donation_payment_details->id;
            $paystack_data['track'] = $donation_payment_details->track;
            $paystack_data['route'] = route('frontend.donation.paystack.pay');
            $paystack_data['type'] = 'donation';

            return view('frontend.payment.paystack')->with(['paystack_data' => $paystack_data]);
        }
        elseif ($request->selected_payment_gateway == 'mollie'){

            $payable_amount = $donation_payment_details->amount;
            $currency_code = get_static_option('site_global_currency');

            if (!is_mollie_supported_currency() ){
                $payable_amount = get_amount_in_usd($payable_amount,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }
            $payment = Mollie::api()->payments->create([
                "amount" => [
                    "currency" => $currency_code,
                    "value" => custom_number_format($payable_amount),//"10.00" // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => "Donation Details ID #".$donation_payment_details->id .' Name: '.$donation_payment_details->name.' Email: '.$donation_payment_details->email,
                "redirectUrl" => route('frontend.donation.mollie.webhook'),
                "metadata" => [
                    "order_id" => $donation_payment_details->id,
                    "track" => $donation_payment_details->track,
                ],
            ]);

            $payment = Mollie::api()->payments->get($payment->id);

            session()->put('mollie_payment_id',$payment->id);

            // redirect customer to Mollie checkout page
            return redirect($payment->getCheckoutUrl(), 303);
        }
        elseif ($request->selected_payment_gateway == 'flutterwave'){
            $payable_amount = $donation_payment_details->amount;
            $currency_code = get_static_option('site_global_currency');
            if (!is_flutterwave_supported_currency()){
                $payable_amount = get_amount_in_usd($donation_payment_details->amount,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }

            $flutterwave_data['name'] = $donation_payment_details->name;
            $flutterwave_data['currency'] = $currency_code;
            $flutterwave_data['form_action'] = route('frontend.donation.flutterwave.pay');
            $flutterwave_data['amount'] = $payable_amount;
            $flutterwave_data['description'] = "Donation Details ID #".$donation_payment_details->id .' Name: '.$donation_payment_details->name;
            $flutterwave_data['email'] = $donation_payment_details->email;
            $flutterwave_data['country'] = get_visitor_country() ? get_visitor_country() : 'NG';
            $flutterwave_data['metadata'] = [
                ['metaname' => 'order_id', 'metavalue' => $donation_payment_details->id],
                ['metaname' => 'track', 'metavalue' => $donation_payment_details->track],
            ];
            return view('frontend.payment.flutterwave')->with('flutterwave_data' ,$flutterwave_data);
        }
        return redirect()->route('homepage');
    }


    public function flutterwave_pay(Request $request){
        Rave::initialize(route('frontend.donation.flutterwave.callback'));
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

        $payment_logs = DonationLogs::where('track', $track)->first();
        if (($chargeResponsecode == "00" || $chargeResponsecode == "0")){

            //update database
            $this->update_database($payment_logs->id,$txRef);
            //send mail to admin/user
            $this->sendEmail($payment_logs->id);
            $order_id = Str::random(6).$payment_logs->id.Str::random(6);
            return redirect()->route('frontend.donation.payment.success',$order_id);

        }else{
            return redirect()->route('frontend.donation.payment.cancel',$payment_logs->id);
        }

    }

    public function paypal_ipn(Request $request)
    {

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $paypal_track = Session::get('paypal_track');
        $payment_logs = DonationLogs::where('track', $paypal_track)->first();

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        Session::forget('paypal_track');

        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('frontend.donation.payment.cancel',$payment_logs->id);
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {

            //database update
            $this->update_database($payment_logs->id,$payment_id);
            //send mail
            $this->sendEmail($payment_logs->id);
            $order_id = Str::random(6).$payment_logs->id.Str::random(6);
            return redirect()->route('frontend.donation.payment.success',$order_id);
        }
        return redirect()->route('frontend.donation.payment.cancel',$payment_logs->id);
    }

    public function paytm_ipn(Request $request){
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        $donation_details = DonationLogs::find($response['ORDERID']);
        if($transaction->isSuccessful()){

            //update database
            $this->update_database($donation_details->id,$response['TXNID']);
            //send success mail to user and admin
            $this->sendEmail($donation_details->id);
            $order_id = Str::random(6).$donation_details->id.Str::random(6);
            return redirect()->route('frontend.donation.payment.success',$order_id);


        }else if($transaction->isFailed()){
            //Transaction Failed
            return redirect()->route('frontend.donation.payment.cancel',$donation_details->id);
        }
        return redirect_404_page();
    }

    public function stripe_success()
    {
        //have to verify stripe payment and redirect to success page
        $stripe_session_id = Session::get('stripe_session_id');
        $stripe_order_id = Session::get('stripe_order_id');
        $order_details = DonationLogs::find($stripe_order_id);
        Session::forget('stripe_session_id');
        Session::forget('stripe_order_id');
        $stripe = new \Stripe\StripeClient(
            get_static_option('stripe_secret_key')
        );
        $response = $stripe->checkout->sessions->retrieve($stripe_session_id,[]);
        $payment_intent = $response['payment_intent'] ?? '';
        $payment_status = $response['payment_status'] ?? '';
        $capture = $stripe->paymentIntents->retrieve($payment_intent);
        if (!empty($payment_status) && $payment_status === 'paid'){
            //is paid
            $transaction_id = !empty($capture) && isset($capture['charges']['data'][0]) ? $capture['charges']['data'][0]['balance_transaction'] : '';
            if (!empty($transaction_id)){
                // Do something here for store payment details in database...
                $this->update_database($order_details->id, $transaction_id);
                //send mail to user
                $this->sendEmail($order_details->id);
                $order_id = Str::random(6) . $order_details->id . Str::random(6);
                return redirect()->route('frontend.donation.payment.success', $order_id);
            }
            return route('frontend.donation.payment.cancel', $order_details->id);
        }else{
            //not paid
            return route('frontend.donation.payment.cancel', $order_details->id);
        }
    }

    public function stripe_ipn(Request $request)
    {
        // stripe customer payment token
        $order_details = DonationLogs::find($request->order_id);
        $donation_case = Donation::find($order_details->donation_id);

        Stripe::setApiKey(get_static_option('stripe_secret_key'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => get_static_option('site_global_currency'),
                    'product_data' => [
                        'name' => $donation_case->title,
                    ],
                    'unit_amount' => $order_details->amount * 100,
                ],
                'quantity' => 1,
                'description' => 'Donation Id #' . $order_details->id
            ]],
            'mode' => 'payment',
            'success_url' => route('frontend.donation.stripe.success'),
            'cancel_url' => route('frontend.donation.payment.cancel', $order_details->id),
        ]);
        Session::put('stripe_session_id', $session->id);
        Session::put('stripe_order_id', $order_details->id);
        return response()->json(['id' => $session->id]);

//        // stripe customer payment token
//        $stripe_token = $request->stripe_token;
//        $payment_log_details = DonationLogs::find($request->order_id);
//        Stripe::setApiKey( get_static_option('stripe_secret_key') );
//        $currency_code = get_static_option('site_global_currency');
//
//        if (!empty($stripe_token)){
//            // charge customer with your amount
//            $result = Charge::create(array(
//                "currency" => $currency_code,
//                "amount"   => $payment_log_details->amount * 100, // amount in cents,
//                'source' => $stripe_token,
//                'description' => 'Donation Payment From '. get_static_option('site_'.get_default_language().'_title').'. Order ID '.$payment_log_details->id .', Payer Name: '.$payment_log_details->name.', Payer Email: '.$payment_log_details->email,
//            ));
//        }
//
//        if ($result->status == 'succeeded'){
//
//            //update database
//            $this->update_database($request->order_id,$result->balance_transaction);
//            //send mail to admin/user
//            $this->sendEmail($request->order_id);
//            $order_id = Str::random(6).$request->order_id.Str::random(6);
//            return redirect()->route('frontend.donation.payment.success',$order_id);
//        }
//        return redirect()->route('frontend.donation.payment.cancel',$request->order_id);
    }

    public function razorpay_ipn(Request $request){

        $donation_logs = DonationLogs::find($request->order_id);

        //get API Configuration
        $api = new Api(get_static_option('razorpay_key'), get_static_option('razorpay_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if(!empty($request->razorpay_payment_id)) {
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=> $payment['amount']));
            } catch (\Exception $e) {
                return redirect()->route('frontend.donation.payment.cancel',$request->order_id);
            }

            //update database
            $this->update_database($request->order_id,$payment->id);
            //send mail to admin/user
            $this->sendEmail($request->order_id);

        }
        $order_id = Str::random(6).$request->order_id.Str::random(6);
        return redirect()->route('frontend.donation.payment.success',$order_id);

    }
    public function mollie_webhook(){
        $payment = session()->get('mollie_payment_id');
        $payment = Mollie::api()->payments->get($payment);
        $payment_log_details = DonationLogs::where('track',$payment->metadata->track)->first();

         if ($payment->isPaid()){

            //update database
             $this->update_database($payment_log_details->id,$payment->id);
            //send mail to admin/user
             $this->sendEmail($payment_log_details->id);

            session()->forget('mollie_payment_id');
            $order_id = Str::random(6).$payment_log_details->id.Str::random(6);
            return redirect()->route('frontend.donation.payment.success',$order_id);
        }

        return redirect()->route('frontend.donation.payment.cancel',$payment_log_details->id);
    }

    public function sendEmail($donation_log_id){
        $donation_details = DonationLogs::find($donation_log_id);
        $site_title = get_static_option('site_'.get_default_language().'_title');
        $customer_subject = __('Your donation payment success for').' '.$site_title;
        $admin_subject = __('You have a new donation payment from').' '.$site_title;
        $donation_notify_mail = get_static_option('donation_notify_mail');
        $admin_mail = !empty($donation_notify_mail) ? $donation_notify_mail : get_static_option('site_global_email');

        Mail::to($admin_mail)->send(new DonationMessage($donation_details,$admin_subject,'owner'));
        Mail::to($donation_details->email)->send(new DonationMessage($donation_details,$customer_subject,'customer'));
    }

    public function update_database($donation_log_id,$transaction_id){

        //update donation log status/transaction id
        $payment_log_details = DonationLogs::find($donation_log_id);
        $payment_log_details->status = 'complete';
        $payment_log_details->transaction_id = $transaction_id;
        $payment_log_details->save();

        //update donation raised amount
        $event_details = Donation::find($payment_log_details->donation_id);
        $event_details->raised = (int) $event_details->raised + (int) $payment_log_details->amount;
        $event_details->save();
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
        $paramList["CALLBACK_URL"] = route('frontend.donation.paytm.ipn');
        $paytm_merchant_key = get_static_option('paytm_merchant_key');

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );

        return array(
            'checkSum' => $checkSum,
            'paramList' => $paramList
        );
    }

    public function paystack_pay(){
        return Paystack::getAuthorizationUrl()->redirectNow();
    }
}
