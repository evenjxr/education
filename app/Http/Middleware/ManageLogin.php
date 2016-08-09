<?php

namespace App\Http\Middleware;

use Closure;
use URL;
use Illuminate\Support\Facades\Session;

class ManageLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Session::get('admin.id', false)) {
            return $next($request);
        } else{
            return redirect(URL::route('manage.login.index'))->with(['msg'=>'请先登录']);
        }
    }
}
