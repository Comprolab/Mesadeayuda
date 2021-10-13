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

if (isset($_POST['Crear'])) {
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
    $recomendacion       =  $_POST["recomendacion"];
    $formaPago           = $_POST["formaPago"];
    $profecional         = $_POST["profecional"];
    $cargo               = $_POST["cargo"];
    $nombreCliente       = $_POST["nombreCliente"];
    $direccion           = $_POST["direccion"];
    $numero               = $_POST["numero"];

    $query3 = "INSERT INTO `hesk_reporte_de_servicio` ( `nombreReporte`, `asignado`, `fechaInicio`, `fechaFinal`, `estado`, `localizacion`, `clase_de_servicio`, `situacion_reportada`, `situacion_obserbada`, `descripcion`, `estadoEquipo`, `recomendacion`, `formaPago`,
      `profecional`, `cargo`, `nombreCliente`, `direccion`, `numero`) VALUES ( '$nombreReporte', '$asignado ', '$fechaInicio', '$fechaFinal', '$estado ', '$localizacion ', ' $clase_de_servicio', '$situacion_reportada', '$situacion_obserbada', '$descripcion',
       '$estadoEquipo', '$recomendacion', '$formaPago', '$profecional', '$cargo ', '$nombreCliente', '$direccion ', '$numero ')";
    hesk_dbQuery($query3);

    header("location:verreport.php");

}

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
<?php



    


?>

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script  src="validaciones.js"> </script>

</head>



