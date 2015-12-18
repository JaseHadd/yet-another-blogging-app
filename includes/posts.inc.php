<?php
namespace YABA;
use \PDO;

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');

function get_posts($number, $from) {
  $config = load_object('db');
  $link = database_connect();
  
  $query = "SELECT title, post FROM {$config->prefix}posts
              LIMIT :limit OFFSET :offset";
  $statement = $link->prepare($query);
  $statement->bindParam('limit', $number, PDO::PARAM_INT);
  $statement->bindParam('offset', $from, PDO::PARAM_INT);
  $statement->execute();
  
  $results = $statement->fetchAll();
  return $results;
}