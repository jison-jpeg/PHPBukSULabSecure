<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Laboratory;

class ScheduleReportController extends Controller
{
    public function index(){
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['College', 'Department', 'Instructor', 'Subject Code', 'Subject Name', 'Section Code', 'Room', 'Days', 'Start Time', 'End Time'];

        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);

        $pdf->Output();
        exit;
    }

    function prepareJsonData() {
        $users = User::with(['college', 'department', 'section'])
            ->join('schedules', 'users.id', '=', 'schedules.user_id')
            ->join('subjects', 'schedules.subject_id', '=', 'subjects.id')
            ->join('sections', 'schedules.section_id', '=', 'sections.id')
            ->join('laboratories', 'schedules.laboratory_id', '=', 'laboratories.id')
            ->select('users.*', 'subjects.subjectCode', 'subjects.subjectName', 'sections.sectionCode', 'laboratories.roomNumber', 'schedules.days', 'schedules.start_time', 'schedules.end_time')
            ->get();
       
        foreach ($users as $user) {
            // Fetch colleges related to the user's department
            $colleges = College::join('departments', 'colleges.id', '=', 'departments.college_id')
                ->where('departments.id', $user->department->id)
                ->get(['colleges.collegeName']);
       
            // Assign colleges and sections to the user object
            $user->colleges = $colleges;
        }
       
        return $users;
    }
    
    private function loadData($header, $data, $pdf){
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'LIST OF SCHEDULES', 0, 1, 'C');

        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 8);
        // Header
        $header = ['College', 'Department', 'Instructor', 'Subject Code', 'Subject Name', 'Section Code', 'Room', 'Days', 'Start Time', 'End Time'];
        $w = array(35, 35, 35, 25, 30, 25, 20, 30, 20, 20);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;

        foreach ($data as $user) {
            $pdf->CellFitScale($w[0],6,$user->colleges->first()->collegeName ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[1],6,$user->department->departmentName ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[2],6,$user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name, 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[3],6,$user->subjectCode ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[4],6,$user->subjectName ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[5],6,$user->sectionCode ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[6],6, 'COMLAB '. $user->roomNumber ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[7],6,$user->days ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[8],6,$user->start_time ?? '', 'LR', 0, 'L', $fill);
            $pdf->CellFitScale($w[9],6,$user->end_time ?? '', 'LR', 0, 'L', $fill);
            $pdf->Ln();
        }
              
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
