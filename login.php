<?php
namespace YABA;

require_once('includes/user.inc.php');
require_once('includes/files.inc.php');

$error = false;
$error_msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(login($_POST['email_address'], $_POST['password'])) {
    $_SESSION = get_session_array();
    header('Location: index.php');
  }
  else {
    $error = true;
    $error_msg = "Invalid username and/or password";
  }
}
load_page('login', ['error' => $error, '$error_msg' => $error_msg]);