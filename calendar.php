<?php include('includes/init.php');

$current_page_id = "calendar";

$db = open_or_init_sqlite_db('data.sqlite', "init/init.sql");
const BOX_UPLOADS_PATH = "uploads/documents/";

/*Adding new event*/
if(isset($_POST["submit_new"])){
  $eventValNew = filter_var($_POST['new_event_name'], FILTER_SANITIZE_STRING);
  $eventsIDs = exec_sql_query($db, "SELECT event FROM 'events' WHERE event ='$eventValNew'")->fetchAll(PDO::FETCH_ASSOC);
  $dateNew = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
  $dateIDs = exec_sql_query($db, "SELECT day FROM 'events' WHERE day ='$dateNew'")->fetchAll(PDO::FETCH_ASSOC);
  $timeNew = filter_var($_POST['time'], FILTER_SANITIZE_STRING);
  $timeIDs = exec_sql_query($db, "SELECT times FROM 'events' WHERE times ='$timeNew'")->fetchAll(PDO::FETCH_ASSOC);

  if (empty($eventsIDs)){
    $sql = "INSERT INTO events (event, day, times) VALUES (:event, :day, :times)";
    $params = array(
      ":event" => $eventValNew,
      ":day" => $dateNew,
      ":times" => $timeNew
    );
    $result = exec_sql_query($db, $sql, $params);
    array_push($messages, "Successfully added the event!");
  } else {
    array_push($messages, "This event already exists!");
  }
}
/*PERSONAL EVENTS STUFF*/
if (isset($_POST['add_personalEvent'])) {
  $practiceID = filter_var($_POST['add_personalEvent'], FILTER_SANITIZE_STRING);

  $sqlCheck = "SELECT * FROM account_events WHERE account_name = :currentUser AND practice_id = :practiceID";
  $paramsCheck = array(
    ":currentUser" => $current_user,
    ":practiceID" => $practiceID
  );
  $resultCheck = exec_sql_query($db, $sqlCheck, $paramsCheck)->fetchAll();

  if (count($resultCheck) > 0 && count($resultCheck[0]) > 0) {
    array_push($messages, "You've already added this practice session. Add something new!");
  } else {
    $sql = "INSERT INTO account_events (account_name, practice_id) VALUES (:account_name, :practice_id)";
    $params = array(
      ":account_name" => $current_user,
      ":practice_id" => $practiceID
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Added this event successfully!");
    } else {
      array_push($messages, "That didn't work. Try again!");
    }
  }
}

if (isset($_POST['remove_personalEvent'])) {
  $practiceID = filter_var($_POST['remove_personalEvent'], FILTER_SANITIZE_STRING);

  $sqlCheck = "SELECT * FROM account_events WHERE account_name = :currentUser AND practice_id = :practiceID";
  $paramsCheck = array(
    ":currentUser" => $current_user,
    ":practiceID" => $practiceID
  );
  $resultCheck = exec_sql_query($db, $sqlCheck, $paramsCheck)->fetchAll();

  if (count($resultCheck) == 0) {
    array_push($messages, "You haven't added this event. Nothing to delete!");
  } else {
    $sql = "DELETE FROM account_events WHERE account_name = :account_name AND practice_id = :practice_id";
    $params = array(
      ":account_name" => $current_user,
      ":practice_id" => $practiceID
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Deleted this event from you calendar successfully!");
    } else {
      array_push($messages, "That didn't work. Try again!");
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Calendar</title>
</head>

<body>
  <div id="calendar">
  <div class="pageContent">
  <?php include("includes/header.php");?>
  <?php print_messages() ?>
  <h3 class="section"> Concerts </h3>
  <?php
  $sql = "SELECT * FROM events";
  $params = array();
  $records= exec_sql_query($db,$sql, $params)->fetchAll();
  echo "<ul class='eventList'>";
  if (isset($records) and !empty($records)){
    foreach($records as $record){
      echo "<li>[ ". $record['event'] ." ] ". $record['day']." - ".$record['times']."</li>";
    }
    echo "</ul><br/>";
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
  <span class="smallsection"> Want to add an event to the calendar? </span><br/><br/>
  <form method ="post" id="addform">

      Event Name: <input type="text" name="new_event_name"><br/><br/>
      Date: <input type="date" name="date"><br/><br/>
      Time: <input type="time" name="time"><br/><br/>
      <button class='personalEventButton greenButton' name="submit_new" type="submit">Add Event</button>
    </form>
    <?php
}
?>

<div id="event_list">
  <h3 class="section"> Practices </h3>
  <?php
  if ($current_user) {
    echo '<p>*** You\'re planning on going to anything highlighted in <span class="personalEventBackground">yellow</span>! ***</p>';
  } else {
    echo '<p>Log in to mark which practices you\'re going to!</p>';
  }
   ?>
        <?php
        $sql = "SELECT * FROM practices";
        $params = array();
        $records= exec_sql_query($db,$sql, $params)->fetchAll();
        ?>
        <table class='eventList' id="practice_list">
          <?php
        if (isset($records) and !empty($records)){
          ?>
          <tr><th>Date</th><th>Time</th>
          <?php
            if ($current_user) {
              echo "<th>Add</th></tr>";
            } else {
              echo "</tr>";
            }
          ?>
          <?php
          foreach(array_reverse($records) as $record){
            ?>
            <?php
              $sql = "SELECT * FROM account_events WHERE practice_id = :practice_id AND account_name = :account_name";
              $params = array(
                ":practice_id" => $record['id'],
                ":account_name" => $current_user
              );
              $results = exec_sql_query($db, $sql, $params)->fetchAll();

              if (count($results) > 0) {
                echo "<tr class='personalEventBackground'>";
              } else {
                echo "<tr>";
              }
            ?>
            <td><?php echo $record['day']; ?></td> <td><?php echo $record['times']; ?></td>
            <?php
            if ($current_user) {
              echo "<td>
                    <form action='calendar.php' method='post'>
                      <button class='personalEventButton greenButton' name='add_personalEvent' type='submit' value='" . $record['id'] . "'>Add</button>
                      <button class='personalEventButton redButton' name='remove_personalEvent' type='submit' value='" . $record['id'] . "'>Remove</button>
                    </form></td></tr>";
            } else {
              echo "</tr>";
            }
          }
          ?>
        </table><br/>
        <?php
        }
        ?>

      </div>
    </div>
  </div>
  <br/>
  <?php include("includes/footer.php");?>
    </body>

  </html>
