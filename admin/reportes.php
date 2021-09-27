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

// agregar

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$ingeniero = $_POST['ingeniero'];


// al presionar en botón de crear
if (isset($_POST['Crear'])) {
    // condición para confirmar que no estén vacios los campos
    // if (isset($_POST['id']) && isset($_POST['nombre'])) {

        // condicion para saber si hay un ingeniero al que se le vaya a asignar la zona
        // if (isset($_POST['ingeniero'])) {

            hesk_dbQuery("INSERT INTO
    
            hesk_zones (
                nombre
            )
            VALUES (
                '$nombre'
            )
            
            ");
        // } else {
        //     hesk_dbQuery("INSERT INTO
    
        //     hesk_zones (
        //         codigo_zona,
        //         nombre
        //     )
        //     VALUES (
        //         '$id',
        //         '$nombre'
        //     )
            
        //     ");
        }
    // } else {
    //     echo "Por favor ingrese todos los datos";
    // }
// }


?>
<!-- Inicio de formularios -->
<div style="margin-left: 10px;" class="main__content settings">

    <h1 class="h1est">Agregar</h1>
    <div  class="table-wrap">

        <form  action="add_zone.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" onsubmit='return validar()'>

        <div class="form-group">
                    <label>
                        <span>Tipo de reporte </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                               
                            </div>
                        </a>
                        <div class="form-group">
                <select name="nombreReporte" id="nombreReporte" class="form-control" style="border: 90%;">}
                <option value="value1">Reporte de servicio</option>
             
                </select>
                    </label>
                   
                </div>

                <div class="form-group">
                    <label>
                        <span>Asignado </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <select name="asignado" id="asignado" class="form-control" style="border: 90%;">}
       
                  <?php
                        $sql = "SELECT id,name FROM hesk_users  WHERE  rol = 1   " ;
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
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <input id="fechaInicio" type="date" class="form-control" name="fechaInicio" maxlength="255" >
                </div>

                <div class="form-group">
                    <label>
                        <span>Fecha final </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                
                            </div>
                        </a>
                    </label>
                    <input type="date" class="form-control" name="fechaFinal" maxlength="255" value="">
                </div>

                <div class="form-group">
                    <label>
                        <span>Estado de servicio </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">

                            </div>
                        </a>
                    </label>
                    <select name="estado" id="estado" class="form-control" style="border: 90%;">}
                <option value="value1">Realizado</option>
                <option value="value1">Pendiente </option>
                <option value="value1">Aplazado </option>
                <option value="value1">En operacion </option>    
                <option value="value1">Planeado</option>
                </select>
                </div>

                <div class="form-group">
                    <label>
                        <span>Localizacion</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                               
                            </div>
                        </a>
                    </label>
                    <select name="localizacion" id="localizacion" class="form-control">
                    <?php 
                        $sql = "SELECT id,nombre FROM hesk_zones";
                        $res = hesk_dbQuery($sql);
                        echo "<option disabled='true' value='0' selected></option>";
                        echo "<option disabled='true' value='0' selected></option>";
                        while ($reg = hesk_dbFetchAssoc($res)) {
                            echo "<option value='$reg[id]'>$reg[nombre]</option>";
                        }
                    ?>
                </select>
                </div>

                <div class="form-group">
                    <label>
                        <span>Descripcion de servicio</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                              
                            </div>
                        </a>
                    </label>
                   
                    <textarea id="descripcion" style="width: 100%;" type="textarea" class="form-control" name="descripcion" maxlength="255" value="" ></textarea>
                </div>


                <div class="form-group">
                    <label>
                        <span>Estado de equipo</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                               
                            </div>
                        </a>
                    </label>
                    <textarea style="width: 100%;" type="textarea" class="form-control" id="estadoEquipo" name="estadoEquipo" maxlength="255" value="" ></textarea>
                </div>

                
                <div class="form-group">
                    <label>
                        <span>Recomendacion</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <textarea style="width: 100%;" type="textarea" class="form-control" id="recomendacion" name="recomendacion" maxlength="255" value="" ></textarea>
                </div>


                <div class="form-group">
                    <label>
                        <span>Forma de pago </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                               
                            </div>
                        </a>
                    </label>
                    <select id="formaPago" name="formaPago" id="formaPago" class="form-control" style="border: 90%;">
                    <option value="value1"></option>
                <option value="value1">Comodato</option>
                <option value="value1">Credito</option>
                <option value="value1">Efectivo </option>
                <option value="value1">Devito </option>    
          
                </select>
                </div>


                <div class="form-group">
                    <label>
                        <span>Profecional</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <select id="profecional" name="profecional" id="zona" class="form-control" style="border: 90%;">}
       
                  <?php
                        $sql = "SELECT id,name FROM hesk_users  WHERE  rol = 1   " ;
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
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <select name="cargo" id="cargo" class="form-control" style="border: 90%;">
                    <option value="value1"></option>
                <option value="value1">Ingeniero de sistemas </option>
                <option value="value1">Tecnico de servicio</option>
                <option value="value1">Ingeniero electrico </option>
                <option value="value1">Ingeniero de zona  </option>    
                <option value="value1">Bacteriologo </option> 
          
                </select>
                </div>



                <div class="form-group">
                    <label>
                        <span>Nombre del cliente </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <select name="nombreCliente" id="nombreCliente" class="form-control">
                    <option disabled selected value="0">Cliente</option>
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
                        <span>Direccion</span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <input type="text" class="form-control" id="direccion" name="direccion" maxlength="255" value="">
                </div>


                <div class="form-group">
                    <label>
                        <span>Numero de telefono del cliente </span>
                        <a onclick="hesk_window('<?php echo $help_folder; ?>general.html#1','400','500')">
                            <div class="tooltype right">
                                <svg class="icon icon-info">
                                    <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                                </svg>
                            </div>
                        </a>
                    </label>
                    <input type="text" class="form-control" id="numero" name="numero" maxlength="255" value="">
                </div>


                

                


            <input name="Crear " type="submit" value="Crear Reporte" class="btn btn-full">
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
<script type="text/javascript" src="../js/validation.js"> </script>