<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;

class SetUserLanguages
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

        if (session()->has('lang')) {
            app()->setLocale(session()->get('lang'));
        } else {
            $defaultLang =  Language::where('default',1)->first();
            if (!empty($defaultLang)) {
                app()->setLocale($defaultLang->slug);
            }
        }
        return $next($request);
    }
}
