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

    //UPDATE FACULTIES
    function facultiesPut(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        $user = User::find($id);

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->college_id = $request->college_id;
        $user->department_id = $request->department_id;

        if ($user->save()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin updated an account.ID: $user->id",
            //     'action' => 'Update',
            // ]);
            return redirect(route('faculties'))->with("success", "Faculty updated successfully!");
        } else {
            return redirect(route('faculties'))->with("error", "Faculty update failed!");
        }
    }

    //DELETE USERS
    function facultiesDelete($id)
    {
        $user = User::find($id);

        if ($user->delete()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin deleted an account.ID: $user->id",
            //     'action' => 'Delete',
            // ]);
            return redirect(route('faculties'))->with("success", "Faculty deleted successfully!");
        } else {
            return redirect(route('faculties'))->with("error", "Faculty deletion failed!");
        }
    }
}