<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    // GET STUDENTS
    function viewStudents()
    {
        $colleges = College::all();
        $departments = Department::all();

        // Retrieve only users with the role of "student"
        $users = User::where('role', 'student')->with(['college', 'department'])->get();

        return view('pages.user', compact('users', 'colleges', 'departments'));
    }

    // CREATE STUDENTS
    function studentsPost(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        // Extract username from email (remove everything after "@")
        $username = explode('@', $request->email)[0];

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $username,
            'role' => $request->role ?? 'student', // Set default role to "student"
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'password' => Hash::make(Str::random(10)), // Generate random password
        ]);

        if (!$user) {
            return redirect(route('students'))->with("error", "Error creating student. Please try again.");
        } else {
            // Send an email to the student with their credentials
            return redirect(route('students'))->with("success", "Student created successfully");
        }
    }
}
