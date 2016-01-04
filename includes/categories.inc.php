<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function get_categories() {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT category_id, name FROM {$config->prefix}categories";
  $statement = $link->prepare($query);
  $statement->execute();
  
  $result = $statement->fetchAll(PDO::FETCH_OBJ);
  return $result;
  
function category_name($id) {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT name FROM {$config->prefix}categories
              WHERE category_id = :id";
  $statement = $link->prepare($query);
  $statement->bind_param('id', $id, PDO::PARAM_INT);
  return $statement->fetchColumn();
}
