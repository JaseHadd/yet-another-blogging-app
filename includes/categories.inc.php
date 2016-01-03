<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function get_categories() {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT category_id, name FROM {$config->prefix}categories";
  $statement = $link->prepare($query);
  $statement->bindParam('email', $email_address);
  $statement->execute();
  
  $result = $statement->fetchAll(PDO::FETCH_OBJ);
  return $result;
}