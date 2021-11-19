<?php 

define('IN_SCRIPT', 1);
define('HESK_PATH', '../');
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();
hesk_session_start();
hesk_dbConnect();


$query = "UPDATE hesk_registros_diarios 
SET fecha = '$_POST[fechaEdit]',
nombreTecnico = '$_POST[nombreTecnicoEdit]',
tareaRealizada = '$_POST[tareaRealizadaEdit]',
observaciones = '$_POST[observacionesEdit]'
WHERE id = $_POST[id_act]";
echo $query;
hesk_dbQuery($query);


?>