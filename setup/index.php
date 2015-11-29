<?php
namespace YABA\Setup;
/* TODO: allow user to create database in setup script */

// use the first page if not specified
$page = array_key_exists('page', $_GET) ? $_GET['page'] : '1';
$error = FALSE
$error_message = "";

switch($_GET['page']) {
  case '1':
    directory_setup();
  case '2':
    connection_setup();
}

function directory_setup() {
  if(!(file_exists("config") || mkdir("config", $mode 0700))) {
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
  if(!array_key_exists('submit', $_POST)) {
    print_page("setup_connection.html");
    exit();
  }
  
  $config_file = "<?php\n\$db_driver={$_POST['db_driver']};\n\$db_host={$_POST['db_host']};\n\$db_database={$_POST['db_database']};\n\$db_username={$_POST['db_username']};\n\$db_password={$_POST['db_password']};\n?>\n";
  file_put_contents('../config/db.inc.php', $config_file);
}

function print_page($page) {
  include("pages/setup_header.html");
  include($page);
  include("pages/setup_footer.html");
}

function set_error($message) {
  $error = TRUE
  $error_message = $message
}
?>
