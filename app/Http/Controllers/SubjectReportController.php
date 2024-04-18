<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\Subject;

class SubjectReportController extends Controller
{
    public function index(){       
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['#', 'Subject', 'Subject Code', 'Description'];

        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }

    function prepareJsonData() {
        $subjects = Subject::get();
   
        return $subjects;
    }
   
    private function loadData($header, $data, $pdf){
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'LIST OF SUBJECTS', 0, 1, 'C');

        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 10);
        // Header
        $w = array(5, 80, 30, 160);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;
        $rowNumber = 1; // Initialize the row number counter

        foreach ($data as $subject) {
            $pdf->CellFitScale($w[0],6, $rowNumber, 'LR', 0, 'L', $fill); // Output the row number
            $pdf->CellFitScale($w[1],6, $subject->subjectName, 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[2],6, $subject->subjectCode, 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[3],6, isset($subject->subjectDescription) ? $subject->subjectDescription : '', 'LR', 0, 'L', $fill);
            $pdf->Ln();
            
            $rowNumber++; // Increment the row number for the next iteration
        }

        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
