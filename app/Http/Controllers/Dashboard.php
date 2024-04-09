<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Controller
{
    // View Dashboard
    function viewDashboard(){

        // Get all total users
        $totalUsers = User::count();

        // Get all total users with role of instructor
        $totalInstructors = User::where('role', 'instructor')->count();

        // Get all total laboratories
        $totalLaboratories = Laboratory::count();

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
        
        return view('pages.dashboard', compact('logs', 'totalUsers', 'totalInstructors', 'totalLaboratories'));
    }
}
