<?php

namespace App\Http\Middleware;

use App\Blog;
use App\Language;
use App\Menu;
//use App\SocialIcons;
//use App\SupportInfo;
use App\SocialIcons;
use App\Widgets;
use Closure;

class GlobalVariableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        view()->composer('*', function ($view) {
            $lang = !empty(session()->get('lang')) ? session()->get('lang') : Language::where('default',1)->first()->slug;
            $all_social_item = SocialIcons::all();
            $all_usefull_links = Menu::find(get_static_option('useful_link_'.get_user_lang().'_widget_menu_id'));
            $all_important_links = Menu::find(get_static_option('important_link_'.get_user_lang().'_widget_menu_id'));
            $all_recent_post = Blog::where('lang' ,$lang)->orderBy('id', 'DESC')->take(get_static_option('recent_post_widget_item'))->get();
            $all_language = Language::where('status', 'publish')->get();
            $primary_menu = Menu::where(['status' => 'default' ,'lang' => $lang])->first();
            $footer_widgets = Widgets::orderBy('widget_order','ASC')->get();

            $view->with('all_usefull_links', $all_usefull_links);
            $view->with('all_important_links', $all_important_links);
            $view->with('all_recent_post', $all_recent_post);
            $view->with('all_social_item', $all_social_item);
            $view->with('all_language', $all_language);
            $view->with('primary_menu', $primary_menu->id);
            $view->with('footer_widgets', $footer_widgets);
            $view->with('user_select_lang_slug', $lang);
        });

        return $next($request);
    }
}
