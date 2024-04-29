<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\College;
use App\Models\Department;
use App\Models\Section;
use App\Models\Laboratory;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // GET SCHEDULES
    function viewSchedules()
    {
        $date = Carbon::today()->format('Y-m-d');
        $schedules = Schedule::all();
        $departments = Department::all();
        $colleges = College::all();
        $sections = Section::all();
        $laboratories = Laboratory::all();
        $subjects = Subject::all();
        $users = User::all();
        $instructors = User::where('role', 'instructor')->get();

        // View the status of the laboratory if it is available, occupied, or locked

        return view('pages.schedule', compact('schedules', 'departments', 'colleges', 'sections', 'laboratories', 'subjects', 'instructors', 'users', 'date'));
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

        // Check for conflicting schedules
        $conflictingSchedules = Schedule::where('user_id', $request->user_id)
            ->where(function ($query) use ($request) {
                foreach ($request->days as $day) {
                    $query->where('days', 'like', '%' . $day . '%')
                        ->where(function ($query) use ($request) {
                            $query->where(function ($query) use ($request) {
                                $query->where('start_time', '>=', $request->start_time)
                                    ->where('start_time', '<', $request->end_time);
                            })
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('end_time', '>', $request->start_time)
                                        ->where('end_time', '<=', $request->end_time);
                                });
                        });
                }
            })
            ->exists();

        if ($conflictingSchedules) {
            return redirect(route('schedules'))->with("error", "There is a scheduling conflict for the selected time and day.");
        }

        // Check if the same schedule already exists
        $existingSchedule = Schedule::where('college_id', $request->college_id)
            ->where('department_id', $request->department_id)
            ->where('subject_id', $request->subject_id)
            ->where('section_id', $request->section_id)
            ->where('user_id', $request->user_id)
            ->where('laboratory_id', $request->laboratory_id)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->where('days', implode(',', $request->days))
            ->exists();

        if ($existingSchedule) {
            return redirect(route('schedules'))->with("error", "The same schedule already exists.");
        }

        // Check if the subject has the same instructor
        $existingInstructor = Schedule::where('subject_id', $request->subject_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($existingInstructor) {
            return redirect(route('schedules'))->with("error", "The instructor is already assigned to the subject.");
        }


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


    // VIEW SCHEDULES BY SECTION
    function viewSectionSchedules($section_id)
    {
        $schedules = Schedule::where('section_id', $section_id)->get();
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
