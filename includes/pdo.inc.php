<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function database_connect() {
  $db = load_object('db');
  $dsn = "{$db->driver}:host={$db->host};dbname={$db->database};charset=utf8";
  $link = new PDO($dsn, $db->username, $db->password);
  
  return $link;
}

?>
