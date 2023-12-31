<?php 
session_start();
$loc = "";
require_once "components/navbar.php";
require_once 'components/db_connect.php';

$sqlR = "SELECT 
    r.id AS review_id,
    c.id AS course_id,
    s.id AS subject_id,
    u.id AS user_id,
    r.rating AS review_rating,
    r.message AS review_message,
    r.creation_date AS review_creation_date,
    s.name AS subject_name,
    u.firstName AS user_firstName,
    u.lastName AS user_lastName
    FROM
    `reviews` r
    JOIN `course` c ON r.fk_course_id = c.id
    JOIN `subject` s ON c.fk_subject_id = s.id
    JOIN `users` u ON r.fk_user_id = u.id LIMIT 3
";
$resultReviews = mysqli_query($conn, $sqlR);
$reviewData = "";  // Initialize $reviewData as an empty string

if ($resultReviews) {
    while ($review = mysqli_fetch_assoc($resultReviews)) {

        $ratingStars = "";
        for ($i = 1; $i <= 5; $i++) {
            $active = ($review['review_rating'] >= $i) ? ' active' : "";
            $ratingStars .=  '<i class="fa fa-star' . $active . '"></i>';
        }

        $reviewData .= " 
        <div class='col-xl-4 mt-4'>
        <div class='position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer'>
        <div class='reviewBorder'>
            <div class='star-rating pb-3 pt-2'>
            " . $ratingStars ."
            </div>
            <h1 class='text-body-emphasis CstmH1'>{$review['subject_name']}</h1>
            <p class='col-lg-10 mx-auto mb-4 fst-italic reviewP overflow-scroll px-2'>
            {$review['review_message']}
            </p>
            <div class='d-flex justify-content-center p-2'>
                <div>
                    <p class='card-text'>{$review['review_creation_date']}</p>
                    <p class='card-text reviewFrom'>From: {$review['user_firstName']} {$review['user_lastName']}</p>                            
                </div>
            </div>
        </div>
        </div>
    </div>
        ";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}


$sql = "SELECT * FROM `users` WHERE `status` = 'TUTOR'";
$result = mysqli_query($conn, $sql);
$tutors = "";


if ($result) {
    while ($tutor = mysqli_fetch_assoc($result)) {

        $tutors .= " 
       
        <div class='swiper-slide'>
        <div class='swiper-wrapper swiperGrid'>
        <div class='swiperImgContainer'>
        <img src='assets/{$tutor['image']}' class='d-block carouselImg' alt='...'>
        </div>
            <div class='carousel_text'>
                <a href='#'><h2 class='py-2 fw-bold'>{$tutor['firstName']} {$tutor['lastName']}</h4></a>
                <p class='fw-light'>{$tutor['profile_info']}</p>
            </div>
        </div>
    </div>
        
        ";

    }

} else {
  
    echo "Error: " . mysqli_error($conn);
}

//
// select all courses and put their information into an array for the calendar
// 
$sql = "SELECT course.id AS course_id,
               course.fromDate AS fromDate,
               course.ToDate AS ToDate,
               course.fk_tutor_id,
               subject.name AS subject_name
        FROM `course`
        JOIN subject ON course.fk_subject_id = subject.id";

$result = mysqli_query($conn, $sql);
$all_courses_calendar = "";


if (($result) && (mysqli_num_rows($result) > 0)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $course_id = $row["course_id"];
        $subject_name = $row["subject_name"];
        $fromDate = date("Y-m-d", strtotime($row["fromDate"]));
        $ToDate = new \DateTime($row["ToDate"]);
        date_modify($ToDate, "+1 day");
        $ToDate = date_format($ToDate, "Y-m-d");


        $all_courses_calendar .= "
        {
            title: '$subject_name',
            start: '$fromDate',
            end: '$ToDate',
            url: 'courses/courseDetails.php?id=$course_id',
        },
        ";

    }
  }
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/review.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <style>
         .star-rating {
        font-size: 1.2rem;
        }
        .star-rating i {
            cursor: unset;
            color: #ddd;
        }
        .star-rating i.active {
            color: #ffcc00;
        }
    </style>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                center: 'dayGridMonth,dayGridWeek' // buttons for switching between views
            },
            events: [
                <?php echo $all_courses_calendar ?>
            ]
        });
        calendar.render();
        });

    </script>
    

    <style>
  
    </style>
    
