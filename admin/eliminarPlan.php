<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../');
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();
hesk_session_start();
hesk_dbConnect();

$query = "DELETE FROM hesk_tareas_planificadas WHERE id = '$_POST[del_id]'";
echo $query;
hesk_dbQuery($query);