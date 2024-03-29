<?php
namespace YABA;

require_once('includes/includes.inc.php');

$pages = [
  'categories' => 'Categories',
  'posts' => 'Posts',
  'newpost' => 'New Post'
];

session_start();

if(!logged_in()){
  header('Location: index.php');
  exit();
}
$page = array_key_exists('page', $_GET)?$_GET['page']:'posts';

if($_SERVER['REQUEST_METHOD'] == "GET") {
  load_page(sprintf(ADMIN_PAGE_PATH, $page), ['pages' => $pages]);
} else {
  call_user_func(sprintf(ADMIN_FUNC_PATH, $page));
}

function newpost() {
  $title = $_POST['post_title'];
  $text = $_POST['post_text'];
  $category = $_POST['post_category'];
  $author = $_SESSION['user_id'];
  
  add_post($title, $text, $category, $author);
}

?>
