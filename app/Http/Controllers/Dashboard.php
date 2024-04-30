<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Controller
{
    // View Dashboard
    function viewDashboard()
    {

        // Get total users, total students, total instructors, and total laboratories
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalLaboratories = Laboratory::count();

        // Query to get total unique attendances of each instructor for each month
        $attendanceCounts = Attendance::selectRaw('user_id, DATE_FORMAT(created_at, "%b %Y") as month_year, COUNT(*) as total_attendances')
            ->whereHas('user', function ($query) {
                $query->where('role', 'instructor');
            })
            ->whereRaw('created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)') // Select data from the last two months
            ->groupBy('user_id', 'month_year')
            ->orderBy('month_year', 'asc')
            ->get();



        // Prepare data for the chart
        $chartData = [];
        foreach ($attendanceCounts as $attendance) {
            $instructorId = $attendance->user_id;
            $instructor = User::find($instructorId);
            $name = $instructor->full_name;
            $monthYear = $attendance->month_year;
            $totalAttendances = $attendance->total_attendances;

            if (!isset($chartData[$instructorId])) {
                $chartData[$instructorId] = [
                    'name' => $name,
                    'data' => []
                ];
            }

            $chartData[$instructorId]['data'][$monthYear] = (int)$totalAttendances;
        }

        // Convert data to array format expected by the frontend
        $formattedChartData = [];
        foreach ($chartData as $instructorId => $data) {
            $formattedChartData[] = [
                'name' => $data['name'],
                'data' => $data['data']
            ];
        }


        // Get recent logs
        $logs = Logs::orderBy('created_at', 'desc')->take(10)->get();

        // Format time difference for logs
        foreach ($logs as $log) {
            $log->formatted_time_diff = Carbon::parse($log->created_at)->diffForHumans();
        }


        $logs = Logs::orderBy('created_at', 'desc')->take(10)->get();

        // Iterate through logs and calculate the time difference
        foreach ($logs as $log) {
            $timeDiff = $log->created_at->diff(Carbon::now());

            // Select appropriate time unit based on the time difference
            if ($timeDiff->y > 0) {
                $formattedTimeDiff = $timeDiff->y . ' ' . ($timeDiff->y == 1 ? 'yr' : 'yrs');
            } elseif ($timeDiff->m > 0) {
                $formattedTimeDiff = $timeDiff->m . ' ' . ($timeDiff->m == 1 ? 'mon' : 'mos');
            } elseif ($timeDiff->days > 0) {
                $formattedTimeDiff = $timeDiff->days . ' ' . ($timeDiff->days == 1 ? 'day' : 'days');
            } elseif ($timeDiff->h > 0) {
                $formattedTimeDiff = $timeDiff->h . ' ' . ($timeDiff->h == 1 ? 'hr' : 'hrs');
            } elseif ($timeDiff->i > 0) {
                $formattedTimeDiff = $timeDiff->i . ' ' . ($timeDiff->i == 1 ? 'min' : 'mins');
            } else {
                $formattedTimeDiff = $timeDiff->s . ' ' . ($timeDiff->s == 1 ? 'sec' : 'secs');
            }

            $log->formatted_time_diff = $formattedTimeDiff;
        }

        return view('pages.dashboard', compact('logs', 'totalUsers', 'totalInstructors', 'totalLaboratories', 'totalStudents', 'formattedChartData'));
    }
}
