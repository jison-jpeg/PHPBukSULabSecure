<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
use App\Models\Logs;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // GET FULLNAME
    function getFullName($id)
    {
        $attendance = Attendance::find($id);
        return $attendance->user->getFullName();
    }

    // GET ATTENDANCE
    function viewAttendance()
    {
        $attendances = Attendance::all();
        return view('pages.attendance', compact('attendances'));
    }

    // STORE ATTENDANCE
    public function recordAttendance(Request $request)
    {
        // Checks if the request is coming from the ESP8266
        if (!$request->has('rfid_number') || !$request->has('laboratory_id') || !$request->has('action')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }
        
        // Extract RFID data, laboratory ID, and action (entrance/exit) from the request
        $rfidNumber = $request->input('rfid_number');
        $laboratoryId = $request->input('laboratory_id');
        $action = $request->input('action'); // ESP8266 sends 'action' parameter indicating entrance or exit

        // Check if the RFID number exists in the database
        $user = User::where('rfid_number', $rfidNumber)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }

        // Check if the user has a schedule for the current time
        $schedule = Schedule::where('user_id', $user->id)
            ->where('days', 'like', '%' . now()->format('D') . '%')
            ->whereTime('start_time', '<=', now())
            ->whereTime('end_time', '>=', now())
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for the user at this time'], 404);
        }

// Handle entrance event
if ($action === 'entrance') {
    // Check if there is an existing active attendance record for the user and schedule on the current day
    $existingAttendanceToday = Attendance::where('user_id', $user->id)
        ->where('schedule_id', $schedule->id)
        ->whereNull('time_out') // Only consider active attendances
        ->whereDate('created_at', now()->format('Y-m-d'))
        ->first();

    if ($existingAttendanceToday) {
        // If the user re-enters without completing their attendance, do not create a new record
        // Update time in only for temporary exits
        if ($existingAttendanceToday->status === 'TEMPORARILY_OUT') {
            $existingAttendanceToday->time_in = now();
            $existingAttendanceToday->save();

            // Log the re-entry action
            Logs::create([
                'date_time' => now(),
                'user_id' => $user->id,
                'laboratory_id' => $laboratoryId,
                'name' => $user->getFullName(),
                'description' => 'User re-entered the laboratory: ' . $laboratoryId . ' for subject: ' . $schedule->subject->subjectName,
                'action' => 'IN'
            ]);

            return response()->json(['message' => 'Attendance updated successfully']);
        } else {
            // If the user re-enters after completing their attendance, allow re-entry
            // No need to create a new record
            return response()->json(['message' => 'User already inside the laboratory']);
        }
    }

    // Record attendance with time-in
    $attendance = new Attendance();
    $attendance->user_id = $user->id;
    $attendance->laboratory_id = $laboratoryId;
    $attendance->subject_id = $schedule->subject_id;
    $attendance->schedule_id = $schedule->id;
    $attendance->time_in = now();
    $attendance->status = 'PRESENT';
    $attendance->save();

    // Log the entrance action
    Logs::create([
        'date_time' => now(),
        'user_id' => $user->id,
        'laboratory_id' => $laboratoryId,
        'name' => $user->getFullName(),
        'description' => 'User entered the laboratory: ' . $laboratoryId . ' for subject: ' . $schedule->subject->subjectName,
        'action' => 'IN'
    ]);

    // Update laboratory occupancy status if role is not student
    if ($user->role !== 'student') {
        Laboratory::where('id', $laboratoryId)->update(['occupancyStatus' => 'On-Going']);
    }

    return response()->json(['message' => 'Attendance recorded successfully']);
}


// Handle exit event
else if ($action === 'exit') {
    // Check if there is an existing attendance record for the user and schedule on the current day
    $existingAttendanceToday = Attendance::where('user_id', $user->id)
        ->where('schedule_id', $schedule->id)
        ->whereDate('created_at', now()->format('Y-m-d'))
        ->first();

    // Check if the user has not entered the laboratory
    if (!$existingAttendanceToday) {
        return response()->json(['error' => 'User has not entered the laboratory'], 400);
    }

    // Check if the laboratory for time-in matches the provided laboratory ID
    if ($existingAttendanceToday->laboratory_id != $laboratoryId) {
        return response()->json(['error' => 'You cannot logout to another laboratory'], 400);
    }

    // Introduce a timeout threshold (in minutes)
    $timeoutThreshold = 15; // Adjust this value as needed
    
    // Check if the user's absence duration is within the timeout threshold
    $timeIn = Carbon::parse($existingAttendanceToday->time_in);
    $timeOut = now();
    $absenceDuration = $timeOut->diffInMinutes($timeIn);

    if ($absenceDuration <= $timeoutThreshold) {
        // Log the temporary exit action
        Logs::create([
            'date_time' => $timeOut,
            'user_id' => $user->id,
            'laboratory_id' => $laboratoryId,
            'name' => $user->getFullName(),
            'description' => 'User temporarily exited the laboratory: ' . $laboratoryId . ' for subject: ' . $schedule->subject->subjectName,
            'action' => 'OUT_TEMPORARY'
        ]);

        return response()->json(['message' => 'Temporary exit recorded successfully']);
    } else {
        // If the absence duration exceeds the timeout threshold, consider it as a complete exit
        $existingAttendanceToday->time_out = $timeOut;
        $existingAttendanceToday->save();

        // Log the complete exit action
        Logs::create([
            'date_time' => $timeOut,
            'user_id' => $user->id,
            'laboratory_id' => $laboratoryId,
            'name' => $user->getFullName(),
            'description' => 'User exited the laboratory: ' . $laboratoryId . ' for subject: ' . $schedule->subject->subjectName,
            'action' => 'OUT'
        ]);

        // Update laboratory occupancy status if role is not student
        if ($user->role !== 'student') {
            Laboratory::where('id', $laboratoryId)->update(['occupancyStatus' => 'Available']);
        }

        return response()->json(['message' => 'Complete exit recorded successfully']);
    }
}

        return response()->json(['error' => 'Invalid action'], 400);
    }
}