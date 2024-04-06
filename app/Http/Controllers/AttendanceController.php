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
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
// View Attendance
public function viewAttendance()
{
    // Fetch unique attendance records for each subject on the same date
    $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date')
        ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
        ->get();

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
