<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
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
        $confirm = $request->input('confirm', false); // Check if time-out confirmation is required

        // Check if the RFID number exists in the database
        $user = User::where('rfid_number', $rfidNumber)->first();

        if (!$user) {
            return response()->json(['error' => 'User with RFID number not found'], 404);
        }

        // Check if the user has a schedule for the current time
        // Implement your logic to find the schedule based on time and day
        $schedule = Schedule::where('user_id', $user->id)
                            ->where('days', 'like', '%' . now()->format('D') . '%')
                            ->whereTime('start_time', '<=', now())
                            ->whereTime('end_time', '>=', now())
                            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for the user at this time'], 404);
        }

        // Check if there is an existing attendance record for the user and schedule
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('schedule_id', $schedule->id)
                                        ->first();

        if ($existingAttendance) {
            if (!$confirm) {
                return response()->json(['error' => 'Time-out confirmation required'], 400);
            } else {
                // Record time-out confirmation
                $existingAttendance->time_out = now();
                $existingAttendance->status = 'PRESENT'; // Update status to 'PRESENT'
                $existingAttendance->save();

                // Calculate the percentage of attendance based on the total working hours for the schedule and the time spent in the office by the user for the day of the schedule
                $totalWorkingHours = $schedule->end_time->diffInMinutes($schedule->start_time) / 60;
                $timeInOffice = $existingAttendance->time_in->diffInMinutes($existingAttendance->time_out) / 60;

                // Ensure the percentage doesn't exceed 100%
                $percentage = min(($timeInOffice / $totalWorkingHours) * 100, 100);

                $existingAttendance->percentage = $percentage;
                $existingAttendance->save();

                return response()->json(['message' => 'Attendance time-out confirmed successfully']);
            }
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

        // Check if attendance was successfully recorded
        if (!$attendance->exists) {
            return response()->json(['error' => 'Failed to record attendance'], 500);
        }

        return response()->json(['message' => 'Attendance recorded successfully']);
    }
}
