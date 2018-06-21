<?php include('includes/init.php');

$current_page_id = "musicsheets";

$db = open_or_init_sqlite_db('data.sqlite', "init/init.sql");
const MAX_FILE_SIZE = 1500000;
const UPLOADS_PATH = "uploads/sheets/";

if(isset($_POST["add_music"])) {
  $sheetID = filter_var($_POST['add_music'], FILTER_SANITIZE_STRING);

  $sqlCheck = "SELECT * FROM account_sheets WHERE account_name = :currentUser AND sheet_id = :sheetID";
  $paramsCheck = array(
    ":currentUser" => $current_user,
    ":sheetID" => $sheetID
  );
  $resultCheck = exec_sql_query($db, $sqlCheck, $paramsCheck)->fetchAll();

  if (count($resultCheck) > 0 && count($resultCheck[0]) > 0) {
    array_push($messages, "You've already added this piece! Add something new!");
  } else {
    $sql = "INSERT INTO account_sheets (account_name, sheet_id) VALUES (:account_name, :sheet_id)";
    $params = array(
      ":account_name" => $current_user,
      ":sheet_id" => $sheetID
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Added this piece successfully!");
    } else {
      array_push($messages, "That didn't work. Try again!");
    }
  }
}

if(isset($_POST["remove_music"])) {
  $sheetID = filter_var($_POST['remove_music'], FILTER_SANITIZE_STRING);

  $sqlCheck = "SELECT * FROM account_sheets WHERE account_name = :currentUser AND sheet_id = :sheetID";
  $paramsCheck = array(
    ":currentUser" => $current_user,
    ":sheetID" => $sheetID
  );
  $resultCheck = exec_sql_query($db, $sqlCheck, $paramsCheck)->fetchAll();

  if (count($resultCheck) == 0) {
    array_push($messages, "You haven't added this piece. Nothing to delete!");
  } else {
    $sql = "DELETE FROM account_sheets WHERE account_name = :account_name AND sheet_id = :sheet_id";
    $params = array(
      ":account_name" => $current_user,
      ":sheet_id" => $sheetID
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Deleted this event successfully!");
    } else {
      array_push($messages, "That didn't work. Try again!");
    }
  }
}

if (isset($_POST["submit_upload"])) {
  $upload_title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $upload_artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_STRING);
  $upload_source = filter_input(INPUT_POST, 'source', FILTER_SANITIZE_STRING);
  $upload_file = $_FILES['sheet_upload'];

  $passesAllInputs = TRUE;

  if ($upload_file['error'] == UPLOAD_ERR_NO_FILE) {
    array_push($messages, "Failed to upload file because you didn't submit one!");
    $passesAllInputs = FALSE;
  }

  if ($upload_file['error'] == UPLOAD_ERR_INI_SIZE) {
    array_push($messages, "Failed to upload file because the file was too large!");
    $passesAllInputs = FALSE;
  }

  if ($upload_source == "") {
    array_push($messages, "You must include a source!");
    $passesAllInputs = FALSE;
  }

  if ($upload_title == "") {
    array_push($messages, "You must include a title!");
    $passesAllInputs = FALSE;
  }

  $upload_name = basename($upload_file["name"]);
  $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) );
  if ($upload_ext != "pdf") {
    array_push($messages, "Please upload a PDF file!");
    $passesAllInputs = FALSE;
  }


  if ($passesAllInputs) {
    if ($upload_file['error'] == UPLOAD_ERR_OK) {
      $sql = "INSERT INTO sheets (filename, file_ext, title, artist, source) VALUES (:filename, :file_ext, :title, :artist, :source)";
      $params = array(
        ':filename' => $upload_name,
        ':file_ext' => $upload_ext,
        ':title' => $upload_title,
        ':artist' => $upload_artist,
        ':source' => $upload_source
      );
      $result = exec_sql_query($db, $sql, $params);
      $file_id = $db->lastInsertId("id");

      $newFileName = $file_id . "." . $upload_ext;
      $sqlUpdateID = "UPDATE sheets SET filename = :filename WHERE id = :id";
      $paramsUpdateID = array(
        ":filename" => $newFileName,
        ":id" => $file_id
      );
      $resultUpdate = exec_sql_query($db, $sqlUpdateID, $paramsUpdateID);

      if (move_uploaded_file($upload_file["tmp_name"], UPLOADS_PATH . "$file_id.$upload_ext")){
        $result3 = TRUE;
      } else {
        $result3 = FALSE;
      }

      if ($result && $resultUpdate && $result3) {
        array_push($messages, "Successfully uploaded!");
      } else {
        array_push($messages, "That didn't work. Try again!");
      }
    }
  }
}

