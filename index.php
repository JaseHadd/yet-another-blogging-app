<?php
namespace YABA;

define('POSTS_PER_PAGE', 5);

if(!file_exists('config/')) {
  header('Location: setup/index.php?page=1');
}

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');
require_once('includes/user.inc.php');
require_once('includes/posts.inc.php');

$config = load_object('blog');

$page = 1;

if(array_key_exists('page', $_GET)) {
  $page = $_GET['page'];
}

load_page('main', ['page' => $page]);

?>
