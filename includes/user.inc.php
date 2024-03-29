<?php
namespace YABA;
use \PDO;

require_once('includes/includes.inc.php');

function logged_in() {
  return isset($_SESSION['login_id']);
}

function login($email_address, $password) {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT login_id, password FROM {$config->prefix}user_login WHERE email_address = :email LIMIT 1";
  $statement = $link->prepare($query);
  $statement->bindParam('email', $email_address);
  $statement->execute();
  
  $result = $statement->fetch(PDO::FETCH_OBJ);
  if(hash_equals($result->password, crypt($password, $result->password))) {
    return $result->login_id;
  }
  return 0;
}

function get_session_array($login_id) {
  $session = ['login_id' => $login_id];
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT first_name, last_name, display_name, user_id
              FROM {$config->prefix}user_info
              WHERE login_id = :login_id LIMIT 1";
  $statement = $link->prepare($query);
  $statement->bindParam('login_id', $login_id);
  $statement->execute();
  
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  return array_merge($session, $result);
}

function author_name($id) {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT first_name, last_name, display_name
              FROM {$config->prefix}user_info
              WHERE user_id = :id";
  $statement = $link->prepare($query);
  $statement->bindParam('id', $id, PDO::PARAM_INT);
  $statement->execute();
  
  $result = $statement->fetch(PDO::FETCH_OBJ);
  
  if(isset($result->display_name)) {
    return $result->display_name;
  }
  else return "{$result->first_name} {$result->last_name}";
  
}

?>
