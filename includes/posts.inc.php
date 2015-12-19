<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function get_posts($number, $from) {
  $config = load_object('db');
  $link = database_connect();
  
  $query = "SELECT title, body FROM {$config->prefix}posts
              LIMIT :limit OFFSET :offset";
  $statement = $link->prepare($query);
  $statement->bindParam('limit', $number, PDO::PARAM_INT);
  $statement->bindParam('offset', $from, PDO::PARAM_INT);
  $statement->execute();
  
  $results = $statement->fetchAll(PDO::FETCH_OBJ);
  return $results;
}