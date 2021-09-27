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

<?php
if (isset($_POST['Cargar'])) {
    $sql = "INSERT INTO `hesk_consumibles` 
    (
    `comodato`, 
    `consumible`, 
    `referencia`, 
    `cantidad`, 
    `fechaSolicitud`, 
    `fechaEnvio`, 
    `solicitante`, 
    `estado`
    ) VALUES (
    $_POST[cliente], 
    $_POST[consumible], 
    $_POST[referencia], 
    $_POST[cantidad], 
    '$_POST[fechaSolicitud]', 
    '$_POST[fechaEnvio]', 
    '$_POST[solicitante]', 
    $_POST[estado]);";

    echo $sql;

    hesk_dbQuery($sql);

    
}

?>

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>

</head>



<div style="margin-left: 10px;" class="main__content settings">
    <!-- Inicio de formulario agregar zona -->
    <h1 class="h1est">Agregar Solicitud </h1>
    <div class="table-wrap">

        <form action="admin_consumibles.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" onsubmit='return validar()'>

            <div class="form-group">

                <label for="cliente">Cliente </label>
                <select autocomplete="off" type="text" name="cliente" id="cliente" class="form-control" placeholder="cliente" require="true">
                    <?php
                    $sql = "SELECT id,nombre FROM hesk_customers";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo "$reg[id]"; ?>"><?php echo "$reg[nombre]"; ?></option>
                    <?php } ?>
                </select>

                <label for="consumible">Consumible</label>
                <select name="consumible" id="consumible" class="form-control">
                    <?php
                    $sql = "SELECT id,consumible FROM hesk_descripcion_consumible";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo "$reg[id]"; ?>"><?php echo "$reg[consumible]"; ?></option>
                    <?php } ?>
                </select>

                <label for="referencia">Referencia</label>
                <select name="referencia" id="referencia" class="form-control">
                    <?php
                    $sql = "SELECT id,referencia FROM hesk_referencias_consumibles";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo "$reg[id]"; ?>"><?php echo "$reg[referencia]"; ?></option>
                    <?php } ?>
                </select>
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control">
                <label for="fechaSolicitud">Fecha de solicitud</label>
                <input type="date" name="fechaSolicitud" id="fechSolicitud" class="form-control">
                <label for="fechaEnvio">Fecha de envío</label>
                <input type="date" name="fechaEnvio" id="fechaEnvio" class="form-control">
                <label for="solicitante">Solicitante</label>
                <input type="text" name="solicitante" id="solicitante" class="form-control">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="0">Pendiente</option>
                    <option value="1">Enviado</option>
                </select>
            </div>

            <input name="Cargar" type="submit" value="Crear" class="btn btn-full">
        </form>
    </div>
    <h1 class="h1est">Control de consumibles</h1>
    <div class="table-wrap">
        <table id="tablazonas" class="display">
            <thead>
                <tr>
                    <th>Comodato</th>
                    <th>Consumible</th>
                    <th>Referencia</th>
                    <th>Cantidad</th>
                    <th>Fecha de solicitud</th>
                    <th>Fecha de envio</th>
                    <th>Días</th>
                    <th>Solicitante</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $reg2 = hesk_dbFetchAssoc(hesk_dbQuery("SELECT NOW() AS fechaActual"));
                $sql = "SELECT 
                    hcons.id as id,
                    hcus.nombre as nombreCliente,
                    hdc.consumible as consumible,
                    hrc.referencia as referencia,
                    hcons.cantidad as cantidad,
                    hcons.fechaSolicitud as solicitud,
                    hcons.fechaEnvio as envio,
                    hcons.solicitante as solicitante,
                    hcons.estado as estado,
                    datediff(now(),hcons.fechaEnvio) as dias
                    FROM hesk_consumibles hcons 
                    JOIN hesk_customers hcus ON hcus.id = hcons.comodato
                    JOIN hesk_descripcion_consumible hdc ON hdc.id = hcons.consumible
                    JOIN hesk_referencias_consumibles hrc ON hrc.id = hcons.consumible";
                $res = hesk_dbQuery($sql);
                while ($reg = hesk_dbFetchAssoc($res)) {
                ?>
                    <tr>
                        <td><?php echo "$reg[nombreCliente]" ?></td>
                        <td><?php echo "$reg[consumible]" ?></td>
                        <td><?php echo "$reg[referencia]" ?></td>
                        <td><?php echo "$reg[cantidad]" ?></td>
                        <td><?php echo "$reg[solicitud]" ?></td>
                        <td><?php echo "$reg[envio]" ?></td>
                        <td><?php echo "$reg[dias]" ?></td>
                        <td><?php echo "$reg[solicitante]" ?></td>
                        <td><a href=""><?php
                            if ($reg["estado"]) {
                                echo "Enviado";
                            } else {
                                echo "Pendiente";
                            }
                            ?></a>
                        </td>
                        <td>
                            <a href=""><i class="far fa-trash-alt" style="color: red;"></i></a>
                            <br>
                            <a href=""><i class="far fa-edit" style="color: green;"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Comodato</th>
                    <th>Consumible</th>
                    <th>Referencia</th>
                    <th>Cantidad</th>
                    <th>Fecha de solicitud</th>
                    <th>Fecha de envio</th>
                    <th>Días</th>
                    <th>Solicitante</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>



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
<script type="text/javascript" src="../js/validation.js"> </script>