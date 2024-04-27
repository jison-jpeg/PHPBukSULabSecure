<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager extends Controller
{
    function login()
    {
        return view('pages.login');
    }

    function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // dd($user.attributes);
            session(['user' => $user]);
            // Redirect users based on their role
            if ($user->role === 'instructor') {
                return redirect('/attendance');
            } elseif ($user->role === 'student') {
                return redirect('/attendance');
            } else {
                return redirect()->intended('dashboard');
            }
        } else {
            return redirect(route('login'))->with("error", "Invalid username or password!");
        }
    }

    function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
