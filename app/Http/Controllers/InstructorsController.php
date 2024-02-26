<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class facultiesController extends Controller
{
    // GET faculties
    public function viewfaculties()
    {
        $faculties = User::where('role', 'instructor')->get();
        return view('pages.instructor', compact('faculties'));
    }

    // CREATE faculties

}

