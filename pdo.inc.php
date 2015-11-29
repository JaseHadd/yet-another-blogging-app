<?php

include_once("config/db.inc.php");
$db = new PDO("{$db_driver}:host={$db_host};dbname=db_database;charset=utf8", $db_username, $db_password);
?>
