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

    //CREATE SCHEDULES
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
    
        // Check if schedule already exists for the laboratory on the selected day(s)
        $existingSchedules = Schedule::where('laboratory_id', $request->laboratory_id)
            ->whereIn('days', $request->days)
            ->get();
    
        // Check for overlapping time slots
        foreach ($existingSchedules as $existingSchedule) {
            if ($request->start_time < $existingSchedule->end_time && $request->end_time > $existingSchedule->start_time) {
                return redirect(route('schedules'))->with("error", "Schedule overlaps with existing schedule.");
            }
        }
    
        $schedule = Schedule::create([
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'subject_id' => $request->subject_id,
            'user_id' => $request->user_id,
            'laboratory_id' => $request->laboratory_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => implode(',', $request->days),
            'sectionCode' => $request->sectionCode,
        ]);
    
        if (!$schedule) {
            return redirect(route('schedules'))->with("error", "Error creating schedule. Please try again.");
        } else {
            return redirect(route('schedules'))->with("success", "Schedule created successfully");
        }
    }
    

}
