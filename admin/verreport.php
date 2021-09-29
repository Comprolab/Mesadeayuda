<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');

// Save the default language for the settings page before choosing user's preferred one
$hesk_settings['language_default'] = $hesk_settings['language'];
require(HESK_PATH . 'inc/common.inc.php');
$hesk_settings['language'] = $hesk_settings['language_default'];
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/setup_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

// Load custom fields
require_once(HESK_PATH . 'inc/custom_fields.inc.php');

// Test languages function
if (isset($_GET['test_languages'])) {
    hesk_testLanguage(0);
} elseif (isset($_GET['test_themes'])) {
    hesk_testTheme(0);
}

$help_folder = '../language/' . $hesk_settings['languages'][$hesk_settings['language']]['folder'] . '/help_files/';

$enable_save_settings   = 0;
$enable_use_attachments = 0;

// Print header
require_once(HESK_PATH . 'inc/header.inc.php');

// Print main manage users page
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');

?>
<script type="text/javascript" src="../js/validation.js"></script>

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>

</head>


<?php


 
    $nombreReporte       = $_POST["nombreReporte"];
    $asignado            = $_POST["asignado"];
    $fechaInicio         = $_POST["fechaInicio"];
    $fechaFinal          = $_POST["fechaFinal"];
    $estado              = $_POST["estado"];
    $localizacion        = $_POST["localizacion"];
    $clase_de_servicio   = $_POST["clase_de_servicio"];
    $situacion_reportada = $_POST["situacion_reportada"];
    $situacion_obserbada = $_POST["situacion_obserbada"];
    $descripcion         = $_POST["descripcion"];
    $estadoEquipo        = $_POST["estadoEquipo"];
    $recomendacion       = $_POST["recomendacion"];
    $formaPago           = $_POST["formaPago"];
    $profecional         = $_POST["profecional"];
    $cargo               = $_POST["cargo"];
    $nombreCliente       = $_POST["nombreCliente"];
    $direccion           = $_POST["direccion"];
    $numero              = $_POST["numero"];
     
    #$query = "INSERT INTO hesk_reporte_de_servicio (nombreReporte, asignado, fechaInicio, fechaFinal, 
     #estado, localizacion, descripcion , estadoEquipo, recomendacion, formaPago, profecional, cargo, nombreCliente, direccion, numero ) VALUES('$nombreReporte', '$asignado', '$fechaInicio', '$fechaFinal', '$estado', $localizacion, '$descripcion',
     #'$estadoEquipo', '$recomendacion', '$formaPago', $profecional, '$cargo', $nombreCliente, '$direccion', $numero )";
    

   // $query2 = "INSERT INTO `hesk_reporte_de_servicio` ( `nombreReporte`, `asignado`, `fechaInicio`, `fechaFinal`, `estado`, `localizacion`, `descripcion`, `estadoEquipo`, `recomendacion`, `formaPago`, `profecional`, `cargo`, `nombreCliente`, `direccion`, `numero`) 
   //7 VALUES ( '$nombreReporte', '$asignado', '$fechaInicio', '$fechaFinal', '$estado', '$localizacion', '$descripcion', '$estadoEquipo', '$recomendacion', '$formaPago', '$profecional', '$cargo', '$nombreCliente', '$direccion', '$numero');";
     //echo $query;
     $query3 ="INSERT INTO `hesk_reporte_de_servicio` ( `nombreReporte`, `asignado`, `fechaInicio`, `fechaFinal`, `estado`, `localizacion`, `clase_de_servicio`, `situacion_reportada`, `situacion_obserbada`, `descripcion`, `estadoEquipo`, `recomendacion`, `formaPago`,
      `profecional`, `cargo`, `nombreCliente`, `direccion`, `numero`) VALUES ( '$nombreReporte', '$asignado ', '$fechaInicio', '$fechaFinal', '$estado ', '$localizacion ', ' $clase_de_servicio', '$situacion_reportada', '$situacion_obserbada', '$descripcion',
       '$estadoEquipo', '$recomendacion', '$formaPago', '$profecional', '$cargo ', '$nombreCliente', '$direccion ', '$numero ')";
     hesk_dbQuery($query3);
     

     
     
    

   








?>
<!-- Inicio de formularios -->
<div style="margin-left: 10px;" class="main__content settings">

    <h1 class="h1est">Administrar Reportes </h1>
    <div class="table-wrap">

        <form action="reportes.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>"
onsubmit='return validar()'>


        </form>
        <div class="table-wrap">
         <table id="tablaclientes" class="display">
            <thead>
                <tr>
                    <th>Id Reporte </th>
                    <th>tipo de reporte</th>
                    <th>cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
               $query = "SELECT hesk_reporte_de_servicio.*,`hesk_customers`.`nombre` FROM `hesk_reporte_de_servicio` INNER JOIN `hesk_customers` ON `hesk_reporte_de_servicio`.`nombreCliente` = `hesk_customers`.`id` order by `hesk_customers`.`id` desc;";
          
               $row = hesk_dbQuery( $query );

                  foreach( $row  as $rowiter ) {
                    ?>
                    <tr>
                        <td><?php echo $rowiter['id'] ?></td>
                        <td><?php echo $rowiter['nombreReporte'] ?></td>
                        <td><?php echo $rowiter['nombre'] ?></td>
                        <td>
                        <a href="reportes/reporteDeServicio.php?id=<?php echo $rowiter['id'] ?>">editar</a>
                        <a href="reportes/reporteDeServicio.php?id=<?php echo $rowiter['id'] ?>">eliminar</a>
                        <a href="reportes/reporteDeServicio.php?id=<?php echo $rowiter['id'] ?>">generar reporte</a>
                    
                        </td>
                    </tr>
                    <?php
                  }
                
                
                ?>

               
            </tbody>
            <tfoot>
                <tr>
                    <th>Id Reporte </th>
                    <th>tipo de reporte</th>
                    <th>cliente</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    </div>


    <!-- Script para datatable -->
    <script>
    $(document).ready(function() {
        $('#tablaclientes').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ filas por pagina",
                "zeroRecords": "No se encuentran resultados",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "Sin filas disponibles",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
            "pagingType": "full_numbers"
        });
    });
</script>
<script type="text/javascript" src="../js/no-resend.js"> </script>
<script type="text/javascript" src="../js/validation.js"> </script>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>
