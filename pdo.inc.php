<?php

include_once("config/db.inc.php");
$dsn = "{$db_driver}:host={$db_host};dbname={$db_database};charset=utf8";
$db = new PDO($dsn, $db_username, $db_password);
?>
