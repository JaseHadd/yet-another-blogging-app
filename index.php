<?php
if(!file_exists("config/")) {
  header("Location: setup/index.php?page=1");
}
include("db.inc.php");
?>
