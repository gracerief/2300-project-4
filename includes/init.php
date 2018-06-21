<?php
$db = open_or_init_sqlite_db("data.sqlite", "init/init.sql");

// An array to deliver messages to the user.
$messages = array();

// Record a message to display to the user.
function record_message($message) {
  global $messages;
  array_push($messages, $message);
}

// Write out any messages to the user.
function print_messages() {
  global $messages;
  foreach ($messages as $message) {
    if (substr($message, -1) == "*") {
      echo "<p class='userMessage loginRep'>" . substr(htmlspecialchars($message),0,-1) . "</p>\n";
    } else {
      echo "<p class='userMessage'>" . htmlspecialchars($message) . "</p>\n";
    }
  }
}

// inspired by project 3 check login function
function check_login() {
  if (isset($_SESSION['current_user'])) {
    return $_SESSION['current_user'];
  }
  return NULL;
}

function log_out() {
  global $current_user;
  $current_user = NULL;
  unset($_SESSION['current_user']);
  session_destroy();
}

// show database errors during development.
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

// execute an SQL query and return the results, inspired from project 3 exec sql function
function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

// open connection to database, inspired from project 3 open db function
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        handle_db_error($exception);
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

function log_in($username,$password) {
  // inspired from project 3 login function
  global $db;
  if ($username && $password) {
    $q = "SELECT * FROM accounts WHERE username = :username;";
    $par = array(
      ':username' => $username
    );
    $records = exec_sql_query($db,$q,$par)->fetchAll();
    if ($records) {
      $account = $records[0];
      if ( password_verify($password, $account['password']) ) {
        session_regenerate_id();
        $_SESSION['current_user'] = $username;
        record_message("Logged in as $username.");
        return $username;
      } else {
        record_message("Error 1: Username or password is invalid.");
      }
    } else {
      record_message("Error 2: Username or password is invalid.");
    }
  } else {
    record_message("Error 3: Username or password is invalid.");
  }
  return NULL;
}

session_start();
if (isset($_POST['login'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $current_user = log_in($username, $password);
} else {
  $current_user = check_login();
}


if (check_login() == NULL) {
  $pages = array(
    "index" => "Home",
    "calendar" => "Calendar",
    "musicsheets" => "Music Sheets",
    "login" => "Log In",
  );
} else {
  $pages = array(
    "index" => "Home",
    "calendar" => "Calendar",
    "musicsheets" => "Music Sheets",
    "logout" => "Log Out"
  );
}


?>
