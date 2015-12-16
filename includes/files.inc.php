<?php
namespace YABA;

define('OBJECT_PATH', 'config/%s');
define('PAGE_PATH', 'pages/%s.html');

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
  $pages = ['header', 'nav', $file_name, 'sidebar', 'footer'];
  foreach($pages as $page) {
    $file_path = sprintf(PAGE_PATH, $page);
    require($file_path);
  } 
}
?>
