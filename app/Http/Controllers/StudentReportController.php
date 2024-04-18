<?php




namespace App\Http\Controllers;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reports\PdfReport;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Section;




class StudentReportController extends Controller
{
    public function index(){
       
        $pdf = new PdfReport('L', 'mm', 'A4');
        // $pdf->AddPage();
        $pdf->AliasNbPages();
       
        $header = ['ID', 'Name', 'Username', 'Email', 'Section', 'College', 'Department'];


        $data = $this->prepareJsonData();
        $this->loadData($header, $data, $pdf);


        $pdf->Output();
        exit;
    }


    // function prepareJsonData(){
    //     $users = User::where('role', 'student')->with(['college', 'department', 'section'])->get()->toJson();
    //     $arr = json_decode($users);


    //     foreach($arr as $user){
    //         $jsonData = json_encode($user);


    //         $colleges = DB::table('colleges')
    //         ->join('departments', 'college_id', '=', 'departments.college_id')
    //         ->where('departments.college_id', $user->id)
    //         ->get()
    //         ->toJson();
    //         $temp = json_decode($colleges);


    //         $sections = DB::table('sections')
    //         ->join('sections')
    //         ->where($user->id)
    //         ->get()
    //         ->toJson();
    //         $temp = json_decode($sections);
    //     }


    //     return($arr);
    // }


    function prepareJsonData() {
        $users = User::where('role', 'student')->with(['college', 'department', 'section'])->get();
   
        foreach ($users as $user) {
            // Fetch colleges related to the user's department
            $colleges = College::join('departments', 'colleges.id', '=', 'departments.college_id')
                ->where('departments.id', $user->department->id)
                ->get(['colleges.collegeName']);
   
            // Fetch sections related to the user
            $sections = Section::where('id', $user->section->id)
                ->get(['sectionCode']);
   
            // Assign colleges and sections to the user object
            $user->colleges = $colleges;
            $user->sections = $sections;
        }
   
        return $users;
    }
   
   
   
    private function loadData($header, $data, $pdf){


        $pdf->AddPage();


        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 15, 'LIST OF STUDENTS', 0, 1, 'C');


        // Colors, line width and bold font
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B', 10);
        // Header
        $w = array(5, 50, 25, 60, 20, 60, 60);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = false;


        foreach($data as $user){
            $pdf->Cell($w[0],6,$user->id,'LR',0,'L',$fill);
            $pdf->Cell($w[1],6,$user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name,'LR',0,'L',$fill);
            $pdf->Cell($w[2],6,$user->username,'LR',0,'L',$fill);
            $pdf->Cell($w[3],6,$user->email,'LR',0,'L',$fill);
            $pdf->Cell($w[4],6,$user->section->sectionCode,'LR',0,'L',$fill);
            $pdf->Cell($w[5],6,$user->college->collegeName,'LR',0,'L',$fill);
            $pdf->Cell($w[6],6,$user->department->departmentName,'LR',0,'L',$fill);
            $pdf->Ln();
        }




        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
    }
}
