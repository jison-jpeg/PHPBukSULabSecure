<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;

class Dashboard extends Controller
{
    
    function viewDashboard(){

        $logs = Logs::orderBy('created_at', 'desc')->take(10)->get();
        
        return view('pages.dashboard', compact('logs'));
    }
}
