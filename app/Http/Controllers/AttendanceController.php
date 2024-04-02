<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
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
    
        // Calculate the percentage of attendance and determine status for each subject
        foreach ($uniqueAttendances as $attendance) {
            $schedule = Schedule::where('user_id', $attendance->user_id)
                ->where('subject_id', $attendance->subject_id)
                ->where('days', 'like', '%' . Carbon::parse($attendance->date)->format('D') . '%')
                ->first();
    
            if ($schedule) {
                $totalScheduledMinutes = Carbon::parse($schedule->start_time)->diffInMinutes($schedule->end_time);
                $totalAttendedMinutes = Carbon::parse($attendance->time_attended)->format('i') + Carbon::parse($attendance->time_attended)->format('H') * 60;
                $attendance->percentage = number_format(($totalAttendedMinutes / $totalScheduledMinutes) * 100, 2);
    
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

            // Find Schedule for the User at Current Time
            $schedule = Schedule::where('user_id', $user->id)
                ->where('days', 'like', '%' . now()->format('D') . '%')
                ->whereTime('start_time', '<=', now())
                ->whereTime('end_time', '>=', now())
                ->first();
            
            if (!$schedule) {
                return response()->json(['error' => 'No schedule found for the user at this time'], 404);
            }

            // Record New Attendance
            Attendance::create([
                'user_id' => $user->id,
                'laboratory_id' => $laboratory->id,
                'subject_id' => $schedule->subject_id,
                'schedule_id' => $schedule->id,
                'time_in' => now(),
                'status' => 'PRESENT',
            ]);

            return response()->json(['message' => 'Attendance recorded successfully']);
        } elseif ($action === 'exit') {
            if (!$currentAttendance) {
                return response()->json(['error' => 'User is not inside the laboratory'], 400);
            }

            // Update the Current Attendance Record with the Exit Time
            $currentAttendance->update(['time_out' => now()]);

            return response()->json(['message' => 'Exit recorded successfully']);
        } else {
            return response()->json(['error' => 'Invalid action'], 400);
        }
    }

    // Sum the Total In and Out of the User of the Schedule of Their Subject of the Current Day
}