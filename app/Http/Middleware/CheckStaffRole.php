<?php

namespace App\Http\Middleware;

use Closure;

class CheckStaffRole
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->staff && auth()->user()->staff->role === 'staff') {
            return redirect()->route('dashboard');
        }
        return $next($request);     
    }
}
