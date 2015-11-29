<?php
if(!file_exists("config/")) {
  mkdir("config", $mode = 0700) or die("Unable to create config directory, please check permissions");
}

if(!array_key_exists('submit', $_POST)) {
?>
<html>
  <head>
    <title>YABA: Setup</title>
  </head>
  <body>
    <h1>Database Setup</h1>
    <form method="post" autocomplete="off">
      <fieldset>
        <legend>Database Details:</legend>
        <label for="db_driver">Type</label>:<br />
        <select name="db_driver">
          <option value="mysql" selected>MySQL</option>
        </select><br />
        
        <label for="db_host">Address</label>:<br />
        <input type="text" name="db_host" /><br />
        
        <label for="db_database">Database</label>:<br />
        <input type="text" name="db_database" /><br />
      </fieldset>
      
      <fieldset>
        <legend>Database Credentials</legend>
        <label for="db_username">Username</label>:<br />
        <input type="text" name="db_username" /><br />
        
        <label for="db_password">Password</label>:<br />
        <input type="password" name="db_password" /><br />
        
      </fieldset>
      
      <input type="submit" value="submit" />
    </form>
  </body>
</html>
<?php
  exit();
}

$config_file = "<?php\n$db_driver={$_POST['db_driver']};\n$db_host={$_POST['db_host']};\n$db_database={$_POST['db_database']};\n$db_username={$_POST['db_username']};\n$db_password={$_POST['db_password']};\n?>\n";
file_put_contents('config/db.inc.php', $config_file);
?>
