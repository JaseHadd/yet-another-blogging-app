<?php
namespace YABA;

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');

function get_posts($number, $from) {
  $config = load_object('db');
  $link = database_connect();
  
  $query = "SELECT title, post FROM {$config->prefix}posts
              LIMIT {$number} OFFSET {$from}";
  $statement = $link->prepare($query);
  $statement->bindParam('email', $email_address);
  $statement->execute();
  
  $results = $statement->fetchAll();
  return $results;
}