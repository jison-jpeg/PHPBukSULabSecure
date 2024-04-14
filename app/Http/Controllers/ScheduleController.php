<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Section;
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
        $sections = Section::all();
        $laboratories = Laboratory::all();
        $subjects = Subject::all();
        $users = User::all();
        $instructors = User::where('role', 'instructor')->get();
        return view('pages.schedule', compact('schedules', 'departments', 'colleges', 'sections', 'laboratories', 'subjects', 'instructors', 'users'));
    }

    //CREATE SCHEDULES
    function createSchedule(Request $request)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days' => 'required|array',
            'days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        ]);

        $schedule = Schedule::create([
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'user_id' => $request->user_id,
            'laboratory_id' => $request->laboratory_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => implode(',', $request->days),
        ]);

        if (!$schedule) {
            return redirect(route('schedules'))->with("error", "Error creating schedule. Please try again.");
        } else {
            return redirect(route('schedules'))->with("success", "Schedule created successfully");
        }
    }

    // UPDATE SCHEDULES
    function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days' => 'required|array',
            'days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        ]);

        $schedule = Schedule::find($id);
        $schedule->college_id = $request->college_id;
        $schedule->department_id = $request->department_id;
        $schedule->subject_id = $request->subject_id;
        $schedule->section_id = $request->section_id;
        $schedule->user_id = $request->user_id;
        $schedule->laboratory_id = $request->laboratory_id;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->days = implode(',', $request->days);
        $schedule->save();

        if (!$schedule) {
            return redirect(route('schedules'))->with("error", "Error updating schedule. Please try again.");
        } else {
            return redirect(route('schedules'))->with("success", "Schedule updated successfully");
        }
    }

    // DELETE SCHEDULES
    function deleteSchedule($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return redirect(route('schedules'))->with("error", "Schedule not found.");
        }

        $schedule->delete();
        return redirect(route('schedules'))->with("success", "Schedule deleted successfully");
    }

    // VIEW SCHEDULES BY USER
    function viewUserSchedules($id)
    {
        $schedules = Schedule::where('user_id', $id)->get();
        $departments = Department::all();
        $colleges = College::all();
        $sections = Section::all();
        $laboratories = Laboratory::all();
        $subjects = Subject::all();
        $users = User::all();
        $instructors = User::where('role', 'instructor')->get();
        return view('pages.schedule', compact('schedules', 'departments', 'colleges', 'sections', 'laboratories', 'subjects', 'instructors', 'users'));
    }
}
