<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // View Attendance
    public function viewAttendance()
    {
        // Fetch unique attendance records for each subject on the same date
        $uniqueAttendances = Attendance::selectRaw('MIN(id) as id, user_id, laboratory_id, subject_id, MIN(time_in) as time_in, MAX(time_out) as time_out, DATE(created_at) as date, TIMEDIFF(MAX(time_out), MIN(time_in)) as time_attended')
            ->groupBy('user_id', 'laboratory_id', 'subject_id', 'date')
            ->get();

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
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'laboratory_id' => $laboratory->id,
                'subject_id' => $schedule->subject_id,
                'schedule_id' => $schedule->id,
                'time_in' => now(),
                'status' => 'PRESENT',
            ]);

            // Calculate time attended
            $totalScheduledMinutes = Carbon::parse($schedule->start_time)->diffInMinutes($schedule->end_time);
            $attendance->time_attended = Carbon::parse(now())->diffInMinutes($schedule->start_time);
            $attendance->percentage = ($attendance->time_attended / $totalScheduledMinutes) * 100;
            $attendance->save();

            return response()->json(['message' => 'Attendance recorded successfully']);
        } elseif ($action === 'exit') {
            if (!$currentAttendance) {
                return response()->json(['error' => 'User is not inside the laboratory'], 400);
            }

            // Update the Current Attendance Record with the Exit Time
            $currentAttendance->update(['time_out' => now()]);

            // Calculate time attended
            $totalScheduledMinutes = Carbon::parse($currentAttendance->schedule->start_time)->diffInMinutes($currentAttendance->schedule->end_time);
            $currentAttendance->time_attended += Carbon::parse(now())->diffInMinutes($currentAttendance->time_in);
            $currentAttendance->percentage = ($currentAttendance->time_attended / $totalScheduledMinutes) * 100;
            $currentAttendance->save();

            return response()->json(['message' => 'Exit recorded successfully']);
        } else {
            return response()->json(['error' => 'Invalid action'], 400);
        }
    }
}
