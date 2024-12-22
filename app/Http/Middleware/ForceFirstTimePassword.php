<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceFirstTimePassword
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->first_login) {
            if (!$request->is('first-time-password*')) {
                return redirect()->route('first.time.password');
            }
        }
        
        return $next($request);
    }
} 