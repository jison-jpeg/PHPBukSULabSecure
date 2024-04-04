<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use App\Models\Logs;
use Carbon\Carbon;

class LaboratoriesController extends Controller
{
    // GET LABORATORIES
    function viewLaboratories()
    {
        $laboratories = Laboratory::all();
        // Sort laboratories by room number (assuming roomNumber holds the name)
        $laboratories = Laboratory::orderByRaw('CAST(roomNumber AS UNSIGNED)')->paginate(10);


        // Fetch recent logs for each laboratory
        foreach ($laboratories as $lab) {
            // Check if occupancy status is "On-Going"
            if ($lab->occupancyStatus == "On-Going") {
                // Fetch the current user instead of the recent log
                $recentLog  = Logs::where('laboratory_id', $lab->id)
                    ->where('action', 'IN')
                    ->latest()
                    ->first();

                if ($recentLog) {
                    $lab->recentUser = optional($recentLog->user)->getFullName();

                    // Calculate time elapsed
                    $timeAccessed = Carbon::parse($recentLog->date_time)->setTimezone('Asia/Manila');
                    $elapsedTime = $timeAccessed->diffForHumans(null, false, true, 1);
                    $lab->recentTime = $elapsedTime;
                } else {
                    $lab->recentUser = "N/A"; // No recent log found
                    $lab->recentTime = "N/A";
                }

                $lab->label = "Current";
            } else {
                // Fetch the recent log
                $recentOutLog = Logs::where('laboratory_id', $lab->id)
                    ->where('action', 'OUT')
                    ->latest()
                    ->first();

                if ($recentOutLog) {
                    $lab->recentUser = optional($recentOutLog->user)->getFullName();

                    // Calculate time elapsed
                    $timeAccessed = Carbon::parse($recentOutLog->date_time)->setTimezone('Asia/Manila');
                    $elapsedTime = $timeAccessed->diffForHumans(null, false, true, 1);
                    $lab->recentTime = $elapsedTime;
                } else {
                    $lab->recentUser = "N/A"; // No recent log found
                    $lab->recentTime = "N/A";
                }

                $lab->label = "Recent";
            }
        }

        return view('pages.laboratory', compact('laboratories'));
    }


    function formatUserName($userName)
    {
        // Truncate long names and add "..." at the end
        return strlen($userName) > 10 ? substr($userName, 0, 10) . "..." : $userName;
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

    // UPDATE LABORATORY
    function laboratoriesPut(Request $request, $id)
    {
        $request->validate([
            'roomNumber' => 'required',
            'building' => 'required',
            'laboratoryType' => 'required',
            'occupancyStatus' => 'nullable',
            'lockStatus' => 'nullable',
        ]);

        $laboratory = Laboratory::find($id);

        $laboratory->roomNumber = $request->roomNumber;
        $laboratory->building = $request->building;
        $laboratory->laboratoryType = $request->laboratoryType;
        $laboratory->occupancyStatus = $request->occupancyStatus;
        $laboratory->lockStatus = $request->lockStatus;

        if ($laboratory->save()) {
            return redirect(route('laboratories'))->with("success", "Laboratory updated successfully");
        } else {
            return redirect(route('laboratories'))->with("error", "Error updating laboratory. Please try again.");
        }
    }
}
