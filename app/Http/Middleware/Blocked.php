<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Blocked
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 4) {
            if ($request->is('blocked') || $request->is('logout') || $request->is('blocked/appeal') || $request->is('user/{{ Auth::user()->user_id}}/delete')) {
                return $next($request);
            }
            return redirect('/blocked');
        }

        return $next($request);
    }
}
