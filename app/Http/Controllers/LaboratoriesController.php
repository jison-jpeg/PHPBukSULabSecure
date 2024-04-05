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
        $laboratories = Laboratory::orderByRaw('CAST(roomNumber AS UNSIGNED)')->paginate(10);

        foreach ($laboratories as $lab) {
            if ($lab->occupancyStatus == "On-Going") {
                $recentLog  = Logs::where('laboratory_id', $lab->id)
                    ->where('action', 'IN')
                    ->latest()
                    ->first();

                if ($recentLog) {
                    $lab->recentUser = optional($recentLog->user)->getFullName();
                    $elapsedTime = Carbon::parse($recentLog->created_at)->setTimezone('Asia/Manila')->diffForHumans(null, false, true, 1);
                    $lab->recentTime = $elapsedTime;
                } else {
                    $lab->recentUser = "N/A";
                    $lab->recentTime = "N/A";
                }

                $lab->label = "Current";
            } else {
                $recentOutLog = Logs::where('laboratory_id', $lab->id)
                    ->where('action', 'OUT')
                    ->latest()
                    ->first();

                if ($recentOutLog) {
                    $lab->recentUser = optional($recentOutLog->user)->getFullName();
                    $elapsedTime = Carbon::parse($recentOutLog->created_at)->setTimezone('Asia/Manila')->diffForHumans(null, false, true, 1);
                    $lab->recentTime = $elapsedTime;
                } else {
                    $lab->recentUser = "N/A";
                    $lab->recentTime = "N/A";
                }

                $lab->label = "Recent";
            }
        }

        return view('pages.laboratory', compact('laboratories'));
    }

    // CREATE LABORATORIES
    function laboratoriesPost(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required',
            'building' => 'required',
            'laboratoryType' => 'required',
        ]);

        $existingLaboratory = Laboratory::where('roomNumber', $request->roomNumber)->first();

        if ($existingLaboratory) {
            return redirect(route('laboratories'))->with("error", "Laboratory with this room number already exists.");
        }

        $laboratory = Laboratory::create([
            'roomNumber' => $request->roomNumber,
            'building' => $request->building,
            'laboratoryType' => $request->laboratoryType,
            'occupancyStatus' => $request->occupancyStatus ?? 'Available',
            'lockStatus' => $request->lockStatus ?? false,
        ]);

        if (!$laboratory) {
            return redirect(route('laboratories'))->with("error", "Error creating laboratory. Please try again.");
        } else {
            // Log the creation of the laboratory
            Logs::create([
                'description' => 'Laboratory created: ' . $laboratory->roomNumber,
                'action' => 'CREATE',
            ]);
            
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

        if (!$laboratory) {
            return redirect(route('laboratories'))->with("error", "Laboratory not found.");
        }

        // Log the changes before updating
        Logs::create([
            'description' => 'Laboratory updated: ' . $laboratory->roomNumber,
            'action' => 'UPDATE',
        ]);

        $laboratory->roomNumber = $request->roomNumber;
        $laboratory->building = $request->building;
        $laboratory->laboratoryType = $request->laboratoryType;
        $laboratory->occupancyStatus = $request->occupancyStatus ?? 'Available';
        $laboratory->lockStatus = $request->lockStatus ?? false;

        if ($laboratory->save()) {
            return redirect(route('laboratories'))->with("success", "Laboratory updated successfully");
        } else {
            return redirect(route('laboratories'))->with("error", "Error updating laboratory. Please try again.");
        }
    }

    // DELETE LABORATORY
    function laboratoriesDelete($id)
    {
        $laboratory = Laboratory::find($id);

        if ($laboratory->delete()) {
            // Log the deletion of the laboratory
            Logs::create([
                'description' => 'Laboratory deleted: ' . $laboratory->roomNumber,
                'action' => 'DELETE',
            ]);
            
            return redirect(route('laboratories'))->with("success", "Laboratory deleted successfully");
        } else {
            return redirect(route('laboratories'))->with("error", "Error deleting laboratory. Please try again.");
        }
    }
}
