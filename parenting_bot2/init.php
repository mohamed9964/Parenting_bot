<?php
// Error Reporting
ini_set('display_errors', 'On'); // set option to ini.php
error_reporting(E_ALL);
include 'admin/connect.php';

// Routes

$tpl='includes/templates/'; // Template Directory
$func= 'includes/functions/'; // Functions Directory

// Include The Important Files


$tpl='includes/templates/'; // Template Directory
include $tpl . 'header.php';
include $func . 'functions.php';
