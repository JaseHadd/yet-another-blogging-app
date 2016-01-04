<?php
namespace YABA;

require_once('includes/includes.inc.php');

if(!file_exists('config/')) {
  header('Location: setup/index.php?page=1');
}

$config = load_object('blog');

$page = 1;

if(array_key_exists('page', $_GET)) {
  $page = $_GET['page'];
}

load_page('main', ['page' => $page, 'parser' => new \Parsedown()]);

?>
