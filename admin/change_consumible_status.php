<script>alert("NOOOOOOOOO")</script>
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

    $idconsu = $_POST['idC'];

    $consultaCons = "SELECT * FROM hesk_consumibles WHERE id=$idconsu";
    $resCons = hesk_dbQuery($consultaCons);
    $regCons = hesk_dbFetchAssoc($resCons);

    hesk_dbQuery("UPDATE hesk_consumibles SET estado = !$regCons[estado] WHERE id = $regCons[id]");