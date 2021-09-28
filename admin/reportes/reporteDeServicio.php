<?php


require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
   # $this->Image('logo.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Movernos a la derecha
    $this->Cell(10);
   
    // Título
    $this->Cell(40,10,'Reporte de servicio',1,0,'C');
    $this->Cell(100,10,'Reporte de servicio',1,0,'C');
    $this->Cell(40,10,'FO-ST-118 ' . 'Version1'  ,1,0,'C');
    // Salto de línea
    $this->Ln(15);
   
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Cell(80,30, utf8_decode('¡Hola, Mundo!') ,1,0,'C' );
$pdf->Cell(80,10, utf8_decode('¡Hola, Mundo!') ,1,1,'C' );
$pdf->Cell(80,10, utf8_decode('') ,0,0,'C' );
$pdf->Cell(80,10, utf8_decode('¡Hola, Mundo!') ,1,1,'C' );
$pdf->Cell(80,10, utf8_decode('') ,0,0,'C' );
$pdf->Cell(80,10, utf8_decode('¡Hola, Mundo!') ,1,0,'C' );



$pdf->Output();

?>