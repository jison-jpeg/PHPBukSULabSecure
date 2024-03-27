<?php

namespace App\Http\Controllers;

use App\Models\Logs;
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
    function viewLogsByUser($id)
    {
        $logs = Logs::with('user')->where('user_id', $id)->get();
        return view('pages.logs', compact('logs'));
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
