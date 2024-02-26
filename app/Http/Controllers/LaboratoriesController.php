<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;

class LaboratoriesController extends Controller
{
    // GET LABORATORIES
    function viewLaboratories()
    {
        $laboratories = Laboratory::all();
        return view('pages.laboratory', compact('laboratories'));
    }

    // CREATE LABORATORIES
    function laboratoriesPost(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required',
            'building' => 'required',
            'laboratoryType' => 'required',
            'occupancyStatus' => 'nullable',
            'lockStatus' => 'nullable',
        ]);

        // Check if a laboratory with the given room number already exists
        $existingLaboratory = Laboratory::where('roomNumber', $request->roomNumber)->first();

        if ($existingLaboratory) {
            return redirect(route('laboratories'))->with("error", "Laboratory with this room number already exists.");
        }

        // If no existing laboratory found, proceed to create a new one
        $laboratory = Laboratory::create([
            'roomNumber' => $request->roomNumber,
            'building' => $request->building,
            'laboratoryType' => $request->laboratoryType,
            'occupancyStatus' => $request->occupancyStatus ?? 'Available',
            'lockStatus' => $request->lockStatus ?? 'Locked',
        ]);

        if (!$laboratory) {
            return redirect(route('laboratories'))->with("error", "Error creating laboratory. Please try again.");
        } else {
            return redirect(route('laboratories'))->with("success", "Laboratory created successfully");
        }
    }

}