<!-- Inicio de formularios -->
<div style="margin-left: 10px;" class="main__content settings">

    <h1 class="h1est">Crear Reporte </h1>
    <div class="table-wrap">

        <form action="reportes.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" onsubmit='return validarreporte()'>

            <div class="form-group">
                <label>
                    <span>Tipo de reporte </span>

                    <div class="form-group">
                        <select name="nombreReporte" id="nombreReporte" class="form-control" style="border: 90%;">}
                            <option value=""></option>
                            <option value="Reporte de servicio">Reporte de servicio</option>

                        </select>
                </label>

            </div>

            <div class="form-group">
                <label>
                    <span>Asignado </span>

                </label>
                <select name="asignado" id="asignado" class="form-control" style="border: 90%;">}
                <option value="">selecione</option>
                    <?php
                    $sql = "SELECT id,name FROM hesk_users  WHERE  rol = 1   ";
                    $res = hesk_dbQuery($sql);
                    echo "<option disabled='true' value='0' selected></option>";
                    echo "<option disabled='true' value='0' selected></option>";
                    while ($reg = hesk_dbFetchAssoc($res)) {
                        echo "<option value='$reg[id]'>$reg[name]</option>";
                    }
                    ?>
                </select>
                </label>
            </div>

            <div class="form-group">
                <label>
                    <span>Fecha de inicio </span>

                </label>

                <input id="fechaInicio" type="date" class="form-control" name="fechaInicio" maxlength="255">
            </div>

            <div class="form-group">
                <label>
                    <span>Fecha final </span>

                </label>

                <input type="date" class="form-control" name="fechaFinal" maxlength="255">
            </div>

            <div class="form-group">
                <label>
                    <span>Estado de servicio </span>

                </label>
                <select name="estado" id="estado" class="form-control" style="border: 90%;">}
                    <option value="Realizado">Realizado</option>
                    <option value="Pendiente">Pendiente </option>
                    <option value="Aplazado">Aplazado </option>
                    <option value="En operacion">En operacion </option>
                    <option value="Planeado">Planeado</option>
                </select>
            </div>

            <div class="form-group">
                <label>
                    <span>Localización</span>

                </label>
                <select name="localizacion" id="localizacion" class="form-control">

                    <option value="Bogota">Bogota</option>
                    <option value="Medellin">Medellin</option>
                    <option value="Cali">Cali</option>
                    <option value="Cartagena de Indias">Cartagena de Indias</option>
                    <option value="Soacha">Soacha</option>
                    <option value="Cúcuta">Cúcuta</option>
                    <option value="Soledad">Soledad</option> 
                    <option value="Bello">Bello</option>
                    <option value="Villavicencio">Villavicencio</option>
                    <option value="Ibagué">Ibagué</option>
                    <option value="Valledupar">Valledupar</option>
                    <option value="Manizales">Manizales</option>
                    <option value="Pereira">Pereira</option>
                    <option value="Montería">Montería</option>
                    <option value="Neiva">Neiva</option>
                    <option value="Pasto">Pasto</option>
                    <option value="Armenia">Armenia</option>
                </select>
            </div>
            <div class="form-group">
                <label>
                    <span>Clase de servicio </span>

                </label>
                <select name="clase_de_servicio" id="clase_de_servicio" class="form-control" style="border: 90%;">}
                    <option value=""></option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="reparacion">reparacion </option>
                    <option value="Visita">Visita </option>
                    <option value="preventivo">preventivo </option>
                    <option value="Comodato">Comodato</option>
                </select>
            </div>


            <div class="form-group">
                <label>
                    <span>Situacion reportada</span>

                </label>

                <textarea id="situacion_reportada" style="width: 100%;" type="textarea" class="form-control" name="situacion_reportada" maxlength="255" value=""></textarea>
            </div>

            <div class="form-group">
                <label>
                    <span>Situacion observada</span>

                </label>

                <textarea id="situacion_obserbada" style="width: 100%;" type="textarea" class="form-control" name="situacion_obserbada" maxlength="255" value=""></textarea>
            </div>

            <div class="form-group">
                <label>
                    <span>Labor realizada</span>

                </label>

                <textarea id="descripcion" style="width: 100%;" type="textarea" class="form-control" name="descripcion" maxlength="255" value=""></textarea>
            </div>


            <div class="form-group">
                <label>
                    <span>Estado de equipo</span>

                </label>
                <textarea style="width: 100%;" type="textarea" class="form-control" id="estadoEquipo" name="estadoEquipo" maxlength="255" value=""></textarea>
            </div>


            <div class="form-group">
                <label>
                    <span>Recomendación</span>

                </label>
                <textarea style="width: 100%;" type="textarea" class="form-control" id="recomendacion" name="recomendacion" maxlength="255" value=""></textarea>
            </div>


            <div class="form-group">
                <label>
                    <span>Forma de pago </span>

                </label>
                <select id="formaPago" name="formaPago" id="formaPago" class="form-control" style="border: 90%;">
                    <option value=""></option>
                    <option value="Comodato">Comodato</option>
                    <option value="Credito">Credito</option>
                    <option value="Efectivo">Efectivo </option>
                    <option value="Devito">Devito </option>

                </select>
            </div>


            <div class="form-group">
                <label>
                    <span>Profesional</span>

                </label>
                <select id="profecional" name="profecional" id="zona" class="form-control" style="border: 90%;">}

                    <?php
                    $sql = "SELECT id,name FROM hesk_users  WHERE  rol = 1   ";
                    $res = hesk_dbQuery($sql);
                    echo "<option disabled='true' value='0' selected></option>";
                    echo "<option disabled='true' value='0' selected></option>";
                    while ($reg = hesk_dbFetchAssoc($res)) {
                        echo "<option value='$reg[id]'>$reg[name]</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="form-group">
                <label>
                    <span>Cargo</span>

                </label>
                <select name="cargo" id="cargo" class="form-control" style="border: 90%;">
                    <option value="value1"></option>
                    <option value="Ingeniero de sistemas ">Ingeniero de sistemas </option>
                    <option value="Tecnico de servicio">Tecnico de servicio</option>
                    <option value="Ingeniero electrico">Ingeniero electrico </option>
                    <option value="Ingeniero de zona ">Ingeniero de zona </option>
                    <option value="Bacteriologo ">Bacteriologo </option>

                </select>
            </div>



            <div class="form-group">
                <label>
                    <span>Nombre del cliente </span>

                </label>
                <select name="nombreCliente" id="nombreCliente" class="form-control">
                    <option value="Cliente">Cliente</option>
                    <?php
                    $sql = "SELECT id,nombre FROM hesk_customers";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo $reg['id'] ?>"><?php echo $reg['nombre'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>



            <div class="form-group">
                <label>
                    <span>Dirección</span>

                </label>
                <input type="text" class="form-control" id="direccion" name="direccion" maxlength="255" value="">
            </div>


            <div class="form-group">
                <label>
                    <span>Número de telefono del cliente </span>

                </label>
                <input type="text" class="form-control" id="numero" name="numero" maxlength="255" value="">
            </div>







            <input  id="Crear" name="Crear" type="submit" value="Crear Reporte" class="btn btn-full">
        </form>
    </div>


    <!-- Script para datatable -->
    <script>
        $(document).ready(function() {
            $('#tablazonas').DataTable({
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
    <?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>

    <script type="text/javascript" src="../js/no-resend.js"> </script>

 