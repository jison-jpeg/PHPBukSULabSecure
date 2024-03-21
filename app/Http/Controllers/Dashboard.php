<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use Carbon\Carbon;

class Dashboard extends Controller
{
    
    function viewDashboard(){

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
        
        return view('pages.dashboard', compact('logs'));
    }
}
