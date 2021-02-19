<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberStatus
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
        if (isset($request->user()->status) && $request->user()->status == 'N') {
            return redirect('report')->withErrors('該帳號已被停權');
        }

        if(Auth::check() && (Auth::user()->admin == 'Y' || Auth::user()->status == 'D')) {
            Auth::logout();
            return redirect('/');
        }

        return $next($request);
    }
}
