<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthManager extends Controller
{
    protected $maxAttempts = 5; // Maximum login attempts
    protected $decayMinutes = 1.5; // Throttle duration in minutes

    public function login()
    {
        return view('pages.login');
    }

    public function loginPost(Request $request)
    {
        // Use RateLimiter to throttle login attempts
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return $this->sendLockoutResponse($request);
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Clear the login attempts cache
            RateLimiter::clear($this->throttleKey($request));

            $user = Auth::user();

            // Check if user is active
            if ($user->status != 'active') {
                Auth::logout();
                return redirect(route('login'))->with("error", "Your account is no longer active. Please contact the administrator.");
            }

            session(['user' => $user]);

            if ($user->role === 'instructor' || $user->role === 'student') {
                return redirect('/attendance');
            } else {
                return redirect()->intended('dashboard');
            }
        } else {
            // Increment login attempts
            RateLimiter::hit($this->throttleKey($request));

            // Handle invalid credentials
            return redirect(route('login'))->with("error", "Invalid username or password!");
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Generate unique throttle key for the login attempts
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('username')) . '|' . $request->ip();
    }

    // Send lockout response
    protected function sendLockoutResponse(Request $request)
    {
        // Return a response indicating that the user is locked out
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        return redirect(route('login'))->with("error", "Too many login attempts. Please try again in {$seconds} seconds.");
    }
}
