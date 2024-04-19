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

    // OLD
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    // NEW
    //Cell with horizontal scaling if text is too wide
    // function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true) {
    //     // Get string width
    //     $str_width = $this->GetStringWidth($txt);

    //     // Calculate ratio to fit cell
    //     if ($w == 0) {
    //         $w = $this->w - $this->rMargin - $this->x;
    //     }

    //     if ($str_width != 0) {
    //         $ratio = ($w - $this->cMargin * 2) / $str_width;
    //     } else {
    //         // Handle the case where $str_width is zero
    //         $ratio = 1; // or any default value
    //     }

    //     $fit = ($ratio < 1 || ($ratio > 1 && $force));
    //     if ($fit)
    //     {
    //         if ($scale)
    //         {
    //             // Calculate horizontal scaling
    //             $horiz_scale = $ratio * 100.0;
    //             // Set horizontal scaling
    //             $this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
    //         }
    //         else
    //         {
    //             // Calculate character spacing in points
    //             $char_space = ($w - $this->cMargin * 2 - $str_width) / max(strlen($txt) - 1, 1) * $this->k;
    //             // Set character spacing
    //             $this->_out(sprintf('BT %.2F Tc ET', $char_space));
    //         }
    //         // Override user alignment (since text will fill up cell)
    //         $align = '';
    //     }

    //     // Pass on to Cell method
    //     $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

    //     // Reset character spacing/horizontal scaling
    //     if ($fit)
    //         $this->_out('BT ' . ($scale ? '100 Tz' : '0 Tc') . ' ET');
    // }


    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }
}
