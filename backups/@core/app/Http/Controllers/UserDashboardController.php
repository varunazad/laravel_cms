<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Donation;
use App\DonationLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Mail\BasicMail;
use App\Mail\UserEmailVeiry;
use App\Order;
use App\PaymentLogs;
use App\ProductOrder;
use App\Products;
use App\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function user_index(){
        $user_details = User::find(Auth::guard('web')->user()->id);
        $package_orders = Order::where('user_id',$user_details->id)->orderBy('id','DESC')->paginate(10);
        $event_attendances = EventAttendance::where('user_id',$user_details->id)->orderBy('id','DESC')->paginate(10);
        $product_orders = ProductOrder::where('user_id',$user_details->id)->orderBy('id','DESC')->paginate(10);
        $product_success_orders = ProductOrder::where(['user_id' => $user_details->id ,'payment_status' => 'complete'])->orderBy('id','DESC')->paginate(10);
        $donation = DonationLogs::where('user_id',$user_details->id)->orderBy('id','DESC')->paginate(10);

        $downloads = [];
        if (!empty($product_success_orders)){
            foreach ($product_success_orders as $order){
                $cart_items = unserialize($order->cart_items);
                foreach ($cart_items as $product){
                    $product_details = Products::find($product['id']);
                    if (!empty($product_details->is_downloadable)){
                        if (array_key_exists($product_details->id,$downloads)){
                            $new_quantity = intval($downloads[$product_details->id]['quantity']) + intval($product['quantity']);
                            $downloads[$product_details->id] = [
                                'order_id' => $order->id,
                                'order_date' => $order->created_at,
                                'id' => $product_details->id,
                                'image' => $product_details->image,
                                'slug' => $product_details->slug,
                                'title' => $product_details->title,
                                'date' => $product_details->created_at,
                                'quantity' => $new_quantity,
                                'amount' => $product_details->sale_price * $new_quantity,
                                'downloadable_file' => $product_details->downloadable_file,
                                'downloadable_file_link' => $product_details->downloadable_file_link,
                            ];
                        }else{
                            $downloads[$product_details->id] = [
                                'order_id' => $order->id,
                                'order_date' => $order->created_at,
                                'image' => $product_details->image,
                                'id' => $product_details->id,
                                'slug' => $product_details->slug,
                                'title' => $product_details->title,
                                'date' => $product_details->created_at,
                                'quantity' => $product['quantity'],
                                'amount' => $product_details->sale_price * $product['quantity'],
                                'downloadable_file' => $product_details->downloadable_file,
                                'downloadable_file_link' => $product_details->downloadable_file_link,
                            ];
                        }
                    }
                }
            }
        }

        return view('frontend.user.dashboard.user-home')->with(
            [
                'user_details' => $user_details,
                'package_orders' => $package_orders,
                'event_attendances' => $event_attendances,
                'product_orders' => $product_orders,
                'donation' => $donation,
                'downloads' => $downloads,
            ]);
    }
    public function user_email_verify_index(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }
        if (empty($user_details->email_verify_token)){
            User::find($user_details->id)->update(['email_verify_token' => \Str::random(20)]);
            $user_details = User::find($user_details->id);

            $message_body = __('Here is your verification code').' <span class="verify-code">'.$user_details->email_verify_token.'</span>';

            Mail::to($user_details->email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        }
        return view('frontend.user.email-verify');
    }

    public function reset_user_email_verify_code(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }

        $message_body = __('Here is your verification code').' <span class="verify-code">'.$user_details->email_verify_token.'</span>';

        Mail::to($user_details->email)->send(new BasicMail([
            'subject' => __('Verify your email address'),
            'message' => $message_body
        ]));

        return redirect()->route('user.email.verify')->with(['msg' => __('Resend Verify Email Success'),'type' => 'success']);
    }

    public function user_email_verify(Request $request){
        $this->validate($request,[
            'verification_code' => 'required'
        ],[
            'verification_code.required' => __('verify code is required')
        ]);
        $user_details = Auth::guard('web')->user();
        $user_info = User::where(['id' =>$user_details->id,'email_verify_token' => $request->verification_code])->first();
        if (empty($user_info)){
            return redirect()->back()->with(['msg' => __('your verification code is wrong, try again'),'type' => 'danger']);
        }
        $user_info->email_verified = 1;
        $user_info->save();
        return redirect()->route('user.home');
    }

    public function user_profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'address' => 'nullable|string',
        ],[
            'name.' => __('name is required'),
            'email.required' => __('email is required'),
            'email.email' => __('provide valid email'),
        ]);
        User::find(Auth::guard()->user()->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
            'phone' => $request->phone,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'address' => $request->address,
            ]
        );

        return redirect()->back()->with(['msg' => __('Profile Update Success'), 'type' => 'success']);
    }

    public function user_password_change(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ],
        [
            'old_password.required' => __('Old password is required'),
            'password.required' => __('Password is required'),
            'password.confirmed' => __('password must have be confirmed')
        ]
        );

        $user = User::findOrFail(Auth::guard()->user()->id);

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::guard('web')->logout();

            return redirect()->route('user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function download_file($id){
        $product_details = Products::find($id);
        $product_success_orders = ProductOrder::where(['user_id' => Auth::guard('web')->user()->id ,'payment_status' => 'complete'])->orderBy('id','DESC')->paginate(10);
        $downloads = [];
        if (!empty($product_success_orders)){
            foreach ($product_success_orders as $order){
                $cart_items = unserialize($order->cart_items);
                foreach ($cart_items as $product){
                    if ($product['id'] == $id){
                        //check this user purchased this item or not
                        if (file_exists('assets/uploads/downloadable/'.$product_details->downloadable_file)){
                            $temp_file = asset('assets/uploads/downloadable/'.$product_details->downloadable_file);
                            $file = new Filesystem();
                            $file->copy($temp_file, 'assets/uploads/downloadable/'.\Str::slug($product_details->title).'.zip');
                            return response()->download('assets/uploads/downloadable/'.\Str::slug($product_details->title).'.zip')->deleteFileAfterSend(true);
                        }
                    }
                }
            }
        }
        return redirect()->route('user.home');
    }

    public function package_order_cancel(Request $request){
        $this->validate($request,[
            'order_id' => 'required'
        ]);
        $order_details = Order::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        $payment_log = PaymentLogs::where('order_id',$request->order_id)->first();

        //send mail to admin
        $order_page_form_mail =  get_static_option('order_page_form_mail');
        $order_mail = $order_page_form_mail ? $order_page_form_mail : get_static_option('site_global_email');
        $order_details->status = 'cancel';
        $order_details->save();
        //send mail to customer
        $data['subject'] = __('one of your package order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your package order ').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user');

        //send mail while order status change
        Mail::to($order_mail)->send(new BasicMail($data));
        if (!empty($payment_log)){
            //send mail to customer
            $data['subject'] = __('your order status has been cancel');
            $data['message'] = __('hello'). '<br>';
            $data['message'] .= __('your order').' #'.$order_details->id.' ';
            $data['message'] .= __('status has been changed to cancel');
            //send mail while order status change
            Mail::to($payment_log->email)->send(new BasicMail($data));
        }
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function product_order_cancel(Request $request)
    {
        $order_details = ProductOrder::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        ProductOrder::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        //send mail to admin
        $data['subject'] = __('one of your product order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your product order ').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        Mail::to(get_static_option('site_global_email'))->send(new BasicMail($data));

        //send mail to customer
        $data['subject'] = __('your order status has been cancel');
        $data['message'] = __('hello').$order_details->billing_name. '<br>';
        $data['message'] .= __('your order').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');
        //send mail while order status change
        Mail::to($order_details->billing_email)->send(new BasicMail($data));

        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function event_order_cancel(Request $request)
    {
        $order_details = EventAttendance::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        EventAttendance::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);
        $event_payment_log = EventPaymentLogs::where(['attendance_id' => $request->order_id])->first();
        $admin_mail = !empty(get_static_option('event_attendance_receiver_mail')) ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');
        //send mail to admin
        $data['subject'] = __('one of your event booking order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your event attendance id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        Mail::to($admin_mail)->send(new BasicMail($data));

        if (!empty($event_payment_log)){
            //send mail to customer
            $data['subject'] = __('your event booking has benn cancelled');
            $data['message'] = __('hello').$event_payment_log->name. '<br>';
            $data['message'] .= __('your event attendance id').' #'.$order_details->id.' ';
            $data['message'] .= __('booking status has been changed to cancel.');
            //send mail while order status change
            Mail::to($event_payment_log->email)->send(new BasicMail($data));
        }
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function donation_order_cancel(Request $request)
    {
        $order_details = DonationLogs::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        DonationLogs::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        $donation_notify_mail = get_static_option('donation_notify_mail');
        $admin_mail = !empty($donation_notify_mail) ? $donation_notify_mail : get_static_option('site_global_email');

        //send mail to admin
        $data['subject'] = __('one of your donation has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your donation log id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        Mail::to($admin_mail)->send(new BasicMail($data));

        //send mail to customer
        $data['subject'] = __('your donation has benn cancelled');
        $data['message'] = __('hello').$order_details->name. '<br>';
        $data['message'] .= __('your donation log id').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');
        //send mail while order status change
        Mail::to($order_details->email)->send(new BasicMail($data));

        return redirect()->back()->with(['msg' => __('donation Cancel'), 'type' => 'warning']);
    }

    public function product_order_view($id){

        $order_details = ProductOrder::find($id);
        if (empty($order_details)) {
            return redirect_404_page();
        }
        return view('frontend.user.dashboard.product-order-view')->with(['order_details' => $order_details]);
    }

    //event_order_cancel
}
