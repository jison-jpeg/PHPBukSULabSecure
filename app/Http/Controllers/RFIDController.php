<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RFIDController extends Controller
{

    // GET RFID Data
    function viewRfid()
    {
        return view('pages.rfid', ['uid' => session('uid')]); // Pass the UID to the view
    }
}
