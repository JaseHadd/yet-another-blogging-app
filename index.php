<?php
namespace YABA;

if(!file_exists('config/')) {
  header('Location: setup/index.php?page=1');
}

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');
require_once('includes/user.inc.php');

$config = load_object('blog');

load_page('main');

?>
