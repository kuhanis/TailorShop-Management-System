<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInactiveUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check last activity
            if ($request->session()->has('last_activity')) {
                $lastActivity = $request->session()->get('last_activity');
                $inactiveTime = time() - $lastActivity;
                
                // Logout if inactive for 30 minutes
                if ($inactiveTime > 1800) {
                    Auth::logout();
                    $request->session()->invalidate();
                    return redirect()->route('login')
                        ->with('error', 'Session expired due to inactivity. Please login again.');
                }
            }
            
            // Update last activity timestamp
            $request->session()->put('last_activity', time());
        }
        
        return $next($request);
    }
} 