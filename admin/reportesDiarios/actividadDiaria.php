<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();

hesk_dbConnect();

$res = hesk_dbQuery("SELECT * FROM hesk_registros_diarios")

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    </style>
</head>


<body>
<center>
    <h1>ACTIVIDAD DIARIA</h1>
    <h1><?php echo $_POST['fechaReporte'] ?></h1>

    <table id="example" border="1">
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
            while($reg = mysqli_fetch_assoc($res)) {
            ?>
                <tr>
                    <td style="width: 75px;"><?php echo $reg['fecha'] ?></td>
                    <td style="width: 60px;"><?php echo $reg['nombreTecnico'] ?></td>
                    <td style="min-width: 250px; max-width: 260px; word-break: break-word;">
                        <?php echo $reg['tareaRealizada'] ?>
                    </td>
                    <td style="min-width: 250px;word-break: break-all;"><?php echo $reg['observaciones'] ?></td>
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