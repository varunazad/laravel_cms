<?php

namespace App\Http\Middleware\Module;

use App\AdminRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class EventModuleStatus
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
        if (!empty(get_static_option('events_module_status'))) {
            return $next($request);
        }
        return redirect()->route('homepage');
    }
}
