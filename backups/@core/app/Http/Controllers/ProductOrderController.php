<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Donation;
use App\DonationLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events;
use App\Http\Traits\PaytmTrait;
use App\Mail\PaymentSuccess;
use App\PaymentLogs;
use App\ProductOrder;
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

class ProductOrderController extends Controller
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

    public function product_checkout(Request $request){
        $this->validate($request,[
            'payment_gateway' => 'nullable|string',
            'subtotal' => 'required|string',
            'coupon_discount' => 'nullable|string',
            'shipping_cost' => 'nullable|string',
            'product_shippings_id' => 'nullable|string',
            'total' => 'required|string',
            'billing_name' => 'required|string',
            'billing_email' => 'required|string',
            'billing_phone' => 'required|string',
            'billing_country' => 'required|string',
            'billing_street_address' => 'required|string',
            'billing_town' => 'required|string',
            'billing_district' => 'required|string',
            'different_shipping_address' => 'nullable|string',
            'shipping_name' => 'nullable|string',
            'shipping_email' => 'nullable|string',
            'shipping_phone' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'shipping_street_address' => 'nullable|string',
            'shipping_town' => 'nullable|string',
            'shipping_district' => 'nullable|string'
        ],
        [
            'billing_name.required' => __('The billing name field is required.'),
            'billing_email.required' => __('The billing email field is required.'),
            'billing_phone.required' => __('The billing phone field is required.'),
            'billing_country.required' => __('The billing country field is required.'),
            'billing_street_address.required' => __('The billing street address field is required.'),
            'billing_town.required' => __('The billing town field is required.'),
            'billing_district.required' => __('The billing district field is required.')
        ]);

        $order_details = ProductOrder::find($request->order_id);
        if (empty($order_details)){
            $order_details = ProductOrder::create([
                'payment_gateway' => $request->selected_payment_gateway,
                'payment_status' => 'pending',
                'payment_track' => Str::random(10). Str::random(10),
                'user_id' => auth()->check() ? auth()->user()->id : null,
                'subtotal' => $request->subtotal,
                'coupon_discount' => $request->coupon_discount,
                'coupon_code' => session()->get('coupon_discount'),
                'shipping_cost' => $request->shipping_cost,
                'product_shippings_id' => $request->product_shippings_id,
                'total' => $request->total,
                'billing_name'  => $request->billing_name,
                'billing_email'  => $request->billing_email,
                'billing_phone'  => $request->billing_phone,
                'billing_country' => $request->billing_country,
                'billing_street_address' => $request->billing_street_address,
                'billing_town' => $request->billing_town,
                'billing_district' => $request->billing_district,
                'different_shipping_address' => $request->different_shipping_address ? 'yes' : 'no',
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_country' => $request->shipping_country,
                'shipping_street_address' => $request->shipping_street_address,
                'shipping_town' => $request->shipping_town,
                'shipping_district' => $request->shipping_district,
                'cart_items' => !empty(session()->get('cart_item')) ? serialize(session()->get('cart_item')) : '',
                'status' =>  'pending',
            ]);
        }


        if (empty(get_static_option('site_payment_gateway'))){
            rest_cart_session();
            $order_id = Str::random(6).$order_details->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);
        }

        //have to work on below code
        if ($request->selected_payment_gateway == 'cash_on_delivery'){
            $this->send_mail($order_details);
            $order_id = Str::random(6).$order_details->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);

        }elseif ($request->selected_payment_gateway == 'paypal'){

            $payable_amount = $order_details->total;
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

            $item_1->setName('Payment For Product Order Id: #'.$order_details->id.' Payer Name: '.$order_details->billing_name.' Payer Email:'.$order_details->billing_email) /** item name **/
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
                ->setDescription('Payment For Product Order Id: #'.$order_details->id.' Payer Name: '.$order_details->billing_name.' Payer Email:'.$order_details->billing_email);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(route('frontend.product.paypal.ipn')) /** Specify return URL **/
            ->setCancelUrl(route('frontend.product.payment.cancel',$order_details->id));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {

                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    return redirect()->route('frontend.product.payment.cancel',$order_details->id); //connection timeout
                } else {
                    return redirect()->route('frontend.product.payment.cancel',$order_details->id); //some error occur, then redirect to cancel page
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
            Session::put('paypal_track', $order_details->payment_track);

            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            return redirect_404_page(); //not redirect to paypal, that's why redirect in 404 page

        }elseif ($request->selected_payment_gateway == 'paytm'){

            $payable_amount = $order_details->total;
            if (!is_paytm_supported_currency() ){
                $payable_amount = get_amount_in_inr($payable_amount,get_static_option('site_global_currency'));
            }
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order' => $order_details->id,
                'user' => Str::slug($order_details->billing_name),
                'mobile_number' => rand(9999,99999999),
                'email' => $order_details->billing_email,
                'amount' => custom_number_format($payable_amount),
                'callback_url' => route('frontend.product.paytm.ipn')
            ]);
            return $payment->receive();

        }elseif ($request->selected_payment_gateway == 'manual_payment'){

            $this->validate($request,[
                'transaction_id_val' => 'required'
            ],[
                'transaction_id_val' => __('Transaction ID is required')
            ]);

            $order_details->transaction_id = $request->transaction_id_val;
            $order_details->save();
            $order_id = Str::random(6).$order_details->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);

        }elseif ($request->selected_payment_gateway == 'stripe'){

            $payable_amount = $order_details->total;
            $stripe_data['title'] = __('Payment of Your Order');
            $stripe_data['order_id'] = $order_details->id;
            $stripe_data['price'] = $payable_amount;
            $stripe_data['route'] = route('frontend.product.stripe.ipn');

            return view('frontend.payment.stripe')->with('stripe_data' ,$stripe_data);
        }
        elseif ($request->selected_payment_gateway == 'razorpay'){

            $payable_amount = $order_details->total;
            $currency_code = get_static_option('site_global_currency');

            if (!is_razorpay_supported_currency()){
                $payable_amount = get_amount_in_inr($order_details->total,get_static_option('site_global_currency'));
                $currency_code = 'INR';
            }

            $razorpay_data['currency_symbol'] = $currency_code;
            $razorpay_data['currency'] = $currency_code;
            $razorpay_data['price'] = $payable_amount;
            $razorpay_data['package_name'] = $order_details->billing_name;
            $razorpay_data['order_id'] = $order_details->id;
            $razorpay_data['route'] = route('frontend.product.razorpay.ipn');

            return view('frontend.payment.razorpay')->with('razorpay_data' ,$razorpay_data);
        }
        elseif ($request->selected_payment_gateway == 'paystack'){

            $payable_amount = $order_details->total;
            $currency_code = get_static_option('site_global_currency');
            if (!is_paystack_supported_currency()){
                $payable_amount = get_amount_in_ngn($order_details->total,get_static_option('site_global_currency'));
                $currency_code = 'NGN';
            }

            $paystack_data['currency'] = $currency_code;
            $paystack_data['price'] = custom_number_format( $payable_amount);
            $paystack_data['package_name'] =  __('Product Order');
            $paystack_data['name'] = $order_details->billing_name;
            $paystack_data['email'] = $order_details->billing_email;
            $paystack_data['order_id'] = $order_details->id;
            $paystack_data['track'] = $order_details->payment_track;
            $paystack_data['route'] = route('frontend.product.paystack.pay');
            $paystack_data['type'] = 'product';

            return view('frontend.payment.paystack')->with(['paystack_data' => $paystack_data]);
        }elseif ($request->selected_payment_gateway == 'mollie'){

            $payable_amount =  $order_details->total;
            $currency_code = get_static_option('site_global_currency');

            if (!is_mollie_supported_currency() ){
                $payable_amount = get_amount_in_usd($order_details->total,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }

            $payment = Mollie::api()->payments->create([
                "amount" => [
                    "currency" => $currency_code,
                    "value" => custom_number_format($payable_amount),//"10.00" // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => "Product Order Details ID #".$order_details->id .' Name: '.$order_details->billing_name.' Email: '.$order_details->billing_email,
                "redirectUrl" => route('frontend.product.mollie.webhook'),
                "metadata" => [
                    "order_id" => $order_details->id,
                    "track" => $order_details->payment_track,
                ],
            ]);

            $payment = Mollie::api()->payments->get($payment->id);

            session()->put('mollie_payment_id',$payment->id);

            // redirect customer to Mollie checkout page
            return redirect($payment->getCheckoutUrl(), 303);
        }elseif ($request->selected_payment_gateway == 'flutterwave'){

            $payable_amount = $order_details->total;
            $currency_code = get_static_option('site_global_currency');

            if (!is_flutterwave_supported_currency()){
                $payable_amount = get_amount_in_usd($order_details->total,get_static_option('site_global_currency'));
                $currency_code = 'USD';
            }

            $flutterwave_data['currency'] = $currency_code;
            $flutterwave_data['name'] = $order_details->billing_name;
            $flutterwave_data['form_action'] = route('frontend.product.flutterwave.pay');
            $flutterwave_data['amount'] = $payable_amount;
            $flutterwave_data['description'] = "Order Details ID #".$order_details->id .' Name: '.$order_details->billing_name;
            $flutterwave_data['email'] = $order_details->billing_email;
            $flutterwave_data['country'] = get_visitor_country() ? get_visitor_country() : 'NG';
            $flutterwave_data['metadata'] = [
                ['metaname' => 'order_id', 'metavalue' => $order_details->id],
                ['metaname' => 'track', 'metavalue' => $order_details->payment_track],
            ];
            return view('frontend.payment.flutterwave')->with('flutterwave_data' ,$flutterwave_data);
        }

        return redirect()->route('homepage');
    }
    public function flutterwave_pay(Request $request){
        Rave::initialize(route('frontend.product.flutterwave.callback'));
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

        $payment_logs = ProductOrder::where('payment_track', $track)->first();
        if (($chargeResponsecode == "00" || $chargeResponsecode == "0")){
            //update event payment log
            $transaction_id = $txRef;

            //update database
            $this->update_database($payment_logs->id,$transaction_id);
            //send mail
            $this->send_mail( $payment_logs);

            $order_id = Str::random(6).$payment_logs->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);

        }else{
            return redirect()->route('frontend.product.payment.cancel',$payment_logs->id);
        }

    }

    public function mollie_webhook(){
        $payment_id = session()->get('mollie_payment_id');
        $payment = Mollie::api()->payments->get($payment_id);
        session()->forget('mollie_payment_id');

        $payment_logs = ProductOrder::where( 'payment_track', $payment->metadata->track )->first();
        if ($payment->isPaid()){
            //update event payment logs
            $transaction_id = $payment->id;
            //update database
            $this->update_database($payment_logs->id,$transaction_id);
            //send mail
            $this->send_mail( $payment_logs);
            $order_id = Str::random(6).$payment_logs->id.Str::random(6);

            return redirect()->route('frontend.product.payment.success',$order_id);
        }

        return redirect()->route('frontend.product.payment.cancel',$payment_logs->id);
    }


    public function paypal_ipn(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $paypal_track = Session::get('paypal_track');
        $payment_logs = ProductOrder::where('payment_track', $paypal_track)->first();

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        Session::forget('paypal_track');

        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('frontend.product.payment.cancel',$payment_logs->id);
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
            $this->send_mail($payment_logs);
            $order_id = Str::random(6).$payment_logs->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);
        }
        return redirect()->route('frontend.product.payment.cancel',$payment_logs->id);
    }

    public function paytm_ipn(Request $request){
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        $order_details = ProductOrder::find($response['ORDERID']);
        if($transaction->isSuccessful()){

            //update database
            $this->update_database($order_details->id,$response['TXNID']);
            //send success mail to user and admin
            $this->send_mail($order_details);
            $order_id = Str::random(6).$order_details->id.Str::random(6);
            return redirect()->route('frontend.product.payment.success',$order_id);


        }else if($transaction->isFailed()){
            //Transaction Failed
            return redirect()->route('frontend.product.payment.cancel',$order_details->id);
        }
        return redirect_404_page();
    }

    public function stripe_success()
    {
        //have to verify stripe payment and redirect to success page
        $stripe_session_id = Session::get('stripe_session_id');
        $stripe_order_id = Session::get('stripe_order_id');
        $order_details = ProductOrder::find($stripe_order_id);
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
                $this->send_mail($order_details);
                $order_id = Str::random(6) . $order_details->id . Str::random(6);
                return redirect()->route('frontend.product.payment.success', $order_id);
            }
            return route('frontend.product.payment.cancel', $order_details->id);
        }else{
            //not paid
            return route('frontend.product.payment.cancel', $order_details->id);
        }
    }

    public function stripe_ipn(Request $request)
    {
        // stripe customer payment token
        $order_details = ProductOrder::find($request->order_id);
        Stripe::setApiKey(get_static_option('stripe_secret_key'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => get_static_option('site_global_currency'),
                    'product_data' => [
                        'name' => __('Product Order Checkout'),
                    ],
                    'unit_amount' => $order_details->total * 100,
                ],
                'quantity' => 1,
                'description' => 'Product Order Id #' . $order_details->id
            ]],
            'mode' => 'payment',
            'success_url' => route('frontend.product.stripe.success'),
            'cancel_url' => route('frontend.product.payment.cancel', $request->order_id),
        ]);
        Session::put('stripe_session_id', $session->id);
        Session::put('stripe_order_id', $order_details->id);
        return response()->json(['id' => $session->id]);
    }


    public function razorpay_ipn(Request $request){

        $order_details = ProductOrder::find($request->order_id);

        //get API Configuration
        $api = new Api(get_static_option('razorpay_key'), get_static_option('razorpay_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if(!empty($request->razorpay_payment_id)) {
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=> $payment['amount']));
            } catch (\Exception $e) {
                return redirect()->route('frontend.product.payment.cancel',$request->order_id);
            }
            // Do something here for store payment details in database...
            //update database
            $this->update_database($order_details->id,$payment->id);
            //send mail
            $this->send_mail( $order_details);

        }

        $order_id = Str::random(6).$request->order_id.Str::random(6);
        return redirect()->route('frontend.product.payment.success',$order_id);

    }

    public function update_database($order_id,$transaction_id){
        ProductOrder::find($order_id)->update(['payment_status' => 'complete', 'transaction_id' => $transaction_id ]);
        rest_cart_session();
    }

    public function send_mail($order_details){
        $order_details = ProductOrder::find($order_details->id);
        $site_title = get_static_option('site_'.get_default_language().'_title');
        $customer_subject = __('You order has been placed in').' '.$site_title;
        $admin_subject = __('You Have A New Product Order From').' '.$site_title;

        Mail::to(get_static_option('site_global_email'))->send(new \App\Mail\ProductOrder($order_details,'owner',$admin_subject));
        Mail::to($order_details->billing_email)->send(new \App\Mail\ProductOrder($order_details,'customer',$customer_subject));
    }

    public function paystack_pay(){
        return Paystack::getAuthorizationUrl()->redirectNow();
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
        $paramList["CALLBACK_URL"] = route('frontend.product.paytm.ipn');
        $paytm_merchant_key = get_static_option('paytm_merchant_key');

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );

        return array(
            'checkSum' => $checkSum,
            'paramList' => $paramList
        );
    }
}
