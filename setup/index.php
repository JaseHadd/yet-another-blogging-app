<?php
namespace YABA\Setup;
use \PDO;
use \stdClass;
/* TODO: allow user to create database in setup script */

// use the first page if not specified
$page = array_key_exists('page', $_GET) ? $_GET['page'] : '1';
$error = FALSE;
$error_message = "";

switch($page) {
  case '1':
    directory_setup();
    break;
  case '2':
    connection_setup();
    break;
  case '3':
    table_setup();
    break;
}

function directory_setup() {
  if(!file_exists("config") && !mkdir("config", $mode = 0700)) {
    set_error("Unable to create config directory");
  }
  else if(!is_dir("config")) {
    set_error("File 'config' already exists and is not a directory");
  }
  else if(!is_writable("config")) {
    set_error("Config directory is not writable.");
  }
  print_page("setup_directory.html");
}

function connection_setup() {
  // if the form was not submitted, display it and exit
  if(!array_key_exists('submit2', $_POST)) {
    print_page("setup_connection.html");
    return;
  }
  // test the database connection
  try {
    $db_driver = $_POST['db_driver']; $db_host = $_POST['db_host']; $db_database = $_POST['db_database']; $db_username = $_POST['db_username']; $db_password = $_POST['db_password'];
    $dsn = "{$db_driver}:host={$db_host};dbname=$db_database;charset=utf8";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $db = @new PDO($dsn, $db_username, $db_password, $options);
  } catch(PDOException $ex) {
    // if the database connection throws an exception, reload the page with an error
    set_error("Unable to connect to the database.");
    print_page("setup_connection.html");
    return;
  }
  // if the database connection succeeded, we write the configuration to file and go to the next page.
  $db_config = new stdClass();
  $db_config->driver = $_POST['db_driver'];
  $db_config->host = $_POST['db_host'];
  $db_config->database = $_POST['db_database'];
  $db_config->username = $_POST['db_username'];
  $db_config->password = $_POST['db_password'];
  
  $db_config_data = serialize($db_config);
  file_put_contents('../config/db', $db_config_data);
  load_page('?page=3');
  
}

function table_setup() {
  if(!array_key_exists('submit3', $_POST)) {
    print_page('setup_tables.html');
    return;
  }
  
  // write the database prefix to the config file
  $db_config_data = file_get_contents('../config/db');
  $db_config = unserialize($db_config_data);
  $db_config->prefix = $_POST['db_prefix'];
  $db_config_data = serialize($db_config);
  file_put_contents('../config/db', $db_config_data);
  
  // and create the tables!
  $prefix = $_POST['db_prefix'];
  include('dbsetup.inc.php');
  include('../config/db.inc.php');
  try {
    $dsn = "{$db_config->driver}:host={$db_config->host};dbname=$db_config->database;charset=utf8";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $db = @new PDO($dsn, $db_config->username, $db_config->password, $options);
    
    foreach($mysql_queries as $query) {
      $st = $db->prepare($query);
      $st->execute();
    }
  } catch(PDOException $ex) {
    // if the database connection throws an exception, reload the page with an error
    set_error("There was an error inserting tables: " . $ex->getMessage());
    print_page("setup_tables");
    return;
  }
}

function print_page($page) {
  include("pages/setup_header.html");
  include("pages/{$page}");
  include("pages/setup_footer.html");
}

function set_error($message) {
  $error = TRUE;
  $error_message = $message;
}

function load_page($page) {
  header("Location: {$page}");
}
?>
