<?php
require('fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        //WELTES LOGO
        $this->Image('../../../images/logo.jpg',10,6,50); // ('FILE', HORZ POSITION, VERTICAL POSITION, SIZE)
        $this->Image('../../../images/USR_ASME3.png',165,6,31); // ('FILE', HORZ POSITION, VERTICAL POSITION, SIZE)
        //LINE BREAK
        $this->Ln(15);
    }
    
    function Footer()
    {
        
    }
}

$pdf = new PDF('P','mm','A4'); // PORTRAIT, MM MEASUREMENT, A4 PAPER
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',8);
$pdf->Cell(5,0,"PROJECT");
$pdf->Cell(10);
$pdf->Cell(5,0,": SSP REVITALIZATION");

$pdf->Cell(5,0,"PROJECT",0,1);
$pdf->Output();
?>