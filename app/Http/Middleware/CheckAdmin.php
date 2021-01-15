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
        if(Auth::check() && $request->user()->admin !== 'Y') {
            Auth::logout();
            return redirect('admin');
        } elseif(!(Auth::check())) {
            return redirect('admin');
        }
        return $next($request);
    }
}
