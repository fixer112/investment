<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class mentor
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
        if (empty(Auth::user()->mentor)) {

            if ($request->ajax() || $request->expectsJson()) {
            
               $data['errors'] = ['fail' => ['Only Mentors allowed']];
               //Auth::logout();
                return \Response::json($data, 403);
            }

            return redirect('/'.Auth::user()->role);
            
        }
        return $next($request);
    }
}
