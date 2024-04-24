<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
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
        $sections = Section::all();

        // Retrieve only users with the role of "student"
        $users = User::where('role', 'student')
            ->with(['college', 'department', 'section'])
            ->get();

        return view('pages.user', compact('users', 'colleges', 'departments', 'sections'));
    }

    // VIEW STUDENT USER ASSOCIATED WITH AN INSTRUCTOR USER BASED ON SCHEDULE SECTION ID
function viewStudentsByInstructor($id)
{
    $colleges = College::all();
    $departments = Department::all();
    $sections = Section::all();

    // Retrieve the section ID of the instructor's schedule subject
    $instructorSchedule = Schedule::where('user_id', $id)->first();

    if (!$instructorSchedule) {
        // Handle case when no schedule is found for the instructor
        return redirect(route('students'))->with("error", "No schedule found for the instructor.");
    }

    $instructorSectionId = $instructorSchedule->section_id;

    // Retrieve only students with the same section ID as the instructor's schedule subject
    $users = User::where('role', 'student')
        ->where('section_id', $instructorSectionId)
        ->with(['college', 'department', 'section'])
        ->get();

    return view('pages.user', compact('users', 'colleges', 'departments', 'sections'));
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
            'section_id' => 'required',
        ]);

        // Extract username from email (remove everything after "@")
        $username = explode('@', $request->email)[0];

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $username,
            'role' => $request->role ?? 'student', // Set default role to "student"
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'section_id' => $request->section_id,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
            'password' => Hash::make(Str::random(10)), // Generate random password
        ]);

        if (!$user) {
            return redirect(route('students'))->with("error", "Error creating student. Please try again.");
        } else {
            // Send an email to the student with their credentials
            
            return redirect(route('students'))->with("success", "Student created successfully");
        }
    }

    // UPDATE STUDENTS
    function studentsPut(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'college_id' => 'required',
            'department_id' => 'required',
            'section_id' => 'required',
        ]);

        $user = User::find($request->id);

        if (!$user) {
            return redirect(route('students'))->with("error", "Student not found.");
        }

        // Extract username from email (remove everything after "@")
        $username = explode('@', $request->email)[0];

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $username;
        $user->college_id = $request->college_id;
        $user->department_id = $request->department_id;
        $user->section_id = $request->section_id;
        $user->birthdate = $request->birthdate;
        $user->phone = $request->phone;

        if ($user->save()) {
            return redirect(route('students'))->with("success", "Student updated successfully");
        } else {
            return redirect(route('students'))->with("error", "Error updating student. Please try again.");
        }
    }

    // DELETE STUDENTS
    function studentsDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect(route('students'))->with("error", "Student not found.");
        }

        if ($user->delete()) {
            return redirect(route('students'))->with("success", "Student deleted successfully");
        } else {
            return redirect(route('students'))->with("error", "Error deleting student. Please try again.");
        }
    }
}
