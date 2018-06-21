<?php
include("includes/init.php");

$current_page_id = "login";
$db=open_or_init_sqlite_db('data.sqlite','init/init.sql');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

  <title>Log In</title>
</head>

<body>
  <div class="pageContent">
    <?php include("includes/header.php");?>

    <?php
    print_messages();
    ?>
    <?php if ($current_user) {
      echo NULL;
    } else {
    ?>
    <br/><br/>
    <form id="logform" action="login.php" method="post">
      <label>Username:</label>
      <input type="text" name="username" required/>
      <br>
      <br>
      <label>Password:</label>
      <input type="password" name="password" required/>
      <br>
      <br>
      <button name="login" type="submit">Log In</button>
    </form>
  <?php } ?>
  </div>
  <br/>
  <?php include("includes/footer.php");?>
</body>

</html>
