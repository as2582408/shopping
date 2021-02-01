<?php

namespace App\Http\Middleware;

use Closure;

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
        if ($request->user()->status == 'N') {
            return redirect('mycenter')->withErrors('該帳號已被停權');
        }

        return $next($request);
    }
}
