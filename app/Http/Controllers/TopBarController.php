<?php

namespace App\Http\Controllers;

use App\Language;
use App\Menu;
use App\SocialIcons;
use App\SupportInfo;
use Illuminate\Http\Request;

class TopBarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function topbar_settings()
    {
        $all_social_icons = SocialIcons::all();
        return view('backend.pages.topbar-settings')->with(['all_social_icons' => $all_social_icons]);
    }

    public function update_topbar_settings(Request $request)
    {

        $this->validate($request, [
            'navbar_button' => 'nullable|string',
            'navbar_button_custom_url' => 'nullable|string',
            'navbar_button_custom_url_status' => 'nullable|string',
        ]);

        update_static_option('navbar_button', $request->navbar_button);
        update_static_option('navbar_button_custom_url', $request->navbar_button_custom_url);
        update_static_option('navbar_button_custom_url_status', $request->navbar_button_custom_url_status);

        $all_lang = Language::all();
        foreach ($all_lang as $lang) {
            $filed_name = 'navbar_' . $lang->slug . '_button_text';
            update_static_option('navbar_' . $lang->slug . '_button_text', $request->$filed_name);
        }



        return redirect()->back()->with(['msg' => __('Navbar Settings Updated..'), 'type' => 'success']);
    }

    public function new_social_item(Request $request){
        $this->validate($request,[
           'icon' => 'required|string',
           'url' => 'required|string',
        ]);

        SocialIcons::create($request->all());

        return redirect()->back()->with([
            'msg' => __('New Social Item Added...'),
            'type' => 'success'
        ]);
    }
    public function update_social_item(Request $request){
        $this->validate($request,[
           'icon' => 'required|string',
           'url' => 'required|string',
        ]);

        SocialIcons::find($request->id)->update([
            'icon' => $request->icon,
            'url' => $request->url,
        ]);

        return redirect()->back()->with([
            'msg' => __('Social Item Updated...'),
            'type' => 'success'
        ]);
    }
    public function delete_social_item(Request $request,$id){
        SocialIcons::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Social Item Deleted...'),
            'type' => 'danger'
        ]);
    }

    public function update_top_menu(Request $request){

        $all_languages = Language::all();
        foreach ($all_languages as $lang){
            $this->validate($request,[
                'top_bar_'.$lang->slug.'_right_menu' => 'nullable|string|max:191'
            ]);
            $filed = 'top_bar_'.$lang->slug.'_right_menu';
            update_static_option('top_bar_'.$lang->slug.'_right_menu',$request->$filed);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated...'),'type' => 'success']);
    }
    public function update_top_button(Request $request){

        $all_languages = Language::all();
        foreach ($all_languages as $lang){
            $this->validate($request,[
                'top_bar_'.$lang->slug.'_button_text' => 'nullable|string|max:191'
            ]);
            $filed = 'top_bar_'.$lang->slug.'_button_text';
            update_static_option('top_bar_'.$lang->slug.'_button_text',$request->$filed);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated...'),'type' => 'success']);
    }
}
