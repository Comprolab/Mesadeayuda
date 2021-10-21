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

<head>

</head>

<div style="margin-left: 10px; padding-right: 10px;" class="main__content settings">
    <a href="admin_planify_activity.php" class="btnb btnb-primary">Planificar</a>
    <div class="table-wrap">
        <h1 class="h1est">Tareas Diarias</h1>
        <form action="" id="formDailyActivity" method="post" class="form">

            <div class="form-group">
                <label for="fecha">fecha</label>
                <input value="<?php echo date("Y-m-d"); ?>" id="fecha" name="fecha" type="date" class="form-control">
            </div>

            <div class="form-group">
                <label for="nombreTecnico">Nombre del técnico o ingeniero</label>
                <input readonly require value="<?php echo $_SESSION['name'] ?>" id="nombreTecnico" name="nombreTecnico" type="text" class="form-control">
            </div>

            <div class="form-group">
                <label for="nombreCliente">Nombre del cliente</label>
                <select name="cliente" id="cliente" class="form-control">
                    <?php
                    $res = hesk_dbQuery("SELECT * FROM hesk_customers");
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo $reg['id'] ?>"><?php echo $reg['nombre'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tareaRealizada">Tarea realizada</label>
                <textarea id="tareaRealizada" name="tareaRealizada" type="text" class="form-control" style="width: 100%;"></textarea>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" type="text" class="form-control" style="width: 100%;"></textarea>
            </div>

            <input type="button" onclick="registarDiario()" value="Registar" id="btnRegistrar" class="btnb btnb-primary">

        </form>
        <p id="mssg"></p>
    </div>

    <?php
    $queryRegDiario = "SELECT
    hrd.id,
    hrd.fecha,
    hrd.nombreTecnico,
    hrd.tareaRealizada,
    hrd.observaciones,
    hrd.cliente,
    hc.nombre
    FROM hesk_registros_diarios hrd LEFT JOIN hesk_customers hc ON hrd.cliente = hc.id";
    $resDiario = hesk_dbQuery($queryRegDiario);
    ?>

    <div class="table-wrap" style="width: 100%;">
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nombre Técnico</th>
                    <th>Cliente</th>
                    <th>Tarea Realizada</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($reg = hesk_dbFetchAssoc($resDiario)) {
                ?>
                    <tr id="row<?php echo $reg['id'] ?>">
                        <td><?php echo $reg['fecha'] ?></td>
                        <td><?php echo $reg['nombreTecnico'] ?></td>
                        <td><?php echo $reg['nombre'] ?></td>
                        <td style="min-width: 200px; word-break: break-word;">
                            <?php echo $reg['tareaRealizada'] ?>
                        </td>
                        <td style="min-width: 180px;word-break: break-all;"><?php echo $reg['observaciones'] ?></td>
                        <td>
                            <button id="eliminarActividadRealizada" onclick="eliminarActividad(<?php echo $reg['id'] ?>)"><i style="color: red;" class='fas fa-trash-alt'></i></button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>Nombre Técnico</th>
                    <th>Cliente</th>
                    <th>Tarea Realizada</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>

        </table>
        <hr>
        <h1 class="h1est">Exportar</h1>
        <form action="reportesDiarios/pdf_report_daily2.php" target="_blank" method="POST">
            <div class="form-group">
                <input class="form-control" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte" id="fechaReporte">
            </div>
            <div class="form-group">
                <input class="form-control" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte2" id="fechaReporte">
            </div>

            <div class="form-group">
                <select name="consTecnico" id="consTec">
                    <option value="%">todos</option>
                    <?php
                    $query = "SELECT * FROM hesk_users WHERE rol = 1";
                    $res = hesk_dbQuery($query);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo $reg['name'] ?>"><?php echo $reg['name'] ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>

            <input type="submit" value="exportar" class="btnb btnb-info" id="fechaR">
        </form>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    function eliminarActividad(ids) {
        $.ajax({
            type: 'POST',
            url: 'eliminarActividad.php',
            data: {
                del_id: ids
            },
            success: function(data) {
                alert("Se eliminó el registro !!");
                $("#row" + ids).css("display", "none");
            }
        });
    }

    function registarDiario() {
        if (validar()) {
            $.ajax({
                type: 'POST',
                url: 'insertRegistroDiario.php',
                data: $('#formDailyActivity').serialize(),
                success: function(data) {
                    alert("Insersión exitosa!");
                    location.reload();
                }
            });
        }

    }

    function validar() {

        if ($("#tareaRealizada").val() == "") {
            alert("Debe digitar sus tareas realizadas");
            return false;
        }
        return true;
    }

    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "url": "../language/es/datatable.json"
            }
        });
    });
</script>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>