</head>
<body>

    <div class="about_wrapper">
        <div class="about_container" style="margin-top: -100px;">
        <p class="mb-4">
            <div class="text">
            <h1 style="font-weight: 700;" class="display-4">About Us</h1>
            <br>
            <p class="fw-light">
                Established in 2021, Tutore Sphere is dedicated to providing a top-tier education at an affordable rate for all families.
            </p>

            <p class="mb-4 fw-light">
                Our team comprises more than 10 exceptionally skilled tutors, each thoroughly vetted through DBS checks and interviews, ensuring your peace of mind.
            </p>

            <p class="mb-4 fw-light">
                We specialize in online and in-person tuition, offering homeschooling services across all age groups and subjects. Additionally, we provide targeted GCSE revision courses for Mathematics, English, and Science.
            </p>

            <a href="http://api.serri.codefactory.live/random/" class="btn btn-primary">Learn more</a>

            </div>
        </div>
        <div class="about_container" >
            <img src="assets/laptop.svg" alt="" class="laptop" style="margin-top: -40px;" >
        </div>
    </div>

    <div class="services_wrapper">
    <div class="container px-4 " id="featured-3">
    <h1 class="border-bottom display-4 pb-3 fw-bold">Features</h1>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
      <div class="feature col">
        <div class="d-inline-flex align-items-center justify-content-center fs-2 mb-3">
        <i class="ri-book-read-line dashboard-icon"></i>
        </div>
        <h3 class="fs-2 text-body-emphasis fw-bold pb-2">Courses</h3>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="courses/courses.php" class="icon-link text-decoration-none">
        <span class="text-decoration-underline">View</span>
          <i class="ri-arrow-drop-right-line"></i>
        </a>
      </div>
      <div class="feature col">
      <div class="d-inline-flex align-items-center justify-content-center fs-2 mb-3">
        <i class="ri-building-line dashboard-icon"></i>
        </div>
        <h3 class="fs-2 text-body-emphasis fw-bold pb-2">Universities</h3>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="university/universities.php" class="icon-link text-decoration-none">
        <span class="text-decoration-underline">View</span>
          <i class="ri-arrow-drop-right-line"></i>
        </a>
      </div>
      <div class="feature col">
      <div class="d-inline-flex align-items-center justify-content-center fs-2 mb-3">
      <i class="ri-question-answer-line"></i>        
        </div>
        <h3 class="fs-2 text-body-emphasis fw-bold pb-2">Reviews</h3>
        <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
        <a href="reviews/reviews.php" class="icon-link text-decoration-none">
        <span class="text-decoration-underline">View</span>
          <i class="ri-arrow-drop-right-line"></i>
        </a>
      </div>
    </div>
  </div>
</div>


<div class="uni_wrapper pt-3">

<div class="container border-top pt-5">
    <h1 class="text-center pb-4" style="font-weight: 700;">Entrance Exam Success</h1>
    <div class="uni_flex container">
    <img src="assets/Logo_Anglo.png" alt="">
<img src="assets/Logo_Charles.svg" alt="">
<img src="assets/Logo_CTU.png" alt="">
<img src="assets/Logo_Life.png" alt="">
<img src="assets/Logo_NY.jpg" alt="">

    </div>


</div>

</div>


<div class="p-5" style="        background-color: var(--primary-white);
">
<h1 style="font-weight: 700;" class="display-4  mb-3 text-center">Our Calendar <i class="ri-calendar-event-line"></i></h1>

<div class='container container-fluid formContainer' id='calendar'></div>

</div>


 <!-- Swiper -->
 <div class="swiper mySwiper">
    <div class="swiper-wrapper">
    <?php echo $tutors?>
  
   
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div> 
  </div>


    

<div class="review_wrapper container">
    <h1 style="font-weight: 700; margin-left: 30px;" class="display-4 mt-2">Reviews <i class="ri-double-quotes-l"></i></h1>

<div class='container py-4'>
            <div class='row align-items-md-stretch'>
              
               
            <?php echo $reviewData ?>

            </div>
        </div>
</div>




<?php
require_once "components/footer.php";

?>


    
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    loop: true, // Enable infinite loop
  });
</script>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

     
</body>
</html>