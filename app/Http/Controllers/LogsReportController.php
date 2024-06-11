<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\Logs;

class LogsReportController extends Controller
{
    public function index(){
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['Date', 'Time', 'Room', 'User ID', 'Name', 'Description', 'Action'];

        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }


    function prepareJsonData(){
        // Retrieve logs data with necessary relations
        $logs = Logs::with(['user', 'laboratory'])->get();

        // Prepare data array for the report
        $data = [];

        // foreach ($logs as $log) {
        //     // Format date and time
        //     $date = date('Y-m-d', strtotime($log->created_at));
        //     $time = date('H:i:s', strtotime($log->created_at));

        //     $data[] = [
        //         'date' => $date,
        //         'time' => $time,
        //         'room' => $log->laboratory->roomNumber, // Assuming 'room' is the relationship name to the room
        //         'user_id' => $log->user->id,
        //         'name' => $log->user->getFullNameAttribute(), // Assuming 'getFullNameAttribute' is a user attribute
        //         'description' => $log->description,
        //         'action' => $log->action,
        //     ];
        // }

        foreach ($logs as $log) {
            $date = date('Y-m-d', strtotime($log->created_at));
            $time = date('H:i:s', strtotime($log->created_at));
            
            // Check if the laboratory relationship is not null before accessing its properties
            $roomNumber = $log->laboratory ? $log->laboratory->roomNumber : null;
        
            $data[] = [
                'date' => $date,
                'time' => $time,
                'room' => $roomNumber,
                'user_id' => optional($log->user)->id, // Use optional helper to avoid null reference
                'name' => optional($log->user)->getFullNameAttribute(), // Use optional helper to avoid null reference
                'description' => $log->description,
                'action' => $log->action,
            ];
        }

        return $data;
    }

    private function loadData($header, $data, $pdf){
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'AUDIT LOGS REPORT', 0, 1, 'C');

        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 10);
        // Header
        $w = array(30, 30, 20, 20, 60, 90, 25);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;

        foreach($data as $log){
            $pdf->CellFitScale($w[0], 6, $log['date'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[1], 6, $log['time'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[2], 6, $log['room'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[3], 6, $log['user_id'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[4], 6, $log['name'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[5], 6, $log['description'], 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[6], 6, $log['action'], 'LR', 0, 'L', $fill);
            $pdf->Ln();
        }
        
    
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
