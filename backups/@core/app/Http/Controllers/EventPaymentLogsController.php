<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Donation;
use App\DonationLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events;
use App\Http\Traits\PaytmTrait;
use App\Mail\ContactMessage;
use App\Mail\PaymentSuccess;
use App\Order;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
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
use Mollie\Laravel\Facades\Mollie;
use Stripe\Stripe;
use Unicodeveloper\Paystack\Facades\Paystack;
use function App\Http\Traits\getChecksumFromArray;

class EventPaymentLogsController extends Controller
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

    public function booking_payment_form(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'attendance_id' => 'required|string'
        ],
        [
            'name.required' => __('Name field is required'),
            'email.required' => __('Email field is required')
        ]);
        $event_details = EventAttendance::find($request->attendance_id);
        $event_info = Events::find($event_details->event_id);
        $event_payment_details = EventPaymentLogs::where('attendance_id',$request->attendance_id)->first();

        if (!empty($event_info->cost) && $event_info->cost > 0){
            $this->validate($request,[
                'payment_gateway' => 'required|string'
            ],[
                'payment_gateway.required' => __('Select A Payment Method')
            ]);
        }

        if (empty($event_payment_details)){
            $payment_log_id = EventPaymentLogs::create([
                'email' =>  $request->email,
                'name' =>  $request->name,
                'event_name' =>  $event_details->event_name,
                'event_cost' =>  ($event_details->event_cost * $event_details->quantity),
                'package_gateway' =>  $request->payment_gateway,
                'attendance_id' =>  $request->attendance_id,
                'status' =>  'pending',
                'track' =>  Str::random(10). Str::random(10),
            ])->id;
            $event_payment_details = EventPaymentLogs::find($payment_log_id);
        }
        //have to work on below code
        if ($request->payment_gateway == 'paypal'){

            $payable_amount = $event_payment_details->event_cost;
            $currency_code = get_static_option('site_global_currency');
            if (!is_paypal_supported_currency()){
                $payable_amount = get_amount_in_usd($event_payment_details->event_cost ,get_static_option('site_global_currency'));
                if ($payable_amount < 1){
                    return $payable_amount.__('USD amount is not supported by paypal');
                }
                $currency_code = 'USD';
            }

            /* new code */
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item_1 = new Item();

            $item_1->setName('Event Payment Details Attendance Id: #'.$request->attendance_id .' Name: '.$event_payment_details->name.' Email: '.$event_payment_details->email) /** item name **/
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
                ->setDescription('Event Payment Details Attendance Id: #'.$request->attendance_id .' Name: '.$event_payment_details->name.' Email: '.$event_payment_details->email);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(route('frontend.event.paypal.ipn')) /** Specify return URL **/
            ->setCancelUrl(route('frontend.event.payment.cancel',$event_payment_details->attendance_id));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {

                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    return redirect()->route('frontend.event.payment.cancel',$event_payment_details->attendance_id); //connection timeout
                } else {
                    return redirect()->route('frontend.event.payment.cancel',$event_payment_details->attendance_id); //some error occur, then redirect to cancel page
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
            Session::put('paypal_track', $event_payment_details->track);

            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            return redirect_404_page(); //not redirect to paypal, that's why redirect in 404 page

        }elseif ($request->payment_gateway == 'paytm'){

            $payable_amount = $event_payment_details->event_cost ;
            if (!is_paytm_supported_currency() ){
                $payable_amount = get_amount_in_inr($event_payment_details->event_cost ,get_static_option('site_global_currency'));
            }
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order' => $event_payment_details->attendance_id,
                'user' => Str::slug($event_payment_details->name),
                'mobile_number' => rand(9999,99999999),
                'email' => $event_payment_details->email,
                'amount' => custom_number_format($payable_amount),
                'callback_url' => route('frontend.event.paytm.ipn')
            ]);
            return $payment->receive();

        }elseif ($request->payment_gateway == 'manual_payment'){
            $order = EventAttendance::where( 'id', $request->attendance_id )->first();
            $order->status = 'pending';
            $order->save();
            EventPaymentLogs::where('attendance_id',$request->attendance_id)->update(['transaction_id' => $request->transaction_id]);
            $order_id = Str::random(6).$event_payment_details->attendance_id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);

        }elseif ($request->payment_gateway == 'stripe'){

            $order = EventAttendance::where( 'id', $request->attendance_id )->first();

            $stripe_data['title'] = __('Payment of event:').' '.$order->event_name;
            $stripe_data['order_id'] = $order->id;
            $stripe_data['price'] = $event_payment_details->event_cost;
            $stripe_data['route'] = route('frontend.event.stripe.ipn');

            return view('frontend.payment.stripe')->with('stripe_data' ,$stripe_data);
        }
        elseif ($request->payment_gateway == 'razorpay'){
            
            $attendance_details = EventAttendance::where( 'id', $request->attendance_id )->first();
            $payable_amount =  $event_payment_details->event_cost ;

            $currency_code = get_static_option('site_global_currency');
            if (!is_razorpay_supported_currency()){
                $payable_amount = get_amount_in_inr($event_payment_details->event_cost,get_static_option('site_global_currency'));
                $currency_code = 'INR';
            }

            $razorpay_data['currency_symbol'] = $currency_code;
            $razorpay_data['currency'] = $currency_code;
            $razorpay_data['price'] = $payable_amount;
            $razorpay_data['package_name'] = $attendance_details->event_name;
            $razorpay_data['order_id'] = $attendance_details->id;
            $razorpay_data['route'] = route('frontend.event.razorpay.ipn');

            return view('frontend.payment.razorpay')->with('razorpay_data' ,$razorpay_data);
        }
        elseif ($request->payment_gateway == 'paystack'){

            $attendance_details = EventAttendance::where( 'id', $request->attendance_id )->first();
            $event_payment_details = EventPaymentLogs::where('attendance_id',$attendance_details->id)->first();

            $payable_amount = $event_payment_details->event_cost;
            $currency_code = get_static_option('site_global_currency');

            if (!is_paystack_supported_currency()){
                $payable_amount = get_amount_in_ngn($event_payment_details->event_cost,get_static_option('site_global_currency'));
                $currency_code = 'NGN';
            }

            $paystack_data['currency'] = $currency_code;
            $paystack_data['price'] = $payable_amount;
            $paystack_data['package_name'] =  $attendance_details->event_name;
            $paystack_data['name'] = $event_payment_details->name;
            $paystack_data['email'] = $event_payment_details->email;
            $paystack_data['order_id'] = $attendance_details->id;
            $paystack_data['track'] = $event_payment_details->track;
            $paystack_data['route'] = route('frontend.event.paystack.pay');
            $paystack_data['type'] = 'event';
            return view('frontend.payment.paystack')->with(['paystack_data' => $paystack_data]);
        }
        elseif ($request->payment_gateway == 'mollie'){

            $attendance_details = EventAttendance::where( 'id', $request->attendance_id )->first();
            $event_payment_details = EventPaymentLogs::where('attendance_id',$attendance_details->id)->first();

            $payable_amount = $event_payment_details->event_cost;
            $currency_code = get_static_option('site_global_currency');

            if (!is_mollie_supported_currency() ){
                $payable_amount = get_amount_in_usd($event_payment_details->event_cost,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }

            $payment = Mollie::api()->payments->create([
                "amount" => [
                    "currency" => $currency_code,
                    "value" => custom_number_format($payable_amount),//"10.00" // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => "Event Payment Details ".' Name: '.$event_payment_details->name.' Email: '.$event_payment_details->email,
                "redirectUrl" => route('frontend.event.mollie.webhook'),
                "metadata" => [
                    "order_id" => $attendance_details->id,
                    "track" => $event_payment_details->track,
                ],
            ]);

            $payment = Mollie::api()->payments->get($payment->id);

            session()->put('mollie_payment_id',$payment->id);

            // redirect customer to Mollie checkout page
            return redirect($payment->getCheckoutUrl(), 303);
        }elseif ($request->payment_gateway == 'flutterwave'){

            $attendance_details = EventAttendance::where( 'id', $request->attendance_id )->first();
            $event_payment_details = EventPaymentLogs::where('attendance_id',$attendance_details->id)->first();

            $payable_amount = $event_payment_details->event_cost;
            $currency_code = get_static_option('site_global_currency');

            if (!is_flutterwave_supported_currency()){
                $payable_amount = get_amount_in_usd($event_payment_details->event_cost ,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }

            $flutterwave_data['form_action'] = route('frontend.event.flutterwave.pay');
            $flutterwave_data['currency'] = $currency_code;
            $flutterwave_data['name'] = $event_payment_details->name;
            $flutterwave_data['amount'] = $payable_amount;
            $flutterwave_data['description'] = "Event Payment Details ID #".$attendance_details->id .' Name: '.$event_payment_details->name;
            $flutterwave_data['email'] = $event_payment_details->email;
            $flutterwave_data['country'] = get_visitor_country() ? get_visitor_country() : 'NG';
            $flutterwave_data['metadata'] = [
                ['metaname' => 'order_id', 'metavalue' => $attendance_details->id],
                ['metaname' => 'track', 'metavalue' => $event_payment_details->track],
            ];
            return view('frontend.payment.flutterwave')->with('flutterwave_data' ,$flutterwave_data);
        }


        return redirect()->route('homepage');
    }
    public function flutterwave_pay(Request $request){
        Rave::initialize(route('frontend.event.flutterwave.callback'));
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

        $payment_logs = EventPaymentLogs::where( 'track', $track )->first();
        if (($chargeResponsecode == "00" || $chargeResponsecode == "0")){
            //update event payment log
            $transaction_id = $txRef;
            //update database
            $this->update_database($payment_logs->attendance_id,$transaction_id);
            //send success mail to user and admin
            $this->send_event_mail($payment_logs->attendance_id);
            $order_id = Str::random(6).$payment_logs->attendance_id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);
        }else{
            return redirect()->route('frontend.event.payment.cancel',$payment_logs->attendance_id);
        }

    }

    public function mollie_webhook(){
        $payment_id = session()->get('mollie_payment_id');
        $payment = Mollie::api()->payments->get($payment_id);
        session()->forget('mollie_payment_id');

          $payment_logs = EventPaymentLogs::where( 'track', $payment->metadata->track )->first();

         if ($payment->isPaid()){
            //update database
             $this->update_database($payment_logs->attendance_id,$payment->id);
            //send success mail to user and admin
             $this->send_event_mail($payment_logs->attendance_id);
             $order_id = Str::random(6).$payment_logs->attendance_id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);

        }
        return redirect()->route('frontend.event.payment.cancel',$payment_logs->attendance_id);
    }

    public function paypal_ipn(Request $request)
    {

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $paypal_track = Session::get('paypal_track');
        $payment_logs = EventPaymentLogs::where('track', $paypal_track)->first();

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        Session::forget('paypal_track');

        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('frontend.event.payment.cancel',$payment_logs->attendance_id);
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {

            //database update
            $this->update_database($payment_logs->attendance_id,$payment_id);
            //send mail
            $this->send_event_mail($payment_logs->attendance_id);
            $order_id = Str::random(6).$payment_logs->attendance_id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);
        }
        return redirect()->route('frontend.event.payment.cancel',$payment_logs->attendance_id);

    }

    public function paytm_ipn(Request $request){
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        $attendanc_details = EventAttendance::find($response['ORDERID']);
        if($transaction->isSuccessful()){

            //update database
            $this->update_database($attendanc_details->id,$response['TXNID']);
            //send success mail to user and admin
            $this->send_event_mail($attendanc_details->id);
            $order_id = Str::random(6).$attendanc_details->id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);


        }else if($transaction->isFailed()){
            //Transaction Failed
            return redirect()->route('frontend.event.payment.cancel',$attendanc_details->id);
        }
        return redirect_404_page();
    }

    public function stripe_success()
    {
        //have to verify stripe payment and redirect to success page
        $stripe_session_id = Session::get('stripe_session_id');
        $stripe_order_id = Session::get('stripe_order_id');

        $order_details = EventAttendance::find($stripe_order_id);
        $payment_log_details = EventPaymentLogs::where('attendance_id',$order_details->id)->first();

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
                //update database
            $this->update_database($payment_log_details->attendance_id,$transaction_id);

            //send success mail to user and admin
            $this->send_event_mail($payment_log_details->attendance_id);
            $order_id = Str::random(6).$order_details->id.Str::random(6);
            return redirect()->route('frontend.event.payment.success',$order_id);
            }
            return route('frontend.event.payment.cancel', $order_details->id);
        }else{
            //not paid
            return route('frontend.event.payment.cancel', $order_details->id);
        }
    }

    public function stripe_ipn(Request $request)
    {
        // stripe customer payment token
        $order_details = EventAttendance::find($request->order_id);
        $payment_log_details = EventPaymentLogs::where('attendance_id',$order_details->id)->first();

        Stripe::setApiKey(get_static_option('stripe_secret_key'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => get_static_option('site_global_currency'),
                    'product_data' => [
                        'name' => $order_details->event_name,
                    ],
                    'unit_amount' => $payment_log_details->event_cost * 100,
                ],
                'quantity' => 1,
                'description' => 'Event Booking Order Id #' . $order_details->id
            ]],
            'mode' => 'payment',
            'success_url' => route('frontend.event.stripe.success'),
            'cancel_url' => route('frontend.event.payment.cancel', $request->order_id),
        ]);
        Session::put('stripe_session_id', $session->id);
        Session::put('stripe_order_id', $order_details->id);
        return response()->json(['id' => $session->id]);

        // stripe customer payment token
