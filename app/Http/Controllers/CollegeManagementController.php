<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Http\Request;

class CollegeManagementController extends Controller
{
    // GET COLLEGES
    function viewColleges()
    {
        $colleges = College::all();
        return view('pages.collegemngt', compact('colleges'));
    }

    // GET SUBJECTS AND DEPARTMENTS
    function viewSubjectsAndDepartments()
    {
        $colleges = College::all();
        $departments = Department::all();
        return view('pages.collegemngt', compact('colleges', 'departments'));
    }

    // CREATE COLLEGES
    function collegesPost(Request $request)
    {
        $request->validate([
            'collegeName' => 'required',
            'collegeDescription' => 'nullable',
        ]);

        // Check if a college with the given college name already exists
        $existingCollege = College::where('collegeName', $request->collegeName)->first();

        if ($existingCollege) {
            return redirect(route('colleges'))->with("error", "College with this college name already exists.");
        }

        // If no existing college found, proceed to create a new one
        $college = College::create([
            'collegeName' => $request->collegeName,
            'collegeDescription' => $request->collegeDescription ?? 'N/A',
        ]);

        if (!$college) {
            return redirect(route('colleges'))->with("error", "Error creating college. Please try again.");
        } else {
            return redirect(route('colleges'))->with("success", "College created successfully");
        }
    }

    // CREATE DEPARTMENTS
    function departmentsPost(Request $request)
    {
        $request->validate([
            'college_id' => 'required',
            'departmentName' => 'required',
            'departmentDescription' => 'nullable',
        ]);

        // Check if a department with the given department name already exists
        $existingDepartment = Department::where('departmentName', $request->departmentName)->first();

        if ($existingDepartment) {
            return redirect(route('colleges'))->with("error", "Department with this department name already exists.");
        }

        // If no existing department found, proceed to create a new one
        $department = Department::create([
            'college_id' => $request->college_id,
            'departmentName' => $request->departmentName,
            'departmentDescription' => $request->departmentDescription ?? 'N/A',
        ]);

        if (!$department) {
            return redirect(route('colleges'))->with("error", "Error creating department. Please try again.");
        } else {
            return redirect(route('colleges'))->with("success", "Department created successfully");
        }
    }
}

