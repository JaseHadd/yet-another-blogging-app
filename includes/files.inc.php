<?php
namespace YABA;

require_once('includes/includes.inc.php');

function load_object($file) {
  $file_path = sprintf(OBJECT_PATH, $file);
  $file_contents = file_get_contents($file_path);
  return unserialize($file_contents);
}

function save_object($file, $data) {
  $file_path = sprintf(OBJECT_PATH, $file);
  $file_contents = serialize($data);
  file_put_contents($file_path, $file_contents);
}

function load_page($file_name, $vars = []) {
  if(!isset($_SESSION)) {
    session_start();
    session_write_close();
  }
  $pages = ['header', 'nav', $file_name, 'footer'];
  $vars['config'] = load_object('blog');
  $vars['user'] = $_SESSION;
  foreach($pages as $page) {
    $file_path = sprintf(PAGE_PATH, $page);
    require($file_path);
  } 
}
?>