//        $stripe_token = $request->stripe_token;
//        $order_details = EventAttendance::find($request->order_id);
//        $payment_log_details = EventPaymentLogs::where('attendance_id',$request->order_id)->first();
//        $site_currency = get_static_option('site_global_currency');
//
//        Stripe::setApiKey( get_static_option('stripe_secret_key') );
//        if (!empty($stripe_token) ){
//            // charge customer with your amount
//            $result = Charge::create(array(
//                "currency" => $site_currency,
//                "amount"   => $payment_log_details->event_cost  * 100, // amount in cents,
//                'source' => $stripe_token,
//                'description' => 'Payment From '. get_static_option('site_'.get_default_language().'_title').'. Order ID '.$order_details->id .', Payer Name: '.$payment_log_details->name.', Payer Email: '.$payment_log_details->email,
//            ));
//        }
//
//        if ($result->status == 'succeeded'){
//            //update database
//            $this->update_database($payment_log_details->attendance_id,$result->balance_transaction);
//
//            //send success mail to user and admin
//            $this->send_event_mail($payment_log_details->attendance_id);
//            $order_id = Str::random(6).$request->order_id.Str::random(6);
//            return redirect()->route('frontend.event.payment.success',$order_id);
//
//        }
//        return redirect()->route('frontend.event.payment.cancel',$request->order_id);

    }

    public function razorpay_ipn(Request $request){

        $order_details = EventAttendance::find($request->order_id);
        //get API Configuration
        $api = new Api(get_static_option('razorpay_key'), get_static_option('razorpay_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if(!empty($request->razorpay_payment_id)) {
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=> $payment['amount']));
            } catch (\Exception $e) {
                return redirect()->route('frontend.event.payment.cancel',$request->order_id);
            }

            //call public function
            $this->update_database($order_details->id,$payment->id);

            //send success mail to user and admin
            $this->send_event_mail($order_details->id);
        }
        $order_id = Str::random(6).$request->order_id.Str::random(6);
        return redirect()->route('frontend.event.payment.success',$order_id);

    }

    public function paystack_pay(){
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    public function update_database($event_attendance_id,$transaction_id){

        //update attendance status
        $order_details = EventAttendance::find($event_attendance_id);
        $order_details->payment_status = 'complete';
        $order_details->status = 'complete';
        $order_details->save();

        //update event payment log
        EventPaymentLogs::where('attendance_id',$event_attendance_id)->update([
            'transaction_id' => $transaction_id,
            'status' => 'complete'
        ]);

        //update event available tickets
        $event_details = Events::find($order_details->event_id);
        $event_details->available_tickets = (int) $event_details->available_tickets - $order_details->quantity;
        $event_details->save();
    }

    public function send_event_mail($event_attendance_id){
        $event_attendance = EventAttendance::find($event_attendance_id);

        $order_mail = get_static_option('event_attendance_receiver_mail') ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');
        $event_details = Events::find($event_attendance->event_id);
        $event_payment_logs = EventPaymentLogs::where('attendance_id',$event_attendance->id)->first();

        //send mail to admin
        $subject = __('you have an event booking order');
        $message = __('you have an event booking order. attendance Id').' #'.$event_attendance->id;
        $message .= ' '.__('at').' '.date_format($event_attendance->created_at,'d F Y H:m:s');
        $message .= ' '.__('via').' '.str_replace('_',' ',$event_payment_logs->package_gateway);
        $admin_mail = !empty(get_static_option('event_attendance_receiver_mail')) ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');

        Mail::to($admin_mail)->send(new \App\Mail\EventAttendance([
            'subject' => $subject,
            'message' => $message,
            'event_attendance' => $event_attendance,
            'event_details' => $event_details,
            'event_payment_logs' => $event_payment_logs,
        ]));

        //send mail to user
        $subject = __('your event booking order has been placed');
        $message = __('your event booking order has been placed. attendance Id').' #'.$event_attendance->id;
        $message .= ' '.__('at').' '.date_format($event_attendance->created_at,'d F Y H:m:s');
        $message .= ' '.__('via').' '.str_replace('_',' ',$event_payment_logs->package_gateway);
        Mail::to($order_mail)->send(new \App\Mail\EventAttendance([
            'subject' => $subject,
            'message' => $message,
            'event_attendance' => $event_attendance,
            'event_details' => $event_details,
            'event_payment_logs' => $event_payment_logs,
        ]));

    }

}
