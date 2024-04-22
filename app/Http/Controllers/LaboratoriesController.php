<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use App\Models\Logs;
use App\Models\User;
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
                    ->whereHas('user', function ($query) {
                        $query->where('role', '!=', 'student'); // Exclude students
                    })
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
                    ->whereHas('user', function ($query) {
                        $query->where('role', '!=', 'student'); // Exclude students
                    })
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

            // Fetch all logs of the instructor user for this laboratory along with user names
            $instructorLogs = Logs::where('laboratory_id', $lab->id)
                ->whereHas('user', function ($query) {
                    $query->where('role', 'instructor'); // Filter by instructor role
                })
                ->with('user') // Include the user relationship
                ->latest()
                ->get();

            // Modify the description of each log to include the user's name
            $instructorLogs->transform(function ($log) {
                $userName = optional($log->user)->getFullName();
                $log->description = str_replace('User', $userName, $log->description);
                return $log;
            });

            $lab->instructorLogs = $instructorLogs;
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

            // Get user's name
            $user = User::find(auth()->user()->id);

            // Log the creation of the laboratory
            Logs::create([
                'user_id' => auth()->user()->id,
                'laboratory_id' => $laboratory->id,
                'name' => $user->getFullName(),
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

        // Get user's name
        $user = User::find(auth()->user()->id);

        // Log the changes before updating
        Logs::create([
            'user_id' => auth()->user()->id,
            'laboratory_id' => $laboratory->id,
            'name' => $user->getFullName(),
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

            // Get user's name
            $user = User::find(auth()->user()->id);

            // Log the deletion of the laboratory
            Logs::create([
                'user_id' => auth()->user()->id,
                'laboratory_id' => $laboratory->id,
                'name' => $user->getFullName(),
                'description' => 'Laboratory deleted: ' . $laboratory->roomNumber,
                'action' => 'DELETE',
            ]);

            return redirect(route('laboratories'))->with("success", "Laboratory deleted successfully");
        } else {
            return redirect(route('laboratories'))->with("error", "Error deleting laboratory. Please try again.");
        }
    }

    // UPDATE LOCK STATUS
public function updateLockStatus(Request $request, $id)
{
    $request->validate([
        'lockStatus' => 'required|boolean',
    ]);

    $laboratory = Laboratory::find($id);

    if (!$laboratory) {
        return redirect(route('laboratories'))->with("error", "Laboratory not found.");
    }

    if ($laboratory->occupancyStatus == "On-Going") {
        return redirect(route('laboratories'))->with("error", "Cannot lock laboratory with ongoing occupancy.");
    }

    $lockStatus = $request->lockStatus;
    $laboratory->lockStatus = $lockStatus;
    $laboratory->save();

    $message = $lockStatus ? "Laboratory locked successfully." : "Laboratory unlocked successfully.";

    // Create a log entry for the lock status change
    Logs::create([
        'user_id' => auth()->user()->id,
        'laboratory_id' => $laboratory->id,
        'name' => auth()->user()->full_name,
        'description' => $message,
        'action' => 'UPDATE',
    ]);

    return redirect(route('laboratories'))->with("success", $message);
}

    
}
