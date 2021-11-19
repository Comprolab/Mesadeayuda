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
                <label for="nombreTecnico">Nombre del Profesional</label>
                <input readonly require value="<?php echo $_SESSION['name'] ?>" id="nombreTecnico" name="nombreTecnico" type="text" class="form-control">
            </div>

            <div class="form-group">
                <label for="nombreCliente">Nombre del cliente</label>
                <select name="cliente" id="cliente" class="form-control">
                    <?php
                    $res = hesk_dbQuery("SELECT * FROM hesk_customers ORDER BY nombre");
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

            <input type="button" onclick="registarDiario()" value="Registrar" id="btnRegistrar" class="btnb btnb-primary">

        </form>
        <p id="mssg"></p>
    </div>

    <?php
    if ($_SESSION['isadmin'] == 1) {
        $queryRegDiario = "SELECT
        hrd.id,
        hrd.fecha,
        hrd.nombreTecnico,
        hrd.tareaRealizada,
        hrd.observaciones,
        hrd.cliente,
        hc.nombre
        FROM hesk_registros_diarios hrd LEFT JOIN hesk_customers hc ON hrd.cliente = hc.id";
    } else {
        $queryRegDiario = "SELECT
        hrd.id,
        hrd.fecha,
        hrd.nombreTecnico,
        hrd.tareaRealizada,
        hrd.observaciones,
        hrd.cliente,
        hc.nombre
        FROM hesk_registros_diarios hrd LEFT JOIN hesk_customers hc ON hrd.cliente = hc.id
        WHERE hrd.nombreTecnico LIKE '$_SESSION[name]'";
    }
    $resDiario = hesk_dbQuery($queryRegDiario);
    ?>

    <div class="table-wrap" style="width: 100%;">
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nombre Profesional</th>
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
                        <td id="rowFecha<?php echo $reg['id'] ?>"><?php echo $reg['fecha'] ?></td>
                        <td id="rowNombreT<?php echo $reg['id'] ?>"><?php echo $reg['nombreTecnico'] ?></td>
                        <td id="rowNombreCliente<?php echo $reg['id'] ?>"><?php echo $reg['nombre'] ?></td>
                        <td id="rowTarea<?php echo $reg['id'] ?>" style="min-width: 200px; word-break: break-word;"><?php echo $reg['tareaRealizada'] ?></td>
                        <td id="rowObservaciones<?php echo $reg['id'] ?>" style="min-width: 180px;word-break: break-all;"><?php echo $reg['observaciones'] ?></td>
                        <td>
                            <button id="eliminarActividadRealizada" onclick="eliminarActividad(<?php echo $reg['id'] ?>)"><i style="color: red;" class='fas fa-trash-alt'></i></button>
                            <button onclick="openEditModal(<?php echo $reg['id'] ?>);"><i style="color: green;" class='fas fa-edit'></i></button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>

            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>Nombre Profesional</th>
                    <th>Cliente</th>
                    <th>Tarea Realizada</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>

        </table>

        <!-- Modal de edicion -->
        <div id="id01" class="w3-modal">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">


                <header class="w3-container w3-teal">
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                    <h2>Editar registro</h2>
                </header>


                <form id="formActualizar" class="w3-container">
                    <div class="w3-section">
                        <div class="form-group">
                            <label for="fechaEdit">fecha</label>
                            <input id="fechaEdit" name="fechaEdit" type="date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="nombreTecnicoEdit">Nombre del Profesional</label>
                            <input readonly require id="nombreTecnicoEdit" name="nombreTecnicoEdit" type="text" class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="tareaRealizadaEdit">Tarea realizada</label>
                            <textarea id="tareaRealizadaEdit" name="tareaRealizadaEdit" type="text" class="form-control" style="width: 100%;"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="observacionesEdit">Observaciones</label>
                            <textarea id="observacionesEdit" name="observacionesEdit" type="text" class="form-control" style="width: 100%;"></textarea>
                        </div>

                    </div>
                </form>

                <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                    <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancelar</button>
                    <button id="btnActualizar" onclick="actualizarTarea($('#formActualizar').attr('ide'))" class="w3-right w3-padding btnb btnb-info">Actualizar</button>
                </div>

            </div>
        </div>
        <script>
            function openEditModal(id) {

                var fecha = $("#rowFecha" + id).text();
                var nomT = $("#rowNombreT" + id).text();
                var tarea = $("#rowTarea" + id).text();
                var obs = $("#rowObservaciones" + id).text();
                $("#fechaEdit").attr("value", fecha);
                $("#nombreTecnicoEdit").attr("value", nomT);
                $("#tareaRealizadaEdit").val(tarea);
                $("#tareaRealizadaEdit").attr("value", tarea);
                $("#observacionesEdit").val(obs);
                $("#observacionesEdit").attr("value", obs);
                $("#formActualizar").attr("ide", id);
                document.getElementById('id01').style.display = 'block';

            }

            function actualizarTarea(id) {
                $.ajax({
                    type: 'POST',
                    url: 'updateDailyActivity.php',
                    data: {
                        fechaEdit: $("#fechaEdit").attr("value"),
                        nombreTecnicoEdit: $("#nombreTecnicoEdit").attr("value"),
                        tareaRealizadaEdit: document.getElementById("tareaRealizadaEdit").value,
                        observacionesEdit: document.getElementById("observacionesEdit").value,
                        id_act: id
                    },
                    success: function(data) {
                        document.getElementById('id01').style.display = 'none';
                        location.reload();

                    },
                    error: function(data) {
                        alert("ERROR: " + data);
                    }
                });
            }
        </script>
        <!-- Fin Modal de edicion -->
        <hr>

        <div style="width: 100%; display: flex; justify-content: space-around;">
            <div class="table-wrap" style="float: left; width: 45%;">
                <h1 class="h1est">Exportar pdf</h1>
                <form action="reportesDiarios/pdf_report_daily2.php" target="_blank" method="POST">
                    <div class="form-group">
                        <input class="form-control" style="width: 90%;" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte" id="fechaReporte">
                    </div>
                    <div class="form-group">
                        <input class="form-control" style="width: 90%;" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte2" id="fechaReporte">
                    </div>

                    <div class="form-group">
                        <select class="form-control" style="width: 90%;" name="consTecnico" id="consTec">
                            <option value="%">Todos los técnicos</option>
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

                    <div class="form-group">
                        <select class="form-control" style="width: 90%;" name="consCliente" id="consCliente">
                            <option value="%">Todos los clientes</option>
                            <?php
                            $query = "SELECT * FROM hesk_customers";
                            $res = hesk_dbQuery($query);
                            while ($reg = hesk_dbFetchAssoc($res)) {
                            ?>
                                <option value="<?php echo $reg['nombre'] ?>"><?php echo $reg['nombre'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select style="width: 90%;" name="consCategoria" id="consCategoria" class="form-control">
                            <?php
                            $todas = "";
                            $query1 = "SELECT id FROM hesk_categories;";
                            $res1 = hesk_dbQuery($query1);
                            while ($reg1 = hesk_dbFetchAssoc($res1)) {
                                $todas = $todas . "," . $reg1['id'];
                            }
                            ?>
                            <option value="(0<?php echo $todas ?>)">Todas las categorías </option>
                            <?php
                            $query = "SELECT * FROM hesk_categories";
                            $res = hesk_dbQuery($query);
                            while ($reg = hesk_dbFetchAssoc($res)) {
                            ?>
                                <option value="(<?php echo $reg['id'] ?>)"><?php echo $reg['name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" value="exportar" class="btnb btnb-info" id="fechaR">
                </form>
            </div>

            <div class="table-wrap" style="float: left; width: 45%;">
                <h1 class="h1est">Exportar excel</h1>
                <form action="reportesDiarios/excel_report_daily.php" target="_blank" method="POST">
                    <div class="form-group">
                        <input class="form-control" style="width: 90%;" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte" id="fechaReporte">
                    </div>
                    <div class="form-group">
                        <input class="form-control" style="width: 90%;" type="date" value="<?php echo date("Y-m-d") ?>" name="fechaReporte2" id="fechaReporte">
                    </div>

                    <div class="form-group">
                        <select class="form-control" style="width: 90%;" name="consTecnico" id="consTec">
                            <option value="%">Todos los técnicos</option>
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

                    <div class="form-group">
                        <select class="form-control" style="width: 90%;" name="consCliente" id="consCliente">
                            <option value="%">Todos los clientes</option>
                            <?php
                            $query = "SELECT * FROM hesk_customers";
                            $res = hesk_dbQuery($query);
                            while ($reg = hesk_dbFetchAssoc($res)) {
                            ?>
                                <option value="<?php echo $reg['nombre'] ?>"><?php echo $reg['nombre'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select style="width: 90%;" name="consCategoria" id="consCategoria" class="form-control">
                            <?php
                            $todas = "";
                            $query1 = "SELECT id FROM hesk_categories;";
                            $res1 = hesk_dbQuery($query1);
                            while ($reg1 = hesk_dbFetchAssoc($res1)) {
                                $todas = $todas . "," . $reg1['id'];
                            }
                            ?>
                            <option value="(0<?php echo $todas ?>)">Todas las categorías </option>
                            <?php
                            $query = "SELECT * FROM hesk_categories";
                            $res = hesk_dbQuery($query);
                            while ($reg = hesk_dbFetchAssoc($res)) {
                            ?>
                                <option value="(<?php echo $reg['id'] ?>)"><?php echo $reg['name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" value="exportar" class="btnb btnb-info" id="fechaR">
                </form>
            </div>
        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    function eliminarActividad(ids) {

        Swal.fire({
            title: '¿Estás seguro de eliminar esta actividad?',
            timer: 10000,
            timerProgressBar: true,
            icon: 'warning',
            iconColor: 'red',
            showCancelButton: true,
            confirmButtonText: 'Si',
            confirmButtonColor: 'red',
            cancelButtonText: `Cancelar`,
            allowEscapeKey: true,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'eliminarActividad.php',
                    data: {
                        del_id: ids
                    },
                    success: function(data) {
                        $("#row" + ids).css("display", "none");
                        Swal.fire({
                            title: 'Eliminado!',
                            icon: 'info', 
                            timer: 1000,
                            timerProgressBar: true
                        })
                    }
                });

            }
        })

        /*
        
        */
    }

    function registarDiario() {
        if (validar()) {
            $.ajax({
                type: 'POST',
                url: 'insertRegistroDiario.php',
                data: $('#formDailyActivity').serialize(),
                success: function(data) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Actividad Registrada',
                        willClose: () => {
                            location.reload();
                        }
                    })
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
            },
            "order": [
                [0, "desc"],
                [1, "desc"]
            ]
        });
    });
</script>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>