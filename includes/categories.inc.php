<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function get_categories() {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT category_id, name, parent_id FROM {$config->prefix}categories";
  $statement = $link->prepare($query);
  $statement->execute();
  
  $result = $statement->fetchAll(PDO::FETCH_OBJ);
  return $result;
}

function get_category_tree() {
  $categories = get_categories();
  $tree = [];
  while(count($categories) != 0) {
    foreach($categories as $index => $category) {
      $category->children = [];
      if($category->parent_id = NULL) {
        $tree[] = $category;
        unset($categories[$index]);
      }
      else {
        foreach($tree as $parent) {
          if($parent->category_id == $category->parent_id) {
            $parent->children[] = $category;
            unset($categories[$index]);
          }
        }
      }
    }
  }
}
  
function category_name($id) {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT name FROM {$config->prefix}categories
              WHERE category_id = :id";
  $statement = $link->prepare($query);
  $statement->bindParam('id', $id, PDO::PARAM_INT);
  $statement->execute();
  
  return $statement->fetchColumn();
}
