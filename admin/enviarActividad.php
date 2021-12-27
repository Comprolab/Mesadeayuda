<?php

define('IN_SCRIPT', 1);
define('HESK_PATH', '../');
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();
hesk_session_start();
hesk_dbConnect();

$id = $_POST['id'];

$sql = "SELECT * FROM hesk_tareas_planificadas WHERE id = $id";
$res = hesk_dbQuery($sql);


$reg = hesk_dbFetchAssoc($res);

$sql = "INSERT INTO hesk_registros_diarios
            (fecha,nombreTecnico,tareaRealizada,observaciones, cliente)
            VALUES
            (
            '$reg[fecha]',
            '$reg[nombreTecnico]',
            '$reg[tareaRealizada]',
            '$reg[observaciones]',
            $reg[cliente])";

            echo $sql;

hesk_dbQuery($sql);