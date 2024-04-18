<?php




namespace App\Http\Controllers\Reports;




use Codedge\Fpdf\Fpdf\Fpdf;




class PdfReport extends FPDF
{
    // Page header
    function Header()  {


        // Watermark
        // $this->SetFont('Arial', 'B', 50);
        // $this->SetTextColor(255,192,203);
        // $this->RotatedText(80,180,'W a t e r m a r k D e m o',45);


        // Logo
        $this->Image('buksu_logo.png',100,5,15);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',12);
        // Title
        $this->Cell(0,0,'Bukidnon State University',0,1,'C');
        $this->Ln(5);
        $this->Cell(0,0,'Malaybalay City, Bukidnon',0,1,'C');
        $this->Ln(15);
    }


    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Electronically Generated Report. Generated on: '.date('m/d/Y h:i:sa'),0,0,'L');
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    }


    // function RotatedText($x, $y, $txt, $angle){
    //     //Text rotated around its origin
    //     $this->Rotate($angle,$x,$y);
    //     $this->Text($x,$y,$txt);
    //     $this->Rotate(0);
    // }


    // var $angle=0;


    // function Rotate($angle,$x=-1,$y=-1){
    //     if($x==-1)
    //         $x=$this->x;
    //     if($y==-1)
    //         $y=$this->y;
    //     if($this->angle!=0)
    //         $this->_out('Q');
    //     $this->angle=$angle;
    //     if($angle!=0)
    //     {
    //         $angle*=M_PI/180;
    //         $c=cos($angle);
    //         $s=sin($angle);
    //         $cx=$x*$this->k;
    //         $cy=($this->h-$y)*$this->k;
    //         $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    //     }
    // }


    // function _endpage(){
    //     if($this->angle!=0)
    //     {
    //         $this->angle=0;
    //         $this->_out('Q');
    //     }
    //     parent::_endpage();
    // }
}
