<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class CheckRole
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
        if(auth()->user()->role_id == 1 && auth()->user()->status == 1){
            return $next($request);
        }
        Session::put('error', 'Tài khoản không được cấp quyền');
        return redirect('order/index');
    }
}
