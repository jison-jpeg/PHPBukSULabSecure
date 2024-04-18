<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;

class LogsController extends Controller
{

    // GET LOGS
    function viewLogs()
    {
        $logs = Logs::with('user')->get();
        return view('pages.logs', compact('logs'));
    }

    // GET LOGS BY USER
    function logsByUser($userId)
    {
        $logs = Logs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);

        // Load user associated with the logs
        $user = User::find($userId);

        return view('pages.logs', compact('logs', 'user'));
    }

    // GET LOGS BY LABORATORY
    function viewLogsByLaboratory($id)
    {
        $logs = Logs::with('user')->where('laboratory_id', $id)->get();
        return view('pages.logs', compact('logs'));
    }

    // GET LOGS BY DATE
    function viewLogsByDate($date)
    {
        $logs = Logs::with('user')->where('date_time', 'like', '%' . $date . '%')->get();
        return view('pages.logs', compact('logs'));
    }

    // GET LOGS BY ACTION
    function viewLogsByAction($action)
    {
        $logs = Logs::with('user')->where('action', 'like', '%' . $action . '%')->get();
        return view('pages.logs', compact('logs'));
    }


    

}
