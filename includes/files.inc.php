<?php
define(OBJECT_PATH, '../config/%s');

function load_object($file) {
  $file_path = sprintf(OBJECT_PATH, $file);
  $file_contents = file_get_contents($file_path);
  return unserialize(file_contents);
}

function save_object($file, $data) {
  $file_path = sprintf(OBJECT_PATH, $file);
  $file_contents = serialize($data);
  file_put_contents($file_path, $file_contents);
}
?>
