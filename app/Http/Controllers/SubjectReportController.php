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
       
        $header = ['ID', 'Subject', 'Subject Code', 'Description'];


        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);


        $pdf->Output();
        exit;
    }




    function prepareJsonData() {
        // Fetch subjects related to users
        $subjects = Subject::get();
   
        // Optionally, you can modify the structure of $subjects if needed before returning it
   
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
        $w = array(5, 80, 40, 150);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;


        foreach($data as $subject){
            $pdf->Cell($w[0],6,$subject->id,'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,$subject->subjectName,'LR',0,'L',$fill);
            $pdf->Cell($w[2],6,$subject->subjectCode,'LR',0,'L',$fill);
            $pdf->Cell($w[3], 6, isset($subject->subjectDescription) ? $subject->subjectDescription : '', 'LR', 0, 'L', $fill);
            $pdf->Ln();
        }




        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
