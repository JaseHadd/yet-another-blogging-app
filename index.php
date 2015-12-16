<?php
namespace YABA;

if(!file_exists('config/')) {
  header('Location: setup/index.php?page=1');
}

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');

$config = load_object('blog');

require('pages/header.html');
require('pages/main.html');
require('pages/nav.html');
require('pages/sidebar.html');
require('pages/footer.html');

?>
