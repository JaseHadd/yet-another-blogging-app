<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function get_posts($number, $from) {
  $config = load_object('db');
  $link = database_connect();
  
  $query = "SELECT *
              FROM {$config->prefix}posts
              ORDER BY created_time DESC
              LIMIT :limit OFFSET :offset";
  $statement = $link->prepare($query);
  $statement->bindParam('limit', $number, PDO::PARAM_INT);
  $statement->bindParam('offset', $from, PDO::PARAM_INT);
  $statement->execute();
  
  $results = $statement->fetchAll(PDO::FETCH_OBJ);
  return $results;
}

function add_post($title, $text, $author, $category) {
  $config = load_object('db');
  $link = database_connect();
  
  $query = "INSERT INTO {$config->prefix}posts
              (title, body, author_id, category_id, created_time, modified_time)
              VALUES (:title, :body, :author, :category, :created, :created)";
  $statement = $link->prepare($query);
  $statement->bindParam('title', $title, PDO::PARAM_STR);
  $statement->bindParam('body', $text, PDO::PARAM_STR);
  $statement->bindParam('author', $author, PDO::PARAM_INT);
  $statement->bindParam('category', $category, PDO::PARAM_INT);
  $statement->bindParam('created', gmdate(DB_DATE_FORMAT));
  
  $statement->execute();
}