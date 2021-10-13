<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../');
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();
hesk_session_start();
hesk_dbConnect();


$query = "INSERT INTO hesk_registros_diarios (fecha, nombreTecnico, tareaRealizada, observaciones) VALUES ('$_POST[fecha]','$_POST[nombreTecnico]','$_POST[tareaRealizada]','$_POST[observaciones]')";
echo $query;
hesk_dbQuery($query);
