<?php
require('../fpdf/fpdf.php');
define('IN_SCRIPT', 1);
define('HESK_PATH', '../');
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();
hesk_session_start();


// datos de mysql

$query = "SELECT * FROM hesk_registros_diarios";
$res = hesk_dbQuery($query);

require ("pdf_mc_table.php");


