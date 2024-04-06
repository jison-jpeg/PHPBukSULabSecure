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
// View Attendance
public function viewAttendance()
{
    // Fetch unique attendance records for each subject on the same date
    $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date, TIMEDIFF(MAX(time_out), MIN(time_in)) as time_attended')
        ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
        ->get();

    // Format time in and time out to 12-hour format
    foreach ($uniqueAttendances as $attendance) {
        $attendance->time_in = date('h:i A', strtotime($attendance->time_in));
        // Check if time_out is null
        if ($attendance->time_out) {
            $attendance->time_out = date('h:i A', strtotime($attendance->time_out));
        } else {
            $attendance->time_out = ''; // Set to empty string or any default value
        }
    }

    // Calculate the percentage of attendance and determine status for each subject
    foreach ($uniqueAttendances as $attendance) {
        $schedule = Schedule::where('user_id', $attendance->user_id)
            ->where('subject_id', $attendance->subject_id)
            ->where('days', 'like', '%' . Carbon::parse($attendance->date)->format('D') . '%')
            ->first();

        if ($schedule) {
            $totalScheduledMinutes = Carbon::parse($schedule->start_time)->diffInMinutes($schedule->end_time);
            $totalAttendedMinutes = Carbon::parse($attendance->time_attended)->format('i') + Carbon::parse($attendance->time_attended)->format('H') * 60;

            // Calculate attendance percentage and ensure it does not exceed 100%
            $percentage = min(100, number_format(($totalAttendedMinutes / $totalScheduledMinutes) * 100, 2));
            $attendance->percentage = $percentage;

            // Determine the status based on arrival time
            $scheduledStartTime = Carbon::parse($schedule->start_time);
            $arrivalTime = Carbon::parse($attendance->time_in);

            if ($arrivalTime->lte($scheduledStartTime)) {
                $attendance->status = 'Present';
            } elseif ($arrivalTime->diffInMinutes($scheduledStartTime) <= 15) {
                $attendance->status = 'Late';
            } else {
                $attendance->status = 'Very Late';
            }
        } else {
            $attendance->percentage = 0; // If no schedule found, set percentage to 0
            $attendance->status = 'Absent'; // If no schedule found, user is absent
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
    
            // Find matching schedule based on user's section code
            $matchingSchedule = Schedule::where('sectionCode', $user->section_code)
                ->where('days', 'like', '%' . now()->format('D') . '%') // Assuming now()->format('D') returns the day abbreviation like "Sat"
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
            // Check if the user has an active attendance record
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
    
    
    // Sum the Total In and Out of the User of the Schedule of Their Subject of the Current Day
}
