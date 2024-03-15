<?php

namespace App\Http\Controllers;

use App\Mail\CredentialsMail;
use App\Models\User;
use App\Models\College;
use App\Models\Department;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    //GET USERS
    function viewUsers()
    {
        $users = User::all();
        $colleges = College::all();
        $departments = Department::all();
        $users = User::with(['college', 'department'])->get();

        return view('pages.user', compact('users', 'colleges', 'departments'));
    }

    //GET ARCHIVED USERS
    function viewArchivedUsers()
    {
        $users = User::onlyTrashed()->get();
        return view('archivedUsers', compact('users'));
    }

    //GET EMPLOYEES
    function viewEmployees()
    {
        $users = User::all();
        return view('employees', compact('users'));
    }


    //CREATE USERS
    function usersPost(Request $request)
    {
        $request->validate([
            'rfid_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'role' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        $plainPassword = Str::random(10);

        $user = User::create([
            'rfid_number' => $request->rfid_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'birthdate' => $request->birthdate,
            'username' => $request->username,
            'password' => Hash::make($plainPassword)
        ]);

        if (!$user) {
            return redirect(route('users'))->with("error", "Invalid username or password!");
        } else {
            //Send email with user data
            Mail::to($request->email)->send(new CredentialsMail($user, $plainPassword));
            return redirect(route('users'))->with("success", "User added successfully!");
        }
    }
    

    //UPDATE USERS
    function usersPut(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'nullable|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phonenumber' => 'nullable|string',
            'role' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'username' => 'nullable|string|unique:users,username,' . $id,
            'password' => 'nullable|string', // We can add more validations
        ]);

        // Remove fields with null values from the validated data
        $validatedData = array_filter($validatedData, function ($value) {
            return !is_null($value);
        });

        // Hash password if provided
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        $user->update($validatedData);
        return redirect(route('users'))->with("success", "User updated successfully!");
    }

    //SOFT DELETE USERS
    function usersDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Restore the user
        $user->delete();

        return redirect(route('users'))->with("success", "User restored  successfully!");
    }

    //RESTORE USERS
    function usersRestore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Restore the user
        $user->restore();

        return back()->with("success", "User restored successfully!");
    }



    //GET ADMINS
    function get_admins()
    {
        $admins = User::where('position', 'tenant admin')->get();
        return response()->json($admins);
    }
}
