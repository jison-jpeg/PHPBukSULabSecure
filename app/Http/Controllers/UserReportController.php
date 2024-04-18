<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\User;
use App\Models\College;
use App\Models\Department;

class UserReportController extends Controller
{
    public function index(){
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['#', 'Name', 'Username', 'Email', 'Role', 'College', 'Department'];

        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }


    function prepareJsonData(){
        $users = User::with(['college', 'department'])->get()->toJson();
        $arr = json_decode($users);

        foreach($arr as $user){
            $jsonData = json_encode($user);

            $colleges = DB::table('colleges')
            ->join('departments', 'college_id', '=', 'departments.college_id')
            ->where('departments.college_id', $user->id)
            ->get()
            ->toJson();
            $temp = json_decode($colleges);
        }

        return($arr);
    }

    private function loadData($header, $data, $pdf){
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'LIST OF USERS', 0, 1, 'C');

        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 10);
        // Header
        $w = array(5, 50, 25, 55, 30, 55, 55);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;
        $rowNumber = 1; // Initialize the row number counter

        foreach($data as $user){
            $pdf->CellFitScale($w[0],6, $rowNumber, 'LR', 0, 'L', $fill); // Output the row number
            $pdf->CellFitScale($w[1],6,$user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name,'LR',0,'L',$fill);
            $pdf->CellFitScale($w[2],6,$user->username,'LR',0,'L',$fill);
            $pdf->CellFitScale($w[3],6,$user->email,'LR',0,'L',$fill);
            $pdf->CellFitScale($w[4],6,$user->role,'LR',0,'L',$fill);
            $pdf->Cell($w[5],6,isset($user->college) ? $user->college->collegeName : '', 'LR', 0, 'L', $fill);
            $pdf->Cell($w[6],6,isset($user->department) ? $user->department->departmentName : '', 'LR', 0, 'L', $fill);
            $pdf->Ln();

            $rowNumber++; // Increment the row number for the next iteration
        }      
    
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
