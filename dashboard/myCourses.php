<?php
// Start the session and include necessary files
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

// Check if the user is a TUTOR; otherwise, redirect to the index page
if (!isset($_SESSION["TUTOR"])) {
    header("Location: ../index.php");
    die();
}

// Check if the tutor ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect or handle the case when the tutor ID is not provided
    header("Location: ../index.php");
    die();
}

// Get user IDs from session and URL
$sessionUserId = $_SESSION['TUTOR'];
$urlUserId = $_GET['id'];

// Compare the session ID with the ID from the URL to prevent unauthorized access
if ($sessionUserId !== $urlUserId) {
    // Redirect to the index page
    header('Location: ../index.php');
    exit();
}

// Build SQL query to fetch course information for the specific tutor
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
            users ON course.fk_tutor_id = users.id
        WHERE 
            course.fk_tutor_id = {$_GET['id']}"; 

// Execute the query
$result = mysqli_query($conn, $sql);
$cards = "";
$tutor_courses_calendar = "";

// Check if the query was successful
if ($result) {
    // Loop through the fetched results and build HTML cards
    while ($row = mysqli_fetch_assoc($result)) {
        $cards .= "
        <article class='postcard light blue test mx-auto'>
            <!-- Course Image -->
            <a class='postcard__img_link' href='#'>
                <img class='postcard__img' src='../assets/{$row['course_image']}' alt='Course Image' />
            </a>
            <div class='postcard__text t-dark'>
                <!-- Course Title -->
                <h1 class='postcard__title blue'><a href='studentList.php?id={$row['course_id']}'>{$row['subject_name']}</a></h1>
                <div class='postcard__subtitle small'>
                    <!-- Course Dates -->
                    <time datetime='{$row['fromDate']}'>
                        <i class='fas fa-calendar-alt mr-2'></i>From: " . date('F j, Y', strtotime($row['fromDate'])) . "
                    </time>
                    <time datetime='{$row['ToDate']}'>
                        <i class='fas fa-calendar-alt mr-2'></i>To: " . date('F j, Y', strtotime($row['ToDate'])) . "
                    </time>
                </div>
                <div class='postcard__bar'></div>
                <!-- University Name -->
                <div class='postcard__preview-txt'>{$row['university_name']}</div>
                <!-- Actions: Details, Students, Update -->
                <ul class='postcard__tagbox'>
                    <li class='tag__item play blue'>
                        <a href='../courses/courseDetails.php?id={$row['course_id']}'><i class='fas fa-play mr-2'></i>Details</a>
                    </li>
                    <li class='tag__item play blue'>
                        <a href='studentList.php?id={$row['course_id']}'><i class='fas fa-play mr-2'></i>Students</a>
                    </li>
                    <li class='tag__item'>
                        <a href='../courses/updateCourse.php?id={$row['course_id']}'><i class='fas fa-edit mr-2'></i>Update</a>
                    </li>
                </ul>
            </div>
        </article>";
        // fill the calendar with events as well

        $course_id = $row["course_id"];
        $subject_name = $row["subject_name"];
        $fromDate = date("Y-m-d", strtotime($row["fromDate"]));
        $ToDate = new \DateTime($row["ToDate"]);
        date_modify($ToDate, "+1 day");
        $ToDate = date_format($ToDate, "Y-m-d");


        $tutor_courses_calendar .= "
        {
            title: '$subject_name',
            start: '$fromDate',
            end: '$ToDate',
            url: '../courses/courseDetails.php?id=$course_id',
        },
        ";
        
    }
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Courses</title>
    <!-- Fonts and stylesheets -->
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <link rel="stylesheet" href="../style/card.css">
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
             /* Custom styles for the calendar */
      .formContainer {
        max-width: 1000px !important;
        max-height: 100dvh;
        margin: 50px auto 50px auto;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 40px;
        border-radius: 8px;
        background-color: var(--primary-white);
    }
            #calendar {
                margin: 0 auto;
               
            }
      
            .fc-day-header {
                background-color: #007bff;
                color: black;
                font-weight: 700;
            }
    
            .fc-event {
                background-color: var(--primary-blue);
                border: none;
            }
    
            .fc-event-title {
                color: #fff;
            }
            .fc .fc-col-header-cell-cushion {
      display: inline-block;
      padding: 2px 4px;
      color: black;
      font-weight: 700;
      text-decoration: none;
    }
    .fc .fc-daygrid-day-number {
      padding: 4px;
      position: relative;
      z-index: 4;
      color: black;
      text-decoration: none;
      font-weight: 700;
    }
    .fc .fc-toolbar-title {
    
      margin: 0px;
      font-weight: 700;
      padding: 15px;
    }
    .fc-direction-ltr .fc-button-group > .fc-button:not(:last-child) {
      border-bottom-right-radius: 0px;
      border-top-right-radius: 0px;
      background-color: var(--primary-blue);
      border: none;
      
    }
    .fc-button:focus,
    .fc-button:active,
    .fc-button:hover {
      outline: none !important;
      border: none !important;
      box-shadow: none !important;
    }
    .fc-direction-ltr .fc-button-group > .fc-button:not(:first-child) {
      border-bottom-left-radius: 0px;
      border-top-left-radius: 0px;
      margin-left: -1px;
      background-color: var(--primary-blue);
      border: none;
    }
    .fc-direction-ltr .fc-button-group > .fc-button:not(:first-child):hover {
        background-color: #187be4;
    
    }
    .fc-direction-ltr .fc-button-group > .fc-button:not(:last-child):hover {
    background-color: #187be4;
    }
    /* New styles for smaller screens */
    @media (max-width: 760px) {
        .fc-header-toolbar {
        
        gap: 15px;
        margin-top: -20px;
    }
    .fc .fc-toolbar-title{
        font-size: 14px;
    }
    
        #calendar .fc-button {
            font-size: 12px; /* Adjust the font size as needed */
            padding: 5px 10px; /* Adjust the padding as needed */
        }
    
        .fc .fc-daygrid-day-number {
      font-size: 10px;
    }
    }
    /* New styles for smaller screens */
    @media (max-width: 580px) {
    
        .fc .fc-toolbar-title{
        font-size: 12px;
    }
    
        #calendar .fc-button {
            font-size: 8px; /* Adjust the font size as needed */
            padding: 2px 2px; /* Adjust the padding as needed */
        }
        .fc-daygrid-block-event .fc-event-time, .fc-daygrid-block-event .fc-event-title{
            font-size: 10px;
        }
       
    }
    </style>
</head>
<body>
    <section class="text-center container">
        <div class="row">
            <div class="col-lg-6 col-md-8 mx-auto mt-4">
                <!-- Header -->
                <h1 class="headerH1">Your Courses <i class="ri-book-read-line iconH1"></i></h1>
                <p class="lead text-body-secondary">View and Edit your own Courses.</p>
                <!-- Search input -->
                <div class="container ">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by course name...">
                </div>
            </div>
        </div>
    </section>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                center: 'dayGridMonth,dayGridWeek' // buttons for switching between views
            },
            events: [
                <?php echo $tutor_courses_calendar ?>
            ]
        });
        calendar.render();
        });

    </script>

    <section class="light">
        <div class="container py-5">
            <!-- Display course cards -->
            <?php echo $cards ?>
            <!-- No results message -->
            <p id="noResultsMessage" class="text-center fw-bold">Nothing Found...</p>
        </div>
    </section>

    <div class='container formContainer' id='calendar'></div>

    <!-- Footer -->
    <?php require_once '../components/footer.php' ?>

    <!-- Bootstrap JS -->
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