if (isset($_POST["delete_sheets"])) {
  $sheetID = $_POST["delete_sheets"];

  //delete from sheets database
  $sqlDelete1 = "DELETE FROM sheets WHERE id = :sheetID";
  $paramsDelete1 = array(
    ":sheetID" => $sheetID
  );
  $result1 = exec_sql_query($db, $sqlDelete1, $paramsDelete1);

  //delete from account_sheets database
  $sqlDelete2 = "DELETE FROM account_sheets WHERE sheet_id = :sheetID";
  $paramsDelete2 = array(
    ":sheetID" => $sheetID
  );
  $result2 = exec_sql_query($db, $sqlDelete2, $paramsDelete2);

  if ($result1 && $result2) {
    array_push($messages, "Successfully deleted!");
  } else {
    array_push($messages, "Something went wrong. Try again!");
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Music Sheets</title>
</head>

<body>
<div class="pageContent">
<?php include("includes/header.php");?>
<?php print_messages() ?>

<?php
if ($current_user) {
  echo (
    "<div id='favorite_sheets'>
      <h2>Favorite Sheets</h2>"
  );

  $sql = "SELECT sheets.title, sheets.artist, sheets.filename FROM sheets INNER JOIN account_sheets ON sheets.id = account_sheets.sheet_id WHERE account_sheets.account_name = :account_name";
  $params = array(
    ":account_name" => $current_user
  );
  $faveSongs = exec_sql_query($db, $sql, $params)->fetchAll();

  if (isset($faveSongs) and !empty($faveSongs)){
    echo "<div class='faveSong'>";
    foreach($faveSongs as $song){
      if($song['artist'] != null) {
        echo "<a href= " . UPLOADS_PATH . $song['filename'] . ">" . "\"" . $song['title'] . "\", " . $song['artist'] . "</a>";
      } else {
        echo "<a href= " . UPLOADS_PATH . $song['filename'] . ">" . "\"" . $song['title'] . "\"" . "</a>";
      }
    }
    echo "</div>";
  } else {
    echo "<p>You haven't saved any yet!</p>";
  }

}
?>

<?php
$isAdmin = false;

$sql = "SELECT admin_status FROM accounts WHERE username = :username";
$params = array(
  ":username" => $current_user
);
$res = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_COLUMN);

if (count($res) > 0 && $res[0] == 'yes') {$isAdmin = true;}

if ($isAdmin) {
?>
<div id="addSheetsSection">
  <h2>Add Sheets</h2>

  <form action="musicsheets.php" method="post" enctype="multipart/form-data">
    <div class="formSection">
      Title: <input type="text" name="title">
    </div>
    <div class="formSection">
      Artist: <input type="text" name="artist">
    </div>
    <div class="formSection">
      Source: <input type="text" name="source">
    </div>
    <div class="formSection">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
      Upload File: <input type="file" name="sheet_upload">
    </div>
    <div class="formSection">
      <button class="personalEventButton greenButton" name="submit_upload" type="submit">Upload</button>
    </div>
  </form>

</div>
</div>
<?php
} else {
  echo "</div>";
}
?>

<?php
  if ($current_user) {
    echo "<div id='side_sheets_list'>";
  } else {
    echo "<div id='sheets_list'>";
  }
?>
  <?php
  $sql = "SELECT * FROM sheets;";
    $params = array();
    $records= exec_sql_query($db,$sql, $params)->fetchAll();
    if (isset($records) and !empty($records)){
      foreach($records as $record){
        ?>
        <?php
          if ($current_user) {
            echo '<div class="sheet_disp sideDisp">';
          } else {
            echo '<div class="sheet_disp floatLeftDisp">';
          }
        ?>
        <?php  ?>
        <h3><a href="<?php echo(UPLOADS_PATH . $record["id"] . "." . $record["file_ext"]);?>">"<?php echo $record["title"];?>"</a></h3>
          <?php
          if (isset($record["artist"])) {
          ?>
          <h5>Artist: <?php echo $record["artist"]; ?></h5>
        <?php
      }
      ?>
      <h6>Source: <?php echo $record['source']?></h6>

      <?php
      if ($current_user) {
        echo "<form action='musicsheets.php' method='post'>
                <button class='faveMusicButton' name='add_music' type='submit' value='" . $record['id'] . "'>Add to Favorites</button>
                <button class='personalEventButton redButton' name='remove_music' type='submit' value='" . $record['id'] . "'>Remove From Favorites</button>";

        if ($isAdmin) {
          echo "<button class='personalEventButton redButton' name='delete_sheets' type='submit' value='" . $record['id'] . "'>Delete this Sheet</button>";
        }
        echo "</form>";
      }?>
          <iframe src="uploads/sheets/<?php echo $record["id"] . "." . $record["file_ext"]; ?>"></iframe>
        </div>
        <?php
      }
    }
  ?>
<?php if ($current_user) {echo "</div>";} ?>
</div>
<div class="clear"></div>
<br/>
<?php include("includes/footer.php");?>
</body>
</html>
