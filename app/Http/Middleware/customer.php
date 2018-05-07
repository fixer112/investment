<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class customer
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
        if (Auth::user()->role != 'cus') {

            return redirect('/'.Auth::user()->role);
            
        }

        return $next($request);
    }
}
