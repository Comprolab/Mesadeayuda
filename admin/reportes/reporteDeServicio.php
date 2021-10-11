<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');


$hesk_settings['language_default'] = $hesk_settings['language'];
require(HESK_PATH . 'inc/common.inc.php');
$hesk_settings['language'] = $hesk_settings['language_default'];
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/setup_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();

//AddPage(orientacion[PORTRAIT, LANDSCAPE], tamaño[A3, A4, LETTER, LEGAL], rotacion multiplo de 180),
//SetFont(tipo[COURIER, HELVETICA, ARIAL, TIMES, SYMBOL, ZAPDINGBATS], estilo[normal, B, I, U], tamaño),
//Cell(ancho y espacio que ocupa , alto y espacio que ocupa, bordes, ?, alineacion, rellenar , link),
//OutPut(destino[I, D, F, S], nombre_archivo, itf8)
//Write(alto, 'texto',link  );
//Ln(); sirve para hacer un salto de linea 
//  $this->Image('ruta',posicion en X, posicion en Y,alto , ancho , tipo ,link);

use PDF as GlobalPDF;

require('fpdf/fpdf.php');
$id = $_GET["id"]; 

$query = "SELECT H.*, C.nombre, U.name FROM `hesk_reporte_de_servicio` H INNER JOIN  `hesk_customers` C ON H.`nombreCliente` = C.`id` 
INNER JOIN `hesk_users` U ON H.profecional = U.id 

WHERE H.ID = $id";
$res = hesk_dbQuery( $query );
$row = hesk_dbFetchAssoc($res);

class PDF  extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
   $this->Image('img/logo.png',15,2,33);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Movernos a la derecha
    $this->Cell(50);
   
    // Título
    
    $this->Cell(100,10,'Reporte de servicio ',1,0,'C');
    $this->Cell(40,10,'FO-ST-118 ' . 'Version1'  ,1,0,'C');
    // Salto de línea
    $this->Ln(20);
   
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-40);
    // Arial italic 8
    $this->SetFont('Arial','B',8);
    // Número de página
    $this->Cell(0,10,'Calle 106 No.54-63-CONMUTADOR PBX:742 6486 - Emai:sistemas@comprolab.com '.$this->PageNo().'page',0,1,'C');
    $this->SetX(100);
    $this->Write(5,'Bogota D,C',0,1);
    
}
}

// Creación del objeto de la clase heredada

$pdf = new PDF();
$pdf->AddPage();



$pdf->SetFont('HELVETICA','B',8);
$pdf->SetX(20);
$pdf->Cell(90 ,21,   " NIT. 860.350.711-1 Departamento de ingenieria   ", 1,0, 'C' );

$pdf->SetX(110);
$pdf->Cell(90 ,7,   'Reporte de servicio No.:  ' .'RF ' . date("Y") .'- '. $row['id'], 1,1, 'C' );
$pdf->SetX(110);
$pdf->Cell(90 ,7,   'Sello de seguridad No.: ', 1,1, 'C' );
$pdf->SetX(110);
$pdf->Cell(90 ,7,   'Ciudad y fecha,:  '. $row['fechaFinal'], 1,1, 'C' );
$pdf->Ln(3);
#segunda seccion 
$pdf->SetFont('HELVETICA','B',9);
$pdf->SetX(20);
$pdf->Cell(180, 5, utf8_decode('Cliente:  '. $row['nombre']),1,1, false );
$pdf->SetX(20);
$pdf->Cell(90, 5, utf8_decode('Ciudad:  ' . $row['localizacion']),1,0, false );
$pdf->Cell(90, 5, 'Departamento ',1,1, false );
$pdf->SetX(20);
$pdf->Cell(90, 5, utf8_decode('Direcion:  '. $row['direccion']),1,0, false );
$pdf->Cell(90, 5, utf8_decode('Telefono:  '. $row['numero']),1,1, false );
$pdf->SetX(20);
$pdf->Cell(60, 5, 'Equipo.: ',1,0, false );
$pdf->Cell(60, 5, 'Serie.: ',1,0, false );
$pdf->Cell(60, 5, 'Marca.: ',1,1, false );
$pdf->SetX(20);
$pdf->Cell(60, 5, 'Tipo de equipo ',1,0, false );
$pdf->Cell(120, 5, 'Modelo  ',1,0, false );
$pdf->Ln(10);
$pdf->SetFont('HELVETICA','B',9);
$pdf->SetX(20);
$pdf->Cell(180, 5, 'Clase de servicio : ' ,1,1, 'C' );
$pdf->SetX(20);
$pdf->SetFont('HELVETICA','',9);
$pdf->Cell(180, 5, utf8_decode(''.$row['clase_de_servicio']),1,0, 'C');

#seccion 3 
$pdf->Ln(10);
$pdf->SetFont('HELVETICA','B',9);
$pdf->SetX(20);
$pdf->MultiCell(180,10 , utf8_decode(' Situacion reportada:   '.  $row['situacion_reportada']) ,1,1);
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->MultiCell(180,10 , utf8_decode(' situación observada:   '.  $row['situacion_obserbada']) ,1,0);
$pdf->Ln(15);
$pdf->SetX(20);
$pdf->MultiCell(180, 10 , utf8_decode('labor realizada: '. $row['descripcion'])  ,1,1);
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(180, 10 ,utf8_decode('Estado del equipo al finalizar el equipo:  ' . $row['estadoEquipo']),1,1);
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(180, 20 , utf8_decode('Recomendaciones :  ' .$row['recomendacion']) ,1,1);
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(180, 5 , utf8_decode('Formas de pago :  ' . $row['formaPago']) ,1,1);
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(90, 15 , utf8_decode('Firma y sello del cliente :  ') ,1,0 , 'T');
$pdf->Cell(90, 15 , utf8_decode('Firma ingeniero de comprolab S.A.S: ')  ,1,1 , 'T');
$pdf->SetX(20);
$pdf->Cell(90,5 ,'Nombre : ' ,1,0, 'T');
$pdf->Cell(90, 5 , utf8_decode('Nombre: ' .$row['name']) ,1,1 , 'T');
$pdf->SetX(20);
$pdf->Cell(90, 5 ,'Cargo: ' ,1,0, 'T');
$pdf->Cell(90, 5 ,utf8_decode('cargo:  '. $row['cargo'] ),1,0 , 'T');

$pdf->Output('I', 'Reporte de servicio.pdf');




?>