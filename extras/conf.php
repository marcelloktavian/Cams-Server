<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
define('_HOST', 'localhost');
define('_USER', 'root');
define('_PASS', '');
define('_DBSE', 'cams_db2022');

define('BASE_URL', "http://$_SERVER[SERVER_NAME]/cams/");

$db = new PDO('mysql:host=' . _HOST . ';dbname=' . _DBSE . ';charset=utf8', _USER, _PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
