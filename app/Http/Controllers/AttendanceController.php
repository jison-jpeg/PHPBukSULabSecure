<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
use App\Models\Logs;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // View Attendance
    public function viewAttendance()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user is an instructor
        if ($user->role === 'instructor') {
            // Fetch unique attendance records for the authenticated instructor user only
            $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date')
                ->where('user_id', $user->id) // Filter by authenticated user's ID
                ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
                ->get();
        } else {
            // If the authenticated user is not an instructor, display all attendance records
            $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date')
                ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
                ->get();
        }

        // Calculate the total duration spent in the laboratory for each attendance record
        foreach ($uniqueAttendances as $attendance) {
            $totalDuration = CarbonInterval::hours(0); // Initialize total duration as 0 hours
            $logs = Attendance::where('user_id', $attendance->user_id)
                ->where('laboratory_id', $attendance->laboratory_id)
                ->where('subject_id', $attendance->subject_id)
                ->whereDate('created_at', $attendance->date)
                ->get();

            foreach ($logs as $log) {
                // Calculate the duration between time_in and time_out for each log
                $timeIn = Carbon::parse($log->time_in);
                $timeOut = Carbon::parse($log->time_out);
                $duration = $timeOut->diff($timeIn);
                $totalDuration = $totalDuration->add($duration);
            }

            // Format the total duration as HH:MM:SS
            $attendance->total_duration = $totalDuration->cascade()->format('%H:%I:%S');

            // Calculate the percentage of the total duration spent in the laboratory for scheduled subject
            $schedule = Schedule::find($logs->first()->schedule_id);
            $totalDurationInSeconds = $totalDuration->totalSeconds;
            $scheduledDurationInSeconds = Carbon::parse($schedule->start_time)->diffInSeconds(Carbon::parse($schedule->end_time));
            $percentage = ($totalDurationInSeconds / $scheduledDurationInSeconds) * 100;
            $attendance->percentage = abs(round($percentage, 2));

            // Set the maximum percentage to 100%
            if ($attendance->percentage > 100) {
                $attendance->percentage = 100;
            }

            // Check user's arrival status
            $scheduleStartTime = Carbon::parse($schedule->start_time);
            $lateTime = $scheduleStartTime->copy()->addMinutes(15);
            $timeIn = Carbon::parse($attendance->time_in);

            if ($timeIn->gt($lateTime)) {
                $attendance->status = 'Late';
            } else {
                $attendance->status = 'Present';
            }

            // Change the status to absent if the percentage is less than 15%
            if ($attendance->percentage < 15) {
                $attendance->status = 'Absent';
            } else {
                // Change the status to incomplete if the percentage is less than 50%
                if ($attendance->percentage < 50) {
                    $attendance->status = 'Incomplete';
                }
            }
        }

        return view('pages.attendance', compact('uniqueAttendances'));
    }

    // VIEW UNIQUE ATTENDANCE OF STUDENT BY SUBJECT IN THE SCHEDULE BASED ON SECTION. IF STUDENT DONT HAVE ATTENDANCE, DISPLAY ABSENT IN THE STATUS
    public function viewStudentAttendance($sectionId, $subjectId)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve students belonging to the specified section
        $students = User::where('role', 'student')
            ->where('section_id', $sectionId)
            ->get();

        // Fetch the schedule based on the subject and section id
        $schedule = Schedule::where('subject_id', $subjectId)
            ->where('section_id', $sectionId)
            ->first();

        // Fetch unique attendance records for the schedule associated with the specified section and subject
        $uniqueAttendances = collect();
        foreach ($students as $student) {
            $attendance = Attendance::where('user_id', $student->id)
                ->where('subject_id', $subjectId)
                ->first();

            // If the attendance record exists, add it to the collection
            if ($attendance) {
                $attendance->date = Carbon::parse($attendance->created_at)->format('Y-m-d'); // Add date to attendance
                $uniqueAttendances->push($attendance);
            } else {
                // If the student is absent, create a dummy attendance record
                $dummyAttendance = new Attendance([
                    'user_id' => $student->id,
                    'laboratory_id' => $schedule->laboratory_id,
                    'subject_id' => $subjectId,
                    'schedule_id' => null,
                    'time_in' => null,
                    'time_out' => null,
                    'date' => null, // Add date to dummy attendance
                ]);
                $uniqueAttendances->push($dummyAttendance);
            }
        }

        // Calculate attendance details for each unique attendance record
        foreach ($uniqueAttendances as $attendance) {

            $studentId = $attendance->user_id;
            // Calculate time in and time out
            $logs = Attendance::where('user_id', $attendance->user_id)
                ->where('laboratory_id', $attendance->laboratory_id)
                ->where('subject_id', $attendance->subject_id)
                ->get();

            // Initialize total duration as 0 hours
            $totalDuration = CarbonInterval::hours(0);

            foreach ($logs as $log) {
                // Calculate the duration between time_in and time_out for each log
                $timeIn = Carbon::parse($log->time_in);
                $timeOut = Carbon::parse($log->time_out);
                $duration = $timeOut->diff($timeIn);
                $totalDuration = $totalDuration->add($duration);
            }

            // Format the total duration as HH:MM:SS
            $attendance->total_duration = $totalDuration->cascade()->format('%H:%I:%S');

            // Calculate the percentage of the total duration spent in the laboratory for scheduled subject
            $totalDurationInSeconds = $totalDuration->totalSeconds;
            $scheduledDurationInSeconds = Carbon::parse($schedule->start_time)->diffInSeconds(Carbon::parse($schedule->end_time));
            $percentage = ($totalDurationInSeconds / $scheduledDurationInSeconds) * 100;
            $attendance->percentage = abs(round($percentage, 2));

            // Set the maximum percentage to 100%
            if ($attendance->percentage > 100) {
                $attendance->percentage = 100;
            }

            // Check user's arrival status
            $scheduleStartTime = Carbon::parse($schedule->start_time);
            $lateTime = $scheduleStartTime->copy()->addMinutes(15);
            $timeIn = Carbon::parse($attendance->time_in);

            if ($timeIn->gt($lateTime)) {
                $attendance->status = 'Late';
            } else {
                $attendance->status = 'Present';
            }

            // Change the status to absent if the percentage is less than 15%
            if ($attendance->percentage < 15) {
                $attendance->status = 'Absent';
            } else {
                // Change the status to incomplete if the percentage is less than 50%
                if ($attendance->percentage < 50) {
                    $attendance->status = 'Incomplete';
                }
            }
        }

        return view('pages.attendance', compact('uniqueAttendances'));
    }


    // Record Attendance
    public function recordAttendance(Request $request)
    {
        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'rfid_number' => 'required|string',
            'laboratory_id' => 'required|exists:laboratories,id',
            'action' => 'required|in:entrance,exit',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Extract Input Data
        $rfidNumber = $request->input('rfid_number');
        $laboratoryId = $request->input('laboratory_id');
        $action = $request->input('action');

        // Find User by RFID Number
        $user = User::where('rfid_number', $rfidNumber)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Find the Laboratory
        $laboratory = Laboratory::find($laboratoryId);
        if (!$laboratory) {
            return response()->json(['error' => 'Laboratory not found'], 404);
        }

        // Check if the laboratory is locked
        if ($laboratory->lockStatus) {
            return response()->json(['error' => 'Laboratory is locked by the Administrator'], 400);
        }

        // Check if the user is an instructor
        $isInstructor = $user->role === 'instructor';

        // Check if User is already inside the Laboratory
        $currentAttendance = Attendance::where('user_id', $user->id)
            ->where('laboratory_id', $laboratoryId)
            ->whereNull('time_out')
            ->first();

        if ($action === 'entrance') {
            if ($currentAttendance) {
                return response()->json(['error' => 'User is already inside the laboratory'], 400);
            }

            // If the user is not an instructor, check if instructor is present in the laboratory
            if (!$isInstructor) {
                $instructorAttendance = Attendance::where('user_id', '!=', $user->id)
                    ->where('laboratory_id', $laboratoryId)
                    ->whereNull('time_out')
                    ->whereHas('user', function ($query) {
                        $query->where('role', 'instructor');
                    })
                    ->exists();

                if (!$instructorAttendance) {
                    return response()->json(['error' => 'Instructor is not present in the laboratory'], 400);
                }
            }

            // Find matching schedule based on current time
            $matchingSchedule = Schedule::where('days', 'like', '%' . now()->format('D') . '%') // Assuming now()->format('D') returns the day abbreviation like "Sat"
                ->whereTime('start_time', '<=', now())
                ->whereTime('end_time', '>=', now())
                ->first();

            if (!$matchingSchedule) {
                return response()->json(['error' => 'No matching schedule found for the user at this time'], 404);
            }

            // Record New Attendance
            Attendance::create([
                'user_id' => $user->id,
                'laboratory_id' => $laboratory->id,
                'subject_id' => $matchingSchedule->subject_id,
                'schedule_id' => $matchingSchedule->id,
                'time_in' => now(),
                'status' => 'PRESENT',
            ]);

            // Log the Entrance Action
            Logs::create([
                'user_id' => $user->id,
                'laboratory_id' => $laboratory->id,
                'name' => $user->getFullName(),
                'description' => 'User entered the laboratory ' . $laboratory->roomNumber,
                'action' => 'IN',
            ]);

            // Update the occupancy status of the laboratory if the user's role is not student
            if ($user->role !== 'student') {
                $laboratory->update(['occupancyStatus' => 'On-Going']);
            }

            return response()->json(['message' => 'Welcome, ' . $user->first_name . ' ' . $user->last_name . '!' . "\n" . 'Subject: ' . $matchingSchedule->subject->name . "\n" . 'Attendance has been successfully recorded.']);
        } elseif ($action === 'exit') {
            if (!$currentAttendance) {
                return response()->json(['error' => 'User is not inside the laboratory'], 400);
            }

            // Update the Current Attendance Record with the Exit Time
            $currentAttendance->update(['time_out' => now()]);

            // Log the Exit Action
            Logs::create([
                'user_id' => $user->id,
                'laboratory_id' => $laboratory->id,
                'name' => $user->getFullName(),
                'description' => 'User exited the laboratory ' . $laboratory->roomNumber,
                'action' => 'OUT',
            ]);

            // Update the occupancy status of the laboratory if the user's role is not student
            if ($user->role !== 'student') {
                $laboratory->update(['occupancyStatus' => 'Available']);
            }
            // Response message for attendance exit
            return response()->json(['message' => 'Goodbye, ' . $user->first_name . ' ' . $user->last_name . '!' . "\n" . 'Your exit has been successfully recorded.']);
        } else {
            return response()->json(['error' => 'Invalid action'], 400);
        }
    }
}
