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
use App\Imports\ScheduleImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class ScheduleController extends Controller
{
    // GET SCHEDULES
    function viewSchedules()
    {
        $date = Carbon::today()->format('Y-m-d');
        $user = Auth::user(); // Get the authenticated user
        $role = $user->role;

        if ($role === 'admin' || $role === 'college-dean' || $role === 'chairperson') {
            $schedules = Schedule::all();
        } elseif ($role === 'instructor') {
            $instructor_id = $user->id;
            $schedules = Schedule::where('user_id', $instructor_id)->get();
        } elseif ($role === 'student') {
            $section_id = $user->section_id;
            $schedules = Schedule::where('section_id', $section_id)->get();
        }

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

        // Check for conflicts in the specified time range for each day
        foreach ($request->days as $day) {
            $conflictingSchedule = Schedule::where('days', 'like', "%$day%")
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                })
                ->exists();

            if ($conflictingSchedule) {
                return redirect(route('schedules'))->with("error", "There is a conflicting schedule for an instructor on $day.");
            }
        }

        // Check if the schedule has already assigned an instructor to the subject in the section
        $existingAssignment = Schedule::where('subject_id', $request->subject_id)
            ->where('section_id', $request->section_id)
            ->exists();

        if ($existingAssignment) {
            return redirect(route('schedules'))->with("error", "The subject in this section already has an assigned instructor.");
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

        // Check for conflicts in the specified time range for each day
        foreach ($request->days as $day) {
            $conflictingSchedule = Schedule::where('days', 'like', "%$day%")
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                })
                ->where('id', '!=', $id)
                ->exists();

            if ($conflictingSchedule) {
                return redirect(route('schedules'))->with("error", "There is a conflicting schedule for an instructor on $day.");
            }
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
            ->where('id', '!=', $id)
            ->exists();

        if ($existingSchedule) {
            return redirect(route('schedules'))->with("error", "The same schedule already exists.");
        }

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

    // VIEW SCHEDULES BY USE
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

    // IMPORT SCHEDULES
    public function importSchedule(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ]
        ]);

        $file = $request->file('file');

        // Import data from Excel file
        try {
            Excel::import(new ScheduleImport, $file);
            return redirect()->back()->with('success', 'Schedule imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing schedule: ' . $e->getMessage());
        }
    }
}
