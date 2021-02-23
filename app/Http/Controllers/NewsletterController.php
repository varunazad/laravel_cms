<?php

namespace App\Http\Controllers;

use App\Mail\SubscriberMessage;
use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $all_subscriber = Newsletter::all();

        return view('backend.newsletter.newsletter-index')->with(['all_subscriber' => $all_subscriber]);
    }

    public function send_mail(Request $request){
        $this->validate($request,[
           'email' => 'required|email',
           'subject' => 'required',
           'message' => 'required',
        ]);

        $data = [
          'email' => $request->email,
          'subject' => $request->subject,
          'message' => $request->message,
        ];

        Mail::to($request->email)->send(new SubscriberMessage($data));

        return redirect()->back()->with([
            'msg' => __('Mail Send Success...'),
            'type' => 'success'
        ]);
    }
    public function delete($id){
        Newsletter::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Subscriber Delete Success....'),'type' => 'danger']);
    }

    public function send_mail_all_index(){
        return view('backend.newsletter.send-main-to-all');
    }

    public function send_mail_all(Request $request){
        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
        ]);
        $all_subscriber = Newsletter::all();

        foreach ($all_subscriber as $subscriber){
            $data = [
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            Mail::to($subscriber->email)->send(new SubscriberMessage($data));
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success..'),
            'type' => 'success'
        ]);
    }

    public function add_new_sub(Request $request){
        $this->validate($request,[
           'email' => 'required|email|unique:newsletters'
        ],
        [
            'email.required' => __('email field required')
        ]);

        Newsletter::create($request->all());
        return redirect()->back()->with([
            'msg' => __('New Subscriber Added..'),
            'type' => 'success'
        ]);
    }

    public function bulk_action(Request $request){
        $all = Newsletter::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}
