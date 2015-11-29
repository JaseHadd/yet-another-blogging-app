<?php
if(!file_exists("config/")) {
  header("Location: setup.php");
}
include("db.inc.php");
?>
