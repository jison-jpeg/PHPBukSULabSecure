<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Laboratory;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // GET SCHEDULES
    function viewSchedules()
    {
        $schedules = Schedule::all();
        $departments = Department::all();
        $colleges = College::all();
        $laboratories = Laboratory::all();
        $subjects = Subject::all();
        $users = User::all();
        $instructors = User::where('role', 'instructor')->get();
        return view('pages.schedule', compact('schedules', 'departments', 'colleges', 'laboratories', 'subjects', 'instructors', 'users'));
    }

    // CREATE SCHEDULES
function createSchedule(Request $request)
{
    $request->validate([
        'college_id' => 'required|exists:colleges,id',
        'department_id' => 'required|exists:departments,id',
        'subject_id' => 'required|exists:subjects,id',
        'user_id' => 'required|exists:users,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'days' => 'required|array|min:1',
        'sectionCode' => 'required|string',
    ]);

    $schedule = new Schedule();
    $schedule->college_id = $request->college_id;
    $schedule->department_id = $request->department_id;
    $schedule->subject_id = $request->subject_id;
    $schedule->user_id = $request->user_id;
    $schedule->laboratory_id = $request->laboratory_id;
    $schedule->start_time = $request->start_time;
    $schedule->end_time = $request->end_time;
    $schedule->days = implode(',', $request->days);
    $schedule->sectionCode = $request->sectionCode;

    $schedule->save();

    return redirect()->route('schedules')->with('success', 'Schedule created successfully.');
}

}
