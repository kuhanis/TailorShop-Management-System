<?php

namespace App\Http\Middleware;

use Closure;

class CheckStaffRole
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->role === 'staff') {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
