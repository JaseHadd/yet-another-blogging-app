<?php
namespace YABA;

require_once('includes/includes.inc.php');

session_start();

if(!logged_in()){
  header('Location: index.php');
  exit();
}
if($_SERVER['REQUEST_TYPE'] == "GET") {
  load_page('newpost');
} else {
  /* post stuff here */
}
?>
