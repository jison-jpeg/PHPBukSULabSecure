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
    // Extract RFID data from the request
    $rfidNumber = $request->input('rfid_number');

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

    // Check if there is an existing attendance record for the user and schedule on the current day
    $existingAttendanceToday = Attendance::where('user_id', $user->id)
        ->where('schedule_id', $schedule->id)
        ->whereDate('created_at', now()->format('Y-m-d'))
        ->first();

    if ($existingAttendanceToday) {
        // If attendance already recorded and has both time in and time out
        if ($existingAttendanceToday->time_in && $existingAttendanceToday->time_out) {
            // Log the Check Out Action
            Logs::create([
                'date_time' => now(),
                'user_id' => $user->id,
                'laboratory_id' => $existingAttendanceToday->laboratory_id,
                'name' => $user->getFullName(),
                'description' => 'A User exited the laboratory: ' . $existingAttendanceToday->laboratory->roomNumber . ' for subject: ' . $existingAttendanceToday->subject->subjectName,
                'action' => 'OUT'
            ]);

            // Update the occupancy status of the laboratory
            $existingAttendanceToday->laboratory->update(['occupancyStatus' => 'Available']);

            return response()->json(['message' => 'Attendance already completed for this subject']);
        }

        // If attendance already recorded but missing time out, update time out
        $existingAttendanceToday->update(['time_out' => now()]);
        return response()->json(['message' => 'Attendance updated successfully']);
    }

    // Get laboratory and subject for the schedule
    $laboratory = Laboratory::find($schedule->laboratory_id);
    $subject = Subject::find($schedule->subject_id);

    // Record attendance with time-in
    $attendance = new Attendance();
    $attendance->user_id = $user->id;
    $attendance->laboratory_id = $laboratory->id;
    $attendance->subject_id = $subject->id;
    $attendance->schedule_id = $schedule->id;
    $attendance->time_in = now();
    $attendance->status = 'PRESENT';
    $attendance->save();

    // Log the Check In Action
    Logs::create([
        'date_time' => now(),
        'user_id' => $user->id,
        'laboratory_id' => $laboratory->id,
        'name' => $user->getFullName(),
        'description' => 'A User entered the laboratory: ' . $laboratory->roomNumber . ' for subject: ' . $subject->subjectName,
        'action' => 'IN'
    ]);

    // Update the occupancy status of the laboratory
    $laboratory->update(['occupancyStatus' => 'On-Going']);

    // Check if attendance was successfully recorded
    if (!$attendance->exists) {
        return response()->json(['error' => 'Failed to record attendance'], 500);
    }

    return response()->json(['message' => 'Attendance recorded successfully']);
}



}
