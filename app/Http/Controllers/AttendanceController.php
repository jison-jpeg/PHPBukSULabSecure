<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //Instructor's Attendance Routes
    function viewAttendance()
    {
        return view('pages.attendance');
    }
}
