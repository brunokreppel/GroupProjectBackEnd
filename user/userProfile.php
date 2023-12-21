<?php
session_start();


if (!isset($_SESSION["STUDENT"]) && !isset($_SESSION["ADM"]) && !isset($_SESSION["TUTOR"])) {
    header("Location: login.php");
    exit();
}
$loc = "../";
require_once "{$loc}components/navbar.php";
require_once '../components/db_connect.php';

$user_id = $_SESSION["STUDENT"] ?? $_SESSION["ADM"]  ?? $_SESSION["TUTOR"];
$sql = "SELECT * FROM `users` WHERE `id` = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Student not found!";
    exit();
}

$current_courses = ""; // will hold the list for ongoing/upcoming booked courses
$past_courses = ""; // will hold the list for past courses
$number_of_attendees = 0; // will hold the list of attendees for a specific course
if (isset($_SESSION["ADM"])) {
    $current_courses = "Your are a Admin you dont have Courses";
    $past_courses = "Your are a Admin you dont have Courses";
}


if (isset($_SESSION["STUDENT"])) {
    //
    // show booked courses for the STUDENT (if there are any)
    //

    $user_id = $_SESSION["STUDENT"];

    $sql = "SELECT  booking.id AS booking_id,
                    booking.user_id AS booking_user_id,
                    course.id AS course_id,
                    course.fromDate AS course_fromDate,
                    course.ToDate AS course_toDate,
                    course.price AS course_price,
                    course.image AS course_image,
                    users.firstName AS tutor_first_name,
                    users.lastName AS tutor_last_name,
                    users.image AS tutor_image,
                    university.name AS university_name,
                    subject.name AS subject_name
            from booking
            JOIN course ON booking.fk_course_id = course.id
            JOIN subject ON course.fk_subject_id = subject.id
            JOIN university ON course.fk_university_id = university.id
            JOIN users ON course.fk_tutor_id = users.id
            where booking.user_id = '$user_id' ";

    $courses = mysqli_query($conn, $sql);
    if ($courses && mysqli_num_rows($courses) > 0) {
        //
        // user has booked one->many courses
        //
        $current_courses = "<div class='container'><u><h6 class='fw-bold'>Currently ongoing or upcoming Courses:</h6></u></div>"; // will hold the list for ongoing/upcoming booked courses
        $past_courses = "<div class='container'><u><h6 class='fw-bold'>Past Courses:</h6></u></div>"; // will hold the list for past courses

        while ($row = mysqli_fetch_assoc($courses)) {
            //
            // loop over all courses
            //
            $today = date("Ymd");
            if (strtotime($row['course_toDate']) < strtotime($today)) {
                $past_courses .= "                    
                <a href='../courses/courseDetails.php?id={$row['course_id']}' style='text-decoration: none; color: black;'>               
                <div class='container'>
                    <p><span class='fw-bold'>Subject:</span> {$row['subject_name']}</p>      
                    <p><span class='fw-bold'>University:</span> {$row['university_name']}</p>
                    <small class='text-body-secondary'><span class='fw-bold'>From: </span> " . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                    <small class='text-body-secondary'><span class='fw-bold'>To: </span>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                </div>
                </a>";
            } else {
                $current_courses .= "
                <a href='../courses/courseDetails.php?id={$row['course_id']}' style='text-decoration: none; color: black;'>               
                <div class='container'>
                    <p><span class='fw-bold'>Subject:</span> {$row['subject_name']}</p>      
                    <p><span class='fw-bold'>University:</span> {$row['university_name']}</p>
                    <small class='text-body-secondary'><span class='fw-bold'>From: </span> " . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                    <small class='text-body-secondary'><span class='fw-bold'>To: </span>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                </div>
                </a>";
            }
        }
    } else {
        // no rows found
        $current_courses = "<div class='container'>You have not booked any courses yet</div>";
        $past_courses = "<div class='container'>You have not booked any courses yet</div>";

    }
}

