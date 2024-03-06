<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        $profileUserId = $request->route('user_id'); 

        if ($user->role === 2 || ($user->role === 1 && $user->user_id === $profileUserId)) {
            return $next($request);
        }
        
        if ($user->role === 1) {
            return $next($request);
        }

        abort(403); 
    }
}
