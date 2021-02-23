<?php

namespace App\Http\Middleware\Permission;

use App\AdminRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class ServicesManage
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
        $page = strtolower(str_replace(' ','_','Services'));
        if (Auth::check()) {
            $id = auth()->user()->id;
            $role_id = \App\Admin::where('id',$id)->first();
            $user_role = \App\AdminRole::where('id', $role_id->role)->first();
            $all_permission = json_decode($user_role->permission);
            if (in_array($page, $all_permission)) {
                return $next($request);
            }
        }
        return redirect()->route('admin.home');
    }
}
