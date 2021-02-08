<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckAdmin
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
        if(Auth::check() && (Auth::user()->admin !== 'Y' || Auth::user()->status !== 'Y')) {
            Auth::logout();
            return redirect('/');
        } elseif(!(Auth::check())) {
            return redirect('/');
        }
        return $next($request);
    }
}
