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



<div style="margin-left: 10px; padding-right: 50px;" class="main__content settings">
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
                        <td><button style="width: 60px;" href="#" id="estadoT" onclick="changeStatus(<?php echo $reg['id'] ?>);" val="<?php echo $reg['id'] ?>"><?php
                                                                                                                                                                if ($reg["estado"]) {
                                                                                                                                                                    echo "Enviado";
                                                                                                                                                                } else {
                                                                                                                                                                    echo "Pendiente";
                                                                                                                                                                }
                                                                                                                                                                ?><br> </button>
                        </td>
                        <td style="text-align: center;">
                            <a href="" class="tooltip" id="delete_cons" onclick="deleteCons(<?php echo $reg['id'] ?>);">
                                <i class="far fa-trash-alt" style="color: red;"></i>
                                <span class="tooltiptext">Eliminar registro</span>
                            </a>
                            <br>
                            <a href="#" class="tooltip" onclick="editCons(<?php echo $reg['nombreCliente'] ?>)">
                                <i class="far fa-edit" style="color: green;"></i>
                                <span class="tooltiptext" id="tooltipleft">Editar registro</span>
                            </a>
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


    <!-- modal de edición -->

    <div class="w3-container">

        <div id="id01" class="w3-modal">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                <div class="w3-center"><br>
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                    <img src="../img/Logo_Comprolab.png" alt="logo" style="width:20%" class="w3-circle w3-margin-top">
                </div>

                <form class="w3-container" action="/action_page.php">
                    <div class="w3-section">
                        <label><b>Username</b></label>
                        <input id="idCEN" class="w3-input w3-border w3-margin-bottom" type="text" name="clienteE" disabled>
                        <label><b>Password</b></label>
                        <input class="w3-input w3-border" type="password" placeholder="Enter Password" name="psw" required>
                        <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit">Login</button>
                    </div>
                </form>

                <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                    <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancelar</button>
                    
                </div>

            </div>
        </div>
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

<script>
    function changeStatus(consu) {
        $.ajax({
            type: "POST",
            url: "change_consumible_status.php",
            data: {
                idC: consu
            },
            success: function(data) {
                location.reload();
            }
        });
    };

    function deleteCons(consu) {
        $.ajax({
            type: "POST",
            url: "delete_consumible.php",
            data: {
                idC: consu
            },
            success: function(data) {
                event.preventDefault();
                $(this).closest('tr').remove();
                alert("Consumible eliminado");
            }
        });
    }

    function editCons(nomCli,consu,cant,fechaEnvio) {

        $.ajax({
            type: 'POST',
            data: {
                idCE: consu
            },
            success: function(data) {

                document.getElementById('idCEN').value = consu;
                document.getElementById('id01').style.display = 'block';
            }
        });
    }
</script>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>

<script type="text/javascript" src="../js/no-resend.js"> </script>
<script type="text/javascript" src="../js/validation.js"> </script>