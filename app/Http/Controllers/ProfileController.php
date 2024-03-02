<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // GET PROFILE PAGE
    public function viewProfile()
    {
        $user = Auth::user(); // Fetch the authenticated user
        return view('pages.profile', compact('user'));
    }

    // UPDATE PROFILE
    function profilePut(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'nullable|max:255|min:2',
            'middle_name' => 'nullable|max:255|min:2',
            'last_name' => 'nullable|max:255|min:2',
            'username' => 'nullable',
            'phone' => 'nullable',
            'birthdate' => 'nullable',
            'password' => 'nullable',
        ]);

        // Find the authenticated user
        $user = User::find(Auth::id());

        // Update the user's profile with the data from the request
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'password' => $user->password,
        ]);

        // Check if the user was successfully updated
        if (!$user) {
            return redirect(route('profile'))->with("error", "Error updating profile. Please try again.");
        } else {
            // If successful, redirect back to the profile page with a success message
            return redirect(route('profile'))->with([
                "success" => "Profile updated successfully",
                "user" => $user,
            ]);
        }
    }

    // UPDATE PASSWORD
    function passwordPut(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
    
        // Find the authenticated user
        $user = User::find(Auth::id());
    
        // Check if the current password matches the one in the database
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect(route('profile'))->with("error", "Current password is incorrect. Please try again.");
        }
    
        // Update the user's password with the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        // Check if the user's password was successfully updated
        if (!$user) {
            return redirect(route('profile'))->with("error", "Error updating password. Please try again.");
        } else {
            // If successful, redirect back to the profile page with a success message
            return redirect(route('profile'))->with("success", "Password updated successfully");
        }
    
    }
}
