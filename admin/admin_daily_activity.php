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

<div style="margin-left: 10px;" class="main__content settings">
    <div class="table-wrap">
        <h1 class="h1est">Actividad diaria</h1>
        <form action="" id="formDailyActivity" method="post" class="form">

            <div class="form-group">
                <label for="fecha">fecha</label>
                <input value="<?php echo date("Y-m-d"); ?>" id="fecha" name="fecha" type="date" class="form-control">
            </div>

            <div class="form-group">
                <label for="nombreTecnico">Nombre del técnico</label>
                <input disabled require value="<?php echo $_SESSION['name'] ?>" id="nombreTecnico" name="nombreTecnico" type="text" class="form-control">
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

</div>

<script>
    function registarDiario() {
        if (validar()) {
            $.ajax({
                type: 'POST',
                url: 'insertRegistroDiario.php',
                data: $('#formDailyActivity').serialize(),
                success: function(data) {
                    alert("Insersión exitosa!");
                    alert(data);
                    document.getElementById("formDailyActivity").reset();
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
</script>