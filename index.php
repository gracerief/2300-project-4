<?php include('includes/init.php');

$current_page_id="index";
 ?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

  <title>Home</title>
</head>

<body>
  <br/><br/>
  <?php include("includes/header.php");?>
  <div id="page_content">
    <article id="content">
      <h1 id="page-title"> Welcome to the Cornell Ukulele Club! </h1>





  <!-- slideshow -->

  <div class="slideshow_box">

    <!-- images -->
    <div class="photo_slides">
      <img src="uploads/images/1.jpg" alt="uke club!" style="width:65%">
    </div>

    <div class="photo_slides">
      <img src="uploads/images/2.jpg" alt="uke club!" style="width:65%">
    </div>

    <div class="photo_slides">
      <img src="uploads/images/3.jpg" alt="uke club!" style="width:65%">
    </div>

    <!-- Next and previous buttons -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
  <br>

  <!-- The dots/circles -->
  <div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
  </div>

  <script>
  var slideIndex = 1;
  showSlides(slideIndex);

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("photo_slides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
  }
  </script>
  <p class="slideshowCaption">All images from the Cornell Ukulele Club album.</p>
  <p class="slideshowCaption"> slideshow code from: https://www.w3schools.com/howto/howto_js_slideshow.asp </p>


<!-- title -->

      <p class="paragraph"> The Cornell Ukulele Club was founded on August 20, 2011. The Cornell Ukulele Club exists to promote the study and performance of ukulele music. We focus on learning basic techniques and enhancing the skills of our members. We intend to perform and bring ukulele music to Cornell in and around campus in Ithaca!</p>


      <section>
          <h2 class="section">Upcoming Events </h2>


      <ul class="indexlist">
        <li><a> [ Orientation Week Concert ] January 25, 2018, 7:00pm </a></li>
        <li><a> [ Mid-Semester Ukulele Fun ] March 23, 2018, 7:00pm</a></li>
        <li><a> [ End of Year Concert ] May 14, 2018, 7:00pm</a></li>
      </ul>

        </section>


        <section>
          <h2 class="section"> Join Our Listserv </h2>
          <p class="paragraph">Send an email to
             <a class="email" href="mailto:ukes-l-request@cornell.edu">ukes-l-request@cornell.edu </a> with the subject of your message Join and the body blank. We look forward to sharing updates with you!</p>
        </section>


        <section>

          <h2 class="section"> Meet Our E-Board </h2>

          <ul class="indexlist">
            <li><a> [ Allison Sutton ] President </a></li>
            <li><a> [ Anna Overholts ] Treasurer</a></li>
            <li><a> [ Kevin Lin ] Third Offiver </a></li>
            <li><a> [ Thomas Rachman ] Secretary </a></li>
          </ul>
        </section>



  </article>
</div>
<br/>
<?php include("includes/footer.php");?>
</body>

</html>
