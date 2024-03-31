<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAttendance;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Laboratory;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function recordAttendance(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'rfid_number' => 'required|string',
            'laboratory_id' => 'required|exists:laboratories,id',
            'action' => 'required|in:entrance,exit',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Extract input data
        $rfidNumber = $request->input('rfid_number');
        $laboratoryId = $request->input('laboratory_id');
        $action = $request->input('action');

        // Find user by RFID number
        $user = User::where('rfid_number', $rfidNumber)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Find the laboratory
        $laboratory = Laboratory::find($laboratoryId);

        if (!$laboratory) {
            return response()->json(['error' => 'Laboratory not found'], 404);
        }

        // Find schedule for the user at current time
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
            return $this->handleEntrance($user, $laboratory, $schedule);
        }

        // Handle exit event
        if ($action === 'exit') {
            return $this->handleExit($user);
        }

        return response()->json(['error' => 'Invalid action'], 400);
    }
   
    private function handleEntrance($user, $laboratory, $schedule)
    {
        // Record new attendance
        Attendance::create([
            'user_id' => $user->id,
            'laboratory_id' => $laboratory->id,
            'subject_id' => $schedule->subject_id,
            'schedule_id' => $schedule->id,
            'time_in' => now(),
            'status' => 'PRESENT',
        ]);

        return response()->json(['message' => 'Attendance recorded successfully']);
    }


    private function handleExit($user)
    {
        // Find the latest attendance record for the user
        $latestAttendance = Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestAttendance) {
            return response()->json(['error' => 'User has not entered'], 400);
        }

        // If the latest attendance has already been marked out, return an error
        if ($latestAttendance->time_out !== null) {
            return response()->json(['error' => 'User has already exited'], 400);
        }

        // Update the latest attendance record with the exit time
        $latestAttendance->update(['time_out' => now()]);

        return response()->json(['message' => 'Exit recorded successfully']);
    }

    // Sum the total in and out of the user of the schedule of their subject of the current day
}
