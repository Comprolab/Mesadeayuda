<?php
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */

use MYPDF as GlobalMYPDF;

// Include the main TCPDF library (search for installation path).
require_once('../../TCPDF/tcpdf.php');

define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();

hesk_dbConnect();
$res = hesk_dbQuery("SELECT * FROM hesk_registros_diarios WHERE fecha BETWEEN '$_POST[fechaReporte]' AND '$_POST[fechaReporte2]'");

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../../tcpdf/examples/images/logo_comprolab.png';
        // $this->Image($image_file, 10, 0, 30, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // $this->writeHTMLCell(50,'',5,8,"<img src = $image_file>",0,0,false,true,'L',true);
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->writeHTMLCell(150,1,31,8,"<h4 style = 'text-align: center;'>REPORTE TAREAS DIARIAS TÉCNICOS DE SISTEMAS</h4>",0,0,false,true,'C',true);
        
        $this->SetFont('helvetica', 'B', 10);
        
        $this->writeHTMLCell(150,1,31,20,"<p style = 'text-align: center;'>Fecha: $_POST[fechaReporte] HASTA $_POST[fechaReporte2]</p>",0,0,false,true,'C',true);
        $this->Ln(10,true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistemas comprolab');
$pdf->SetTitle("Reporte de sistemas $_POST[fechaReporte] a $_POST[fechaReporte2]");
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords("TCPDF, PDF, reporte, sistemas, $_POST[fechaReporte], $_POST[fechaReporte2]");

// set default header data

// set header and footer fonts

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();


$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------
ob_start();
?>

<center>
<style>
            table {
                border: 2px;
                
            }

            th{
                font-weight: bold;
            }

            td,
            th {
                border: 1px solid;
                border-collapse: collapse;
                text-align: justify;
                vertical-align: middle;
            }

            /** Definir las reglas del encabezado **/
            thead {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
            }

            /** Definir las reglas del pie de página **/
            tfoot {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

            }
        </style>
            <!-- <h3 style="text-align: center;">Fecha: <?php echo $_POST['fechaReporte'] . " HASTA " . $_POST['fechaReporte2'] ?></h3> -->

            
            <table cellspacing="0" cellpadding="5" id="example" class="">
                <thead>
                    <tr>
                        <th style="width: 75px; font-weight: bold;">Fecha</th>
                        <th style="width: 90px; font-weight: bold;">Nombre Técnico</th>
                        <th style="width: 250px; font-weight: bold;">Tarea Realizada</th>
                        <th style="width: 220px; font-weight: bold;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($reg = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr >
                            <td style="width: 75px;"><?php echo $reg['fecha'] ?></td>
                            <td style="width: 90px;"><?php echo $reg['nombreTecnico'] ?></td>
                            <td style="width: 250px; word-break: break-word;">
                                <?php echo $reg['tareaRealizada'] ?>
                            </td>
                            <td style="width: 220px;word-break: break-word;"><?php echo $reg['observaciones'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
            <center>
<?php
$pdf->writeHTML(ob_get_clean(), true, false, false, false, '');

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output("Actividades diarias $_POST[fechaReporte] hasta $_POST[fechaReporte2].pdf", 'I');

//============================================================+
// END OF FILE
//============================================================+