<header>
  <h1 id="title">Cornell Ukulele Club</h1>

  <nav id="links">
    <ul class="navbar">
      <?php
      foreach($pages as $page => $page_name) {
        if ($page == $current_page_id) {
          $css_id= "id='current_page'";
        } else {
          $css_id = "";
        }
        echo "<li><a " . $css_id . " href='" . $page. ".php'>$page_name</a></li>";
      }
      echo "</ul>";
      if ($current_user) {
        echo "<h4 id='currentuser'>––– Signed in as [" . $current_user . "]</h4>";
      }
      ?>
  </nav>
</header>
<hr id="divide">
