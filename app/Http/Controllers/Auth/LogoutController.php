<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function index(Request $request)
    {
        // Log the logout
        if (Auth::check()) {
            \Log::info('User logged out', [
                'user' => Auth::user()->username,
                'ip' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->view('auth.logout-redirect');
    }
}
