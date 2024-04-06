<?php

namespace App\Http\Controllers;

use App\Mail\CredentialsMail;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Logs;
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

            //Create log
            Logs::create([
                'date_time' => now(),
                'user_id' => Auth::id(),
                'name' => $user->getFullName(),
                'description' => "An admin created an account.ID: $user->id",
                'action' => 'Create',
            ]);
            return redirect(route('users'))->with("success", "User added successfully!");
        }
    }

    //UPDATE USERS
    function usersPut(Request $request, $id)
    {
        $request->validate([
            'rfid_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'role' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        $user = User::find($id);

        $user->rfid_number = $request->rfid_number;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->college_id = $request->college_id;
        $user->department_id = $request->department_id;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->birthdate = $request->birthdate;
        $user->username = $request->username;

        if ($user->save()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin updated an account.ID: $user->id",
            //     'action' => 'Update',
            // ]);
            return redirect(route('users'))->with("success", "User updated successfully!");
        } else {
            return redirect(route('users'))->with("error", "User update failed!");
        }
    }

    //DELETE USERS
    function usersDelete($id)
    {
        $user = User::find($id);

        // User cannot delete own account
        if ($user->id == Auth::id()) {
            return redirect(route('users'))->with("error", "You cannot delete your own account!");
        }

        if ($user->delete()) {
            //Create log
            Logs::create([
                'date_time' => now(),
                'user_id' => Auth::id(),
                'name' => $user->getFullName(),
                'description' => "An admin deleted an account.ID: $user->id",
                'action' => 'Delete',
            ]);
            return redirect(route('users'))->with("success", "User deleted successfully!");
        } else {
            return redirect(route('users'))->with("error", "User deletion failed!");
        }
    }
}
