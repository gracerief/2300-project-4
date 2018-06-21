<?php
include("includes/init.php");

$current_page_id = "logout";


if(isset($_POST["submit_logout"])) {
  $dologout=TRUE;
  log_out();
  record_message("You've been logged out."); ?>
  <html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
    <?php
    include("includes/header.php");
    header("Location: index.php");
  } else {
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
        <div>
          <?php include("includes/header.php");
          $dologout=FALSE;?>
        </div>
        <h2 id="subheader">Log Out</h2>
        <form  id="logout" method="post">
          <label>Are you sure you would like to Logout?    </label>
          <br><br>
          <button type="submit" name="submit_logout">Yes</button>
        </form>
        <?php
        print_messages();
      }
      ?>
    </div>
  </body>
  </html>
