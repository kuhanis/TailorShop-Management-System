<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicOrderAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access to the public order page without affecting authentication
        return $next($request);
    }
} 