if (isset($_SESSION["TUTOR"])) {
    //
    // show courses for the TUTOR (if there are any)
    //

    $user_id = $_SESSION["TUTOR"];

    $sql = "SELECT  course.id AS course_id,
                    course.fromDate AS course_fromDate,
                    course.ToDate AS course_toDate,
                    course.price AS course_price,
                    course.image AS course_image,
                    university.name AS university_name,
                    subject.name AS subject_name
            from course
            JOIN subject ON course.fk_subject_id = subject.id
            JOIN university ON course.fk_university_id = university.id
            where course.fk_tutor_id = '$user_id' ";

    $courses = mysqli_query($conn, $sql);
    if ($courses && mysqli_num_rows($courses) > 0) {
        //
        // tutor has one->many courses
        //
        $current_courses = "<div class='container'><u><h6 class='fw-bold'>Currently ongoing or upcoming Courses:</h6></u></div>"; // will hold the list for ongoing/upcoming booked courses
        $past_courses = "<div class='container'><u><h6 class='fw-bold'>Past Courses:</h6></u></div>"; // will hold the list for past courses

        while ($row = mysqli_fetch_assoc($courses)) {
            //
            // loop over all courses
            //
            $today = date("Ymd");
            if (strtotime($row['course_toDate']) < strtotime($today)) {
                //
                // courses that ended in the past
                //
                $past_courses .= "     
                <a href='../courses/courseDetails.php?id={$row['course_id']}' style='text-decoration: none; color: black;'>               
                    <div class='container'>
                        <p><span class='fw-bold'>Subject:</span> {$row['subject_name']}</p>      
                        <p><span class='fw-bold'>University:</span> {$row['university_name']}</p>
                        <small class='text-body-secondary'><span class='fw-bold'>From: </span> " . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                        <small class='text-body-secondary'><span class='fw-bold'>To: </span>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                    </div>
                    </a>  ";
            } else {
                //
                // current courses for this tutor
                //
                $current_courses .= "
                <a href='../courses/courseDetails.php?id={$row['course_id']}' style='text-decoration: none; color: black;'>               
                <div class='container'>
                    <p><span class='fw-bold'>Subject:</span> {$row['subject_name']}</p>      
                    <p><span class='fw-bold'>University:</span> {$row['university_name']}</p>
                    <small class='text-body-secondary'><span class='fw-bold'>From: </span> " . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                    <small class='text-body-secondary'><span class='fw-bold'>To: </span>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                </div>
                </a> ";
                //
                // query over all the students that booked that course
                //
                $curr_course_id = $row['course_id'];

                $sql = "SELECT  users.id,
                                users.firstName AS first_name,
                                users.lastName AS last_name,
                                users.email,
                                users.status
                        FROM users
                        JOIN users_booking ON users.id = users_booking.fk_user_id
                        JOIN booking ON users_booking.fk_booking_id = booking.id
                        JOIN course ON course.id = booking.fk_course_id
                        WHERE course.id = $curr_course_id AND users.status = 'STUDENT'
                        ORDER BY users.id";

                $user_courses = mysqli_query($conn, $sql);

                if ($user_courses && mysqli_num_rows($user_courses) > 0) {
                    $number_of_attendees = mysqli_num_rows($user_courses);                    
                    $current_courses .= "<div class='container'><small> <span class='fw-bold'>Number of attendees:</span> ".$number_of_attendees."</small>";
                    while ($attendee_row = mysqli_fetch_assoc($user_courses)) {
                        // gives list of attendees for the tutor, probably better to put into course details
                        $current_courses .="<small><li>". $attendee_row['first_name']. " ". $attendee_row['last_name']."</li></small>";
                    }
                    $current_courses .="</div>";
                }
            }
        }
    } else {
        // no rows found
        $current_courses = "<div class='container'>You have no courses</div>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../style/rootstyles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      
        body{
            background-color: #fefefe;
        }
        .btn-primary {
    background-color: var(--primary-blue) !important;
    border: var(--primary-blue) !important;
    color: #fff;
    border: none;
    cursor: pointer;

}

.btn-primary:hover {
    background-color: #187be4 !important;
}
        .profile-img {
            max-width: 100%;
            height: 570px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .profile-section {
            position: relative;
            background-color: #fff;
            height: 570px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 300px;
            margin-top: 72px;
        }
        .course-container {
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 15px; /* Adjust as needed */
    }

    .course-container p {
        margin-bottom: 5px;
    }

    .course-container small {
        display: block;
        margin-bottom: 5px;
        color: #6c757d; /* Adjust text color as needed */
    
    }
    .grid-container {
        display: grid;
        gap: 25px; /* Adjust the gap between items */
        height: fit-content;
        margin-bottom: 20px;
        margin-top: 5px;
    }

    @media screen and (min-width: 768px) {
        /* For screens 768px and larger */
        .grid-container {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media screen and (max-width: 1199px) {
        .profile-section{
            height: fit-content;
        }
        .profile-img{
            height: 400px;
            margin-bottom: -40px;
        }
    }


  
    /* Styles for both containers */
    .course-container {
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .course-container p,
    .course-container small {
        margin-bottom: 5px;
    }

    .course-container small {
        display: block;
        color: #6c757d; /* Adjust text color as needed */
    }
    </style>
</head>

<body>

    <div class="container mt-3 pt-2">
        <div class="row">

            <div class="col-xl-4 col-lg-8 col-md-8 col-sm-8  mb-2 d-flex justify-content-center flex-column container">
                <div>
                    <h1 class="fw-bold mb-4 text-center text-black">Welcome, <?= $user["firstName"] ?>.</h1>
                    <img src="../assets/<?= $user['image'] ?>" alt="Profile Image" class="profile-img">
                </div>
            </div>
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12  ">
                <div class="profile-section ">
                    <div class="pb-2 ">
                        <p>
                        <h2>Thank you, <?= $user["firstName"] ?>!</h2>
                        </p>
                        <p class="px-3 pt-2">Welcome to our university tutoring community! We're thrilled to have you on board as a valuable member. Your profile information is provided below, and you can update it as needed. If you have any questions or concerns, feel free to reach out. Enjoy your time and academic journey with us!</p>
                    </div>
                    <h2 class="mb-3">Profile Information</h2>
                    <ul>
                        <li><strong><i class="ri-user-line"></i> First Name:</strong> <?= $user["firstName"] ?></li>
                        <li><strong> <i class="ri-user-line"></i> Last Name:</strong> <?= $user["lastName"] ?></li>
                        <li><strong><i class="ri-mail-line"></i> Email:</strong> <?= $user["email"] ?></li>
                        <li><strong><i class="ri-calendar-line"></i> Date of Birth:</strong> <?= $user["dateOfBirth"] ?></li>
                        <li><strong><i class="ri-phone-line"></i> Phone Number:</strong> <?= $user["phone_number"] ?></li>
                    </ul>
                    <h3>Information</h3>
                    <p class="pb-4"><i class="ri-information-line"></i> <?= $user["profile_info"] ?></p>
                    <a href="update.php" class="btn btn-primary " style="position: absolute; bottom: 15px;">Update</a>

                    <?php if (isset($_SESSION["STUDENT"])): ?>
                        <a href="../reviews/userReview.php" class="btn btn-primary " style="position: absolute; bottom: 15px; margin-left: 5rem;">My reviews</a>
                    <a href="../reviews/createReview.php" class="btn btn-primary " style="position: absolute; bottom: 15px; margin-left: 12rem;">Create review</a>
                </div>

                    <?php endif; ?>
    
            </div>
        </div>
    </div>


<div class="grid-container container">

<div class="course-container container p-4">
<?php echo " <div>$current_courses</div>" ?>

</div>
<div class="course-container container p-4">
<?php echo " <div>$past_courses</div>" ?>


</div>
</div>

 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>