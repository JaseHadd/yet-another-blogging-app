<?php
namespace YABA;

if(!file_exists('config/')) {
  header("Location: setup/index.php?page=1");
}

require_once('db.inc.php');
?>
