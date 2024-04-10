<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // GET SUBJECTS
    function viewSubjects()
    {
        $subjects = Subject::all();
        $colleges = College::all();
        $departments = Department::all();
        return view('pages.subject', compact('subjects' , 'colleges', 'departments'));
    }
    
    // CREATE SUBJECTS
    function subjectsPost(Request $request)
    {
        $request->validate([
            'subjectName' => 'required',
            'subjectCode' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
            'subjectDescription' => 'nullable',
        ]);

        // Check if a subject with the given subject name already exists
        $existingSubject = Subject::where('subjectName', $request->subjectName)->first();

        if ($existingSubject) {
            return redirect(route('subjects'))->with("error", "Subject with this subject name already exists.");
        }

        // If no existing subject found, proceed to create a new one
        $subject = Subject::create([
            'subjectName' => $request->subjectName,
            'subjectCode' => $request->subjectCode,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'subjectDescription' => $request->subjectDescription ?? 'N/A',
        ]);

        if (!$subject) {
            return redirect(route('subjects'))->with("error", "Error creating subject. Please try again.");
        } else {
            return redirect(route('subjects'))->with("success", "Subject created successfully");
        }
    }

    // UPDATE SUBJECT
    function subjectsPut(Request $request, $id)
    {
        $request->validate([
            'subjectName' => 'required',
            'subjectCode' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
            'subjectDescription' => 'nullable',
        ]);

        $subject = Subject::find($id);

        $subject->subjectName = $request->subjectName;
        $subject->subjectCode = $request->subjectCode;
        $subject->college_id = $request->college_id;
        $subject->department_id = $request->department_id;
        $subject->subjectDescription = $request->subjectDescription;

        if ($subject->save()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin updated an account.ID: $user->id",
            //     'action' => 'Update',
            // ]);
            return redirect(route('subjects'))->with("success", "Subject updated successfully!");
        } else {
            return redirect(route('subjects'))->with("error", "Subject update failed!");
        }
    }

    //DELETE SUBJECT
    function subjectsDelete($id)
    {
        $subject = Subject::find($id);

        if ($subject->delete()) {
            // //Create log
            // Logs::create([
            //     'date_time' => now(),
            //     'user_id' => Auth::id(),
            //     'name' => $user->getFullName(),
            //     'description' => "An admin deleted an account.ID: $user->id",
            //     'action' => 'Delete',
            // ]);
            return redirect(route('subjects'))->with("success", "Subject deleted successfully!");
        } else {
            return redirect(route('subjects'))->with("error", "Subject deletion failed!");
        }
    }
}
