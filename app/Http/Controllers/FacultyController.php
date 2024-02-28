<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    // GET FACULTIES
    public function viewFaculties()
    {
        $colleges = College::all();
        $departments = Department::all();

        // Retrieve only users with the role of "instructor"
        $users = User::where('role', 'instructor')->with(['college', 'department'])->get();

        return view('pages.user', compact('users', 'colleges', 'departments'));
    }

    // CREATE FACULTIES
    public function facultiesPost(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        // Extract username from email (remove everything after "@")
        $username = explode('@', $request->email)[0];

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $username,
            'role' => $request->role ?? 'instructor', // Set default role to "instructor"
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
            'password' => Hash::make(Str::random(10)), // Generate random password
        ]);

        if (!$user) {
            return redirect(route('faculties'))->with("error", "Error creating faculty. Please try again.");
        } else {
            // Send an email to the faculty with their credentials
            return redirect(route('faculties'))->with("success", "Faculty created successfully");
        }
    }

}

