<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if (!Auth::user()->is_email_verified) {
        //     // auth()->logout();
        //     // return redirect()->route('login')->with('message', 'akun tidak di verifikasi, silahkan klik aktifasi, dan cek email anda');
        // } else
        if (Auth::user()->is_email_verified) {
            return $next($request);
        }
        return redirect('/login')->with('message', 'akun tidak di verifikasi, silahkan klik aktifasi, dan cek email anda');
    }
}
