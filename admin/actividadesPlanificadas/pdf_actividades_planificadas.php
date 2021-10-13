<?php



require("../../dompdf/autoload.inc.php");

ob_start();

?>
<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();

hesk_dbConnect();

$res = hesk_dbQuery("SELECT * FROM hesk_tareas_planificadas WHERE fecha BETWEEN '$_POST[fechaReporte]' AND '$_POST[fechaReporte2]' ORDER BY fecha,nombreTecnico");

if (hesk_dbNumRows($res) == 0) {

?>
    <h1>
        <center>Este día no se Planificaron actividades</center>
    </h1>
    <h1>
        <center>FECHA: <?php echo $_POST['fechaReporte'] . " HASTA " . $_POST['fechaReporte2'] ?></center>
    </h1>
<?php
} else {

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
        <script rel="stylesheet" href="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
        <title>Reporte <?php echo $_POST['fechaReporte'] . " HASTA " . $_POST['fechaReporte2'] ?></title>
        <style>
            table {
                border: 1px solid;
                width: 100%;
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
    </head>


    <body>
        <center>
            <h1>TAREAS PLANIFICADAS TECNICO DE SISTEMAS</h1>
            <h3>Fecha: <?php echo $_POST['fechaReporte'] . " HASTA " . $_POST['fechaReporte2'] ?></h3>

            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre Técnico</th>
                        <th>Tarea Realizada</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($reg = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <td style="width: 75px;"><?php echo $reg['fecha'] ?></td>
                            <td style="width: 90px;"><?php echo $reg['nombreTecnico'] ?></td>
                            <td style="width: 200px; word-break: break-word;">
                                <?php echo $reg['tareaRealizada'] ?>
                            </td>
                            <td style="width: 200px;word-break: break-word;"><?php echo $reg['observaciones'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre Técnico</th>
                        <th>Tarea Realizada</th>
                        <th>Observaciones</th>
                    </tr>
                </tfoot>
            </table>
            <center>
    </body>

    </html>
<?php
}
$dompdf = new Dompdf\Dompdf();

$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper("letter", "landscape");

$dompdf->render();
$dompdf->stream("Actividades diarias $_POST[fechaReporte] a $_POST[fechaReporte2].pdf");



?>