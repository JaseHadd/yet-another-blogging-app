<?php
namespace YABA;
use \PDO;

include_once('files.inc.php');

function database_connect() {
  $db = load_object('db');
  $dsn = "{$db->db_driver}:host={$db->db_host};dbname={$db->db_database};charset=utf8";
  $link = new PDO($dsn, $db_username, $db_password);
  
  return $link;
}

?>
