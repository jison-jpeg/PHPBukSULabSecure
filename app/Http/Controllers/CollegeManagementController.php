<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Logs;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollegeManagementController extends Controller
{
    // GET COLLEGES
    function viewColleges()
    {
        $colleges = College::all();
        $departments = Department::all();
        $sections = Section::all();
        return view('pages.collegemngt', compact('colleges', 'departments', 'sections'));
    }

    // CREATE COLLEGES
    function createCollege(Request $request)
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

    // UPDATE COLLEGE
    function collegePut(Request $request, $id)
    {
        $request->validate([
            'collegeName' => 'required',
            'collegeDescription' => 'required',
        ]);

        $college = College::find($id);

        $college->collegeName = $request->collegeName;
        $college->collegeDescription = $request->collegeDescription;

        if ($college->save()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin updated an account.ID: $user->id",
            //     'action' => 'Update',
            // ]);
            return redirect(route('colleges'))->with("success", "College updated successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "College update failed!");
        }
    }

    //DELETE COLLEGE
    function collegeDelete($id)
    {
        $college = College::find($id);

        if ($college->delete()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin deleted an account.ID: $user->id",
            //     'action' => 'Delete',
            // ]);
            return redirect(route('colleges'))->with("success", "College deleted successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "College deletion failed!");
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

    // UPDATE DEPARTMENT
    function departmentPut(Request $request, $id)
    {
        $request->validate([
            'departmentName' => 'required',
            'departmentDescription' => 'nullable',
        ]);

        $department = Department::find($id);

        $department->departmentName = $request->departmentName;
        $department->departmentDescription = $request->departmentDescription;

        if ($department->save()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin updated an account.ID: $user->id",
            //     'action' => 'Update',
            // ]);
            return redirect(route('colleges'))->with("success", "Department updated successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "Department update failed!");
        }
    }

    // DELETE COLLEGE
    function departmentDelete($id)
    {
        $department = Department::find($id);

        if ($department->delete()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin deleted an account.ID: $user->id",
            //     'action' => 'Delete',
            // ]);
            return redirect(route('colleges'))->with("success", "Department deleted successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "Department deletion failed!");
        }
    }

    // CREATE SECTIONS
    function sectionsPost(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'sectionCode' => 'required',
            'sectionDescription' => 'nullable',
        ]);

        // Check if a section with the given section name already exists
        $existingSection = Section::where('sectionCode', $request->sectionCode)->first();

        if ($existingSection) {
            return redirect(route('colleges'))->with("error", "Section with this section name already exists.");
        }

        // If no existing section found, proceed to create a new one
        $section = Section::create([
            'department_id' => $request->department_id,
            'sectionCode' => $request->sectionCode,
            'sectionDescription' => $request->sectionDescription ?? 'N/A',
        ]);

        // Get user's name
        $user = User::find(auth()->user()->id);

        //Create log
        Logs::create([
            'date_time' => now(),
            'user_id' => Auth::id(),
            'name' => $user->getFullName(),
            'description' => "Section created: " . $section->sectionCode,
            'action' => 'Create',
        ]);

        if (!$section) {
            return redirect(route('colleges'))->with("error", "Error creating section. Please try again.");
        } else {
            return redirect(route('colleges'))->with("success", "Section created successfully");
        }
    }

    // UPDATE SECTION
    function sectionPut(Request $request, $id)
    {
        $request->validate([
            'sectionCode' => 'required',
            'sectionDescription' => 'nullable',
        ]);

        $section = Section::find($id);

        $section->sectionCode = $request->sectionCode;
        $section->sectionDescription = $request->sectionDescription;

        if ($section->save()) {

            // Get user's name
            $user = User::find(auth()->user()->id);

            //Create log
            Logs::create([
                'date_time' => now(),
                'user_id' => Auth::id(),
                'name' => $user->getFullName(),
                'description' => "Section updated: " . $section->sectionCode,
                'action' => 'Update',
            ]);

            return redirect(route('colleges'))->with("success", "Section updated successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "Section update failed!");
        }
    }

    // DELETE SECTION
    function sectionDelete($id)
    {
        $section = Section::find($id);

        if ($section->delete()) {
            // Get user's name
            $user = User::find(auth()->user()->id);

            //Create log
            Logs::create([
                'date_time' => now(),
                'user_id' => Auth::id(),
                'name' => $user->getFullName(),
                'description' => "Section deleted: " . $section->sectionCode,
                'action' => 'Delete',
            ]);

            return redirect(route('colleges'))->with("success", "Section deleted successfully!");
        } else {
            return redirect(route('colleges'))->with("error", "Section deletion failed!");
        }
    }
}

