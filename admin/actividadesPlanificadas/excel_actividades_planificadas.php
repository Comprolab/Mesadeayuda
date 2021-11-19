<?php

header("Content-type:application/xls; charset=utf-8");
header("Content-Disposition: attatchment; filename=tareasPlaificadas.xls");

define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();

hesk_dbConnect();

$res = hesk_dbQuery("SELECT
hrd.id,
hrd.fecha,
hrd.nombreTecnico,
hrd.tareaRealizada,
hrd.observaciones,
hrd.cliente,
hc.nombre
FROM hesk_tareas_planificadas hrd 
LEFT JOIN hesk_customers hc 
ON hrd.cliente = hc.id 
JOIN hesk_users hu 
ON hu.name = hrd.nombreTecnico 
JOIN hesk_categories hca
ON hu.categoria = hca.id 
WHERE (fecha BETWEEN '$_POST[fechaReporte]' AND '$_POST[fechaReporte2]') 
AND hrd.nombreTecnico LIKE '%$_POST[consTecnico]%' 
AND hc.nombre LIKE '%$_POST[consCliente]%' 
AND hca.id IN $_POST[consCategoria] ORDER BY hrd.fecha,hrd.nombreTecnico");

?>

<center>
    <style>
        table {
            border: 2px;

        }

        th {
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
                <th style="width: 90px; font-weight: bold;"><?php echo utf8_decode('Nombre del Técnico')?></th>
                <th style="width: 120px; font-weight: bold;">Cliente</th>
                <th style="width: 230px; font-weight: bold;">Tareas Realizadas</th>
                <th style="width: 210px; font-weight: bold;">Observaciones</th>
            </tr>
        </thead>
        <tbody>

            <?php
            while ($reg = mysqli_fetch_assoc($res)) {
            ?>
                <tr>
                    <td style="width: 75px;"><?php echo utf8_decode($reg['fecha']) ?></td>
                    <td style="width: 90px;"><?php echo utf8_decode($reg['nombreTecnico']) ?></td>
                    <td style="width: 120px;"><?php echo utf8_decode($reg['nombre']) ?></td>
                    <td style="width: 230px; word-break: break-word;">
                        <?php echo utf8_decode($reg['tareaRealizada']) ?>
                    </td>
                    <td style="width: 210px;word-break: break-word;"><?php echo utf8_decode($reg['observaciones']) ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <center>