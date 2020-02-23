<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
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
        if (Auth::check() and Auth::user()->status !== 'enabled') {
            Auth::logout();
            return redirect('/');
        }

        if (!$request->is('onboarding') and Auth::check() and Auth::user()->password == '') {
            return redirect('/onboarding');
        }

        return $next($request);
    }
}
