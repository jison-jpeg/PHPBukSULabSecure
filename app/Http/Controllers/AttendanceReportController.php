<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Laboratory;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends Controller {

    protected $attendanceController;

    public function __construct(AttendanceController $attendanceController)
    {
        $this->attendanceController = $attendanceController;
    }

    // ALL ATTENDANCE REPORT
    public function index(){
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['#', 'Name', 'Room', 'Subject', 'Time In', 'Time Out', 'Date', 'Duration', 'Percentage', 'Status'];

        $data = $this->prepareAllJsonData();
        $this->loadAllData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }

    function prepareAllJsonData() {
        // Get the authenticated user
        $user = Auth::user();
    
        // Fetch all attendance records
        $attendances = Attendance::with(['user', 'subject', 'section', 'laboratory'])
            ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
            ->leftJoin('users', 'users.id', '=', 'attendances.user_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('sections', 'sections.id', '=', 'schedules.section_id')
            ->leftJoin('laboratories', 'laboratories.id', '=', 'schedules.laboratory_id')
            ->select(
                'users.last_name',
                'users.first_name',
                'users.middle_name',
                'subjects.subjectName',
                'laboratories.roomNumber',
                'attendances.time_in',
                'attendances.time_out',
                'attendances.created_at', // Adjusted for the date column
            )
            ->get();
    
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

        // Calculate the total duration, percentage, and status for each attendance record
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

            $attendance->last_name = $attendance->user->last_name;
            $attendance->first_name = $attendance->user->first_name;
            $attendance->middle_name = $attendance->user->middle_name;
            $attendance->subjectName = $attendance->subject->subjectName;
            $attendance->roomNumber = $attendance->laboratory->roomNumber;
            $attendance->time_in = $attendance->time_in;
            $attendance->time_out = $attendance->time_out;
            $attendance->created_at = Carbon::parse($attendance->date)->toDateString();
        }
    
        // Return the processed attendance records
        return $uniqueAttendances;
    }

    private function loadAllData($header, $data, $pdf){
        $pdf->AddPage();
    
        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'USER ATTENDANCE', 0, 1, 'C');
    
        // Colors, line width, and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 9);
    
        // Header
        $w = array(5, 55, 25, 55, 25, 25, 20, 15, 25, 25);
        for($i=0; $i<count($header); $i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
    
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
    
        // Data
        $fill = false;
        $rowNumber = 1; // Initialize the row number counter
    
        foreach ($data as $attendance) {
            $pdf->Cell($w[0], 6, $rowNumber, 'LR', 0, 'L', $fill); // Output the row number
            $pdf->Cell($w[1], 6, $attendance->last_name . ', ' . $attendance->first_name . ' ' . $attendance->middle_name, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[2], 6, 'COMLAB ' . ($attendance->roomNumber ?? ''), 'LR', 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $attendance->subjectName ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[4], 6, $attendance->time_in ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[5], 6, $attendance->time_out ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[6], 6, $attendance->created_at->format('Y-m-d') ?? '', 'LR', 0, 'L', $fill);

            // Calculate total duration
            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
            $timeOut = \Carbon\Carbon::parse($attendance->time_out);
            $totalDuration = $timeOut->diff($timeIn)->format('%H:%I:%S');
            $pdf->Cell($w[7], 6, $totalDuration, 'LR', 0, 'L', $fill);
    
            $pdf->Cell($w[8], 6, $attendance->percentage ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[9], 6, $attendance->status ?? '', 'LR', 0, 'L', $fill);
            $pdf->Ln();
    
            $rowNumber++;
        }
    
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }


    // STUDENT ATTENDANCE REPORT
    public function viewStudentAttendance($sectionId, $subjectId){
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['#', 'Name', 'Room', 'Subject', 'Time In', 'Time Out', 'Date', 'Duration', 'Percentage', 'Status'];

        $data = $this->prepareJsonData($sectionId, $subjectId);
        $this->loadData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }

    function prepareJsonData($subjectId) {
        // Assuming you have a specific logic to fetch students or you may need to adjust this query
        $students = User::where('role', 'student')->get();

        // Retrieve the schedule associated with the subject ID
        $schedule = Schedule::where('subject_id', $subjectId)->first();

        // Check if the schedule exists
        if (!$schedule) {
            // Handle case where schedule does not exist
            return [];
        }
    
        // Your existing code
        $attendances = Attendance::with(['user', 'subject', 'section', 'laboratory'])
        ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
        ->leftJoin('users', 'users.id', '=', 'attendances.user_id')
        ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
        ->leftJoin('sections', 'sections.id', '=', 'schedules.section_id')
        ->leftJoin('laboratories', 'laboratories.id', '=', 'schedules.laboratory_id')
        ->where('users.role', 'student') // Filter out non-student users
        ->where('schedules.subject_id', $subjectId) // Filter by subject ID
        ->select(
            'users.last_name',
            'users.first_name',
            'users.middle_name',
            'subjects.subjectName',
            'laboratories.roomNumber',
            'attendances.time_in',
            'attendances.time_out',
            'attendances.created_at', // Adjusted for the date column
        )
        ->get();

    
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

            $attendance->last_name = $attendance->user->last_name;
            $attendance->first_name = $attendance->user->first_name;
            $attendance->middle_name = $attendance->user->middle_name;
            $attendance->subjectName = $attendance->subject->subjectName;
            $attendance->roomNumber = $attendance->laboratory->roomNumber;
            $attendance->time_in = $attendance->time_in;
            $attendance->time_out = $attendance->time_out;
        }
        
        return $uniqueAttendances; // This line should be inside the method block
    }

    private function loadData($header, $data, $pdf){
        $pdf->AddPage();
    
        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'STUDENT ATTENDANCE', 0, 1, 'C');
    
        // Colors, line width, and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 9);
    
        // Header
        $w = array(5, 55, 25, 55, 25, 25, 20, 15, 25, 25);
        for($i=0; $i<count($header); $i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
    
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
    
        // Data
        $fill = false;
        $rowNumber = 1; // Initialize the row number counter
    
        foreach ($data as $attendance) {
            $pdf->Cell($w[0], 6, $rowNumber, 'LR', 0, 'L', $fill); // Output the row number
            $pdf->Cell($w[1], 6, $attendance->last_name . ', ' . $attendance->first_name . ' ' . $attendance->middle_name, 'LR', 0, 'L', $fill);
            $pdf->Cell($w[2], 6, 'COMLAB ' . ($attendance->roomNumber ?? ''), 'LR', 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $attendance->subjectName ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[4], 6, $attendance->time_in ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[5], 6, $attendance->time_out ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[6], 6, $attendance->created_at->format('Y-m-d') ?? '', 'LR', 0, 'L', $fill);
    
            // Calculate total duration
            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
            $timeOut = \Carbon\Carbon::parse($attendance->time_out);
            $totalDuration = $timeOut->diff($timeIn)->format('%H:%I:%S');
            $pdf->Cell($w[7], 6, $totalDuration, 'LR', 0, 'L', $fill);
    
            $pdf->Cell($w[8], 6, $attendance->percentage ?? '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[9], 6, $attendance->status ?? '', 'LR', 0, 'L', $fill);
            $pdf->Ln();
    
            $rowNumber++;
        }
    
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }

    public function showAttendance($scheduleId)
{
    $schedule = Schedule::find($scheduleId);
    $sectionId = $schedule->section_id;
    $subjectId = $schedule->subject_id;

    return view('attendance', compact('sectionId', 'subjectId'));
}

}
