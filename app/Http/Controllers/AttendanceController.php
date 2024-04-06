<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
use App\Models\Logs;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
// View Attendance
public function viewAttendance()
{
    // Fetch unique attendance records for each subject on the same date
    $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date, TIMEDIFF(MAX(time_out), MIN(time_in)) as time_attended')
        ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
        ->get();

    // Set the status of each attendance record based on the time arrival. If user is late in 15 minutes, set the status to LATE
    foreach ($uniqueAttendances as $attendance) {
        $timeIn = Carbon::parse($attendance->time_in);
        $schedule = Schedule::where('subject_id', $attendance->subject_id)
            ->where('days', 'like', '%' . Carbon::parse($attendance->date)->format('D') . '%')
            ->first();

        if ($schedule) {
            $scheduleTime = Carbon::parse($schedule->start_time);
            $lateTime = $scheduleTime->copy()->addMinutes(15);

            if ($timeIn->gt($lateTime)) {
                $attendance->status = 'LATE';
            } else {
                $attendance->status = 'PRESENT';
            }
        } else {
            $attendance->status = 'ABSENT';
        }
    }

    // Calculate the total percent attended for each attendance record in every subject
    foreach ($uniqueAttendances as $attendance) {
        $totalTime = Carbon::parse($attendance->time_attended);
        $totalMinutes = $totalTime->hour * 60 + $totalTime->minute;
        $totalMinutes = $totalMinutes > 0 ? $totalMinutes : 1;

        $schedule = Schedule::where('subject_id', $attendance->subject_id)
            ->where('days', 'like', '%' . Carbon::parse($attendance->date)->format('D') . '%')
            ->first();

        if ($schedule) {
            $totalScheduleTime = Carbon::parse($schedule->end_time)->diffInMinutes(Carbon::parse($schedule->start_time));
            $attendance->percentage = round(($totalMinutes / $totalScheduleTime) * 100, 2);

            // set the maximum percentage to 100
            if ($attendance->percentage > 100) {
                $attendance->percentage = 100;
            }

        } else {
            $attendance->percentage = 0;
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

    // Check if User is already inside the Laboratory
    $currentAttendance = Attendance::where('user_id', $user->id)
        ->where('laboratory_id', $laboratoryId)
        ->whereNull('time_out')
        ->first();

    if ($action === 'entrance') {
        if ($currentAttendance) {
            return response()->json(['error' => 'User is already inside the laboratory'], 400);
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

        return response()->json(['message' => 'Attendance recorded successfully']);
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

        return response()->json(['message' => 'Exit recorded successfully']);
    } else {
        return response()->json(['error' => 'Invalid action'], 400);
    }
}


}
