<?php
namespace YABA;

require_once('includes/user.inc.php');
require_once('includes/files.inc.php');

$error = false;
$error_msg = "";

if(array_key_exists('logout', $_GET)) {
  session_start();
  session_unset();
  session_destroy();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $result = login($_POST['email_address'], $_POST['password']);
  if($result) {
    session_start();
    $_SESSION = get_session_array($result);
    session_write_close();
    header('Location: index.php');
  }
  else {
    $error = true;
    $error_msg = "Invalid username and/or password";
  }
}
$page_vars = ['error' => $error, '$error_msg' => $error_msg];
load_page('login', $page_vars);