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
            'sectionCode' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
            'subjectDescription' => 'nullable',
        ]);

        // Check if a subject with the given subject code already exists
        $existingSubject = Subject::where('subjectCode', $request->subjectCode)->first();

        if ($existingSubject) {
            return redirect(route('subjects'))->with("error", "Subject with this subject code already exists.");
        }

        // If no existing subject found, proceed to create a new one
        $subject = Subject::create([
            'subjectName' => $request->subjectName,
            'subjectCode' => $request->subjectCode,
            'sectionCode' => $request->sectionCode,
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
}
