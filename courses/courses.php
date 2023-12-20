<?php
// <a href='details.php?id={$row['course_id']}' class='btn btn-info mx-2 rounded'>Details</a>";
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

$sql = "SELECT 
            course.id AS course_id, 
            course.fk_subject_id, 
            course.fk_university_id, 
            course.fk_tutor_id, 
            course.fromDate, 
            course.ToDate, 
            course.price, 
            course.image AS course_image, 
            subject.id AS subject_id, 
            subject.name AS subject_name, 
            subject.description AS subject_description, 
            subject.core_concepts AS subject_core_concepts, 
            subject.exam_preparation AS subject_exam_preparation, 
            subject.importance AS subject_importance, 
            users.id AS tutor_id, 
            users.email AS tutor_email, 
            users.passwd AS tutor_passwd, 
            users.firstName AS tutor_firstName, 
            users.lastName AS tutor_lastName, 
            users.dateOfBirth AS tutor_dateOfBirth, 
            users.image AS tutor_image, 
            users.status AS tutor_status, 
            users.profile_info AS tutor_profile_info, 
            users.phone_number AS tutor_phone_number, 
            university.id AS university_id, 
            university.name AS university_name, 
            university.location AS university_location, 
            university.extURL AS university_extURL, 
            university.uni_description AS university_description
        FROM 
            course 
        JOIN 
            subject ON course.fk_subject_id = subject.id 
        JOIN 
            university ON course.fk_university_id = university.id 
        JOIN 
            users ON course.fk_tutor_id = users.id";


$result = mysqli_query($conn, $sql);
$cards = "";

if ($result) {
    // Loop through the fetched results and build HTML cards
    while ($row = mysqli_fetch_assoc($result)) {
        $cards .= "
        <article class='postcard light blue test'>
            <!-- Course Image -->
            <a class='postcard__img_link' href='#'>
                <img class='postcard__img' src='../assets/{$row['course_image']}' alt='Course Image' />
            </a>
            <div class='postcard__text t-dark'>
                <!-- Course Title -->
                <h1 class='postcard__title blue'><a href='courseDetails.php?id={$row['course_id']}'>{$row['subject_name']}</a></h1>
                <div class='postcard__subtitle small'>
                    <!-- Course Dates -->
                    <time datetime='{$row['fromDate']}'>
                        <i class='fas fa-calendar-alt me-2'></i>From: " . date('F j, Y', strtotime($row['fromDate'])) . "
                    </time>
                    <time datetime='{$row['ToDate']}'>
                        <i class='fas fa-calendar-alt mx-2'></i>To: " . date('F j, Y', strtotime($row['ToDate'])) . "
                    </time>
                </div>
                <div class='postcard__bar'></div>
                <!-- University Name -->
                <div class='postcard__preview-txt fw-bold'>University: <span class='fw-light'>{$row['university_name']}</span></div>
                <div class='postcard__preview-txt fw-bold'>Tutor: <span class='fw-light'>{$row['tutor_firstName']} {$row['tutor_lastName']}</span></div>
                <div class='postcard__preview-txt fw-bold'>Price: <span class='fw-light'>{$row['price']}$</span></div>


                <!-- Actions: Details, Students, Update -->
                <ul class='postcard__tagbox'>
                    <li class='tag__item play blue'>
                        <a href='../courses/courseDetails.php?id={$row['course_id']}'><i class='fas fa-play me-2'></i>Details</a>
                    </li>";

                    if (isset($_SESSION["TUTOR"]) || isset($_SESSION["ADM"])) {
                        $cards .= "
                        <li class='tag__item'>
                        <a href='../courses/updateCourse.php?id={$row['course_id']}'><i class='fas fa-edit me-2'></i>Update</a>
                    </li>";
                    }
                  


                    $cards .= "
                    <li class='tag__item'>
                        <a href='../reviews/courseReview.php?id={$row['course_id']}'><i class='ri-double-quotes-l me-2'></i></i>Reviews</a>
                    </li>
                </ul>
            </div>
        </article>";
    }
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="stylesheet" href="../style/card.css">
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>

    <style>
        /* Custom styles */
        *{font-family: 'Bai Jamjuree', sans-serif;}
        #noResultsMessage {
            display: none;
        }
        .headerH1{
            text-align: center;
            font-size: 2.6rem !important;
            font-weight: 700;
            margin: 0 0 5px 0 !important;
            color: black;
        }
        .iconH1{
            font-size: 2.6rem;    
        }
    </style>
</head>
<body>
    <section class="text-center container">
        <div class="row">
            <div class="col-lg-6 col-md-8 mx-auto mt-4">
                <!-- Header -->
                <h1 class="headerH1">All Courses <i class="ri-book-read-line iconH1"></i></h1>
                <p class="lead text-body-secondary">Search for a Course you would like to participate in!</p>
                <!-- Search input -->
                <div class="container ">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by course name...">
                </div>
            </div>
        </div>
    </section>
  <section class="light">
        <div class="container py-5">
            <!-- Display course cards -->
            <?php echo $cards ?>
            <!-- No results message -->
            <p id="noResultsMessage" class="text-center fw-bold">Nothing Found...</p>
        </div>
    </section>


  <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> 
    <!-- Custom script for search functionality -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const cards = document.querySelectorAll(".test");
            const noResultsMessage = document.getElementById("noResultsMessage");

            // Set the initial state to 'none'
            noResultsMessage.style.display = 'none';

            function performSearch() {
                const query = searchInput.value.trim().toLowerCase();
                let foundResults = false;

                cards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    const match = cardText.includes(query);
                    card.style.display = match ? 'flex' : 'none';

                    if (match) {
                        foundResults = true;
                    }
                });

                // Toggle the display of the noResultsMessage based on search results
                noResultsMessage.style.display = foundResults ? 'none' : 'block';
            }

            searchInput.addEventListener("input", performSearch);
        });
    </script>   
</body>
</html>
    
