<?php
namespace YABA;

require_once('includes/pdo.inc.php');
require_once('includes/files.inc.php');

function logged_in() {
  return isset($_SESSION['user_id']);
}

function login($email_address, $password) {
  $config = load_object('db');
  $link = database_connect();
  $query = "SELECT login_id, password FROM {$db->prefix}user_login WHERE email_address = :email LIMIT 1";
  $statement = $link->prepare($query);
  $statement->bindParam(':email', $email_address);
  $statement->execute();
  
  $result = $statement->fetch(PDO::FETCH_OBJ);
  if(hash_equals($result->password, crypt($password, $result->password))) {
    return $result->login_id;
  }
  return 0;
  
}

?>
