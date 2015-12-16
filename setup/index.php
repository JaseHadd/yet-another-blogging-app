<?php
namespace YABA\Setup;
use \PDO;
use \stdClass;

define('PAGE_FILE', 'pages/setup_%s.html');
define('PAGE_FUNC', 'YABA\Setup\%s_setup');
define('CONF_FILE', '../config/%s');

define('DEFAULT_PAGE', 1);

/* TODO:  allow user to create database in setup script
          add error handling for database inserts
          perform input validation
          */

// use the first page if not specified
$page = array_key_exists('page', $_GET) ? $_GET['page'] : '1';
$error = FALSE;
$error_message = '';

$page_names = [
  1 =>  'directory',
  2 =>  'connection',
  3 =>  'tables',
  4 =>  'blog',
  5 =>  'admin'
];

$page = (int)$_GET['page'];

// if the page specified isn't defined, load the default page
if(!array_key_exists($page, $page_names)) {
  load_page(DEFAULT_PAGE);
}

// if the page was submitted, run the corresponding function
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  $func = sprintf(PAGE_FUNC, $page_names[$page]);
  if(is_callable($func)) {
    call_user_func($func);
  } else {
    error_log("YABA Setup: Unable to call function '{$func}'");
  }
} else {
  // if the page is being requested, instead of submitted, load the HTML form.
  print_page($page_names[$page]);
}

function directory_setup() {
  if(!file_exists("config") && !mkdir("config", $mode = 0700)) {
    set_error('Unable to create config directory');
  }
  else if(!is_dir("config")) {
    set_error('A file named config already exists and is not a directory');
  }
  else if(!is_writable("config")) {
    set_error('Config directory is not writable.');
  }
  print_page('setup_directory');
}

function connection_setup() {
  // if the form was not submitted, display it and exit
  
  // test the database connection
  try {
    $db_driver = $_POST['db_driver']; $db_host = $_POST['db_host']; $db_database = $_POST['db_database']; $db_username = $_POST['db_username']; $db_password = $_POST['db_password'];
    $dsn = "{$db_driver}:host={$db_host};dbname={$db_database};charset=utf8";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $db = @new PDO($dsn, $db_username, $db_password, $options);
  } catch(PDOException $ex) {
    // if the database connection throws an exception, reload the page with an error
    set_error('Unable to connect to the database.');
    print_page('setup_connection');
    return;
  }
  // if the database connection succeeded, we write the configuration to file and go to the next page.
  $db_config = new stdClass();
  $db_config->driver = $_POST['db_driver'];
  $db_config->host = $_POST['db_host'];
  $db_config->database = $_POST['db_database'];
  $db_config->username = $_POST['db_username'];
  $db_config->password = $_POST['db_password'];
  
  save_object('db', $db_config);
  load_page(3);
}

function tables_setup() {
  // write the database prefix to the config file
  $db_config = load_object('db');
  $db_config->prefix = $_POST['db_prefix'];
  save_object('db', $db_config);
  
  // and create the tables!
  $prefix = $_POST['db_prefix'];
  include('dbsetup.inc.php');
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
    set_error("There was an error inserting tables: {$ex->getMessage()}");
    print_page('setup_tables');
    return;
  }
  
  load_page(4);
}

function blog_setup() {
  $blog_config = new stdClass();
  $blog_config -> title     = $_POST['blog_title'];
  $blog_config -> headline  = $_POST['blog_headline'];
  
  save_object('blog', $blog_config);
}

function admin_setup() {
  $db_config = load_object('db');
  
  $user_login_query = "
  INSERT INTO {$db_config->prefix}user_login(email_address, password) 
    VALUES(:email, :hash)";
  $user_info_query = "
  INSERT INTO {$db_config->prefix}user_info(login_id, first_name, last_name, display_name)
    VALUES(:login_id, :first_name, :last_name, :display_name)";
  $dsn = "{$db_config->driver}:host={$db_config->host};dbname=$db_config->database;charset=utf8";
  $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
  $db = @new PDO($dsn, $db_config->username, $db_config->password, $options);
  
  $cost = 10;
  $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_RANDOM)), '+', '-');
  $salt = sprintf('$2y$%02d$', $cost) . $salt;
  $hash = crypt($_POST['admin_password'], $salt);
  
  $statement = $db->prepare($user_login_query);
  $statement->execute(array(
    'email' => $_POST['admin_email'],
    'hash' => $hash));
  
  $statement = $db->prepare($user_info_query);
  $statement->execute(array(
    'login_id' => $db->lastInsertId(),
    'first_name' => $_POST['admin_first_name'],
    'last_name' => $_POST['admin_last_name'],
    'display_name' => $_POST['admin_display_name']));
    
  load_index();
  
}

function print_page($page) {
  global $error, $error_message;
  
  $header = sprintf(PAGE_FILE, 'header');
  $file = sprintf(PAGE_FILE, $page);
  $footer = sprintf(PAGE_FILE, 'footer');
  
  include($header);
  include($file);
  include($footer);
}

function set_error($message) {
  $error = TRUE;
  $error_message = $message;
}

function load_page($page) {
  $path = $_SERVER['PHP_SELF'] . "?page={$page}";
  header("Location: {$path}");
}

function load_index() {
  header("Location: {dirname(dirname(__FILE__))}");
}

function load_object($file) {
  $file_path = sprintf(CONF_FILE, $file);
  $file_data = file_get_contents($file_path);
  return unserialize($file_data);
}

function save_object($file, $object) {
  $file_path = sprintf(CONF_FILE, $file);
  $file_data = serialize($object);
  file_put_contents($file_path, $file_data);
}

?>
