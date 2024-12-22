<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        $title ="login";
        return view('auth.login',compact('title'));
    }

    public function login(Request $request){
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
        ]);
        
        $credentials = $request->only('username', 'password');
        $remember = $request->remember;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $request->session()->put('user_ip', $request->ip());
            $request->session()->put('last_activity', time());
            $request->session()->put('login_time', time());
            
            // Log login attempt
            \Log::info('Successful login', [
                'user' => Auth::user()->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Check for first time login
            if (Auth::user()->first_login) {
                return redirect()->route('first.time.password');
            }

            return redirect()->route('dashboard');
        }

        // Log failed attempt
        \Log::warning('Failed login attempt', [
            'username' => $request->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('loginError', 'Login Failed!');
    }
}
