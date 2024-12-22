<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // Regenerate session ID periodically
        if (!$request->session()->has('last_session_refresh')) {
            $request->session()->put('last_session_refresh', time());
        } elseif (time() - $request->session()->get('last_session_refresh') > 300) { // 5 minutes
            $request->session()->regenerate();
            $request->session()->put('last_session_refresh', time());
        }

        // Check if user's IP has changed
        if ($request->session()->has('user_ip') && 
            $request->session()->get('user_ip') !== $request->ip()) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->with('error', 'Session expired due to security concern. Please login again.');
        }

        // Set/Update user's IP in session
        $request->session()->put('user_ip', $request->ip());

        // Set security headers
        $response = $next($request);
        if (method_exists($response, 'header')) {
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->header('Content-Security-Policy', "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:");
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $response;
    }
} 