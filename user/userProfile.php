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

if (isset($_SESSION["STUDENT"])) {
    //
    // show booked courses for the STUDENT (if there are any)
    //

    $current_courses = "<div class='container'><u>Currently ongoing or upcoming Courses:</u></div>"; // will hold the list for ongoing/upcoming booked courses
    $past_courses = "<div class='container'><u>Past Courses:</u></div>"; // will hold the list for past courses


    $user_id = $_SESSION["STUDENT"];

    $sql="select    booking.id AS booking_id,
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

    $courses=mysqli_query($conn, $sql);
    if ($courses && mysqli_num_rows($courses) > 0) {
        //
        // user has booked one->many courses
        //
        while ($row = mysqli_fetch_assoc($courses)) {
            //
            // loop over all courses
            //
            $today = date("Ymd");
            if (strtotime($row['course_toDate']) < strtotime($today)) {
                $past_courses .= "                    
                    <div class='container'>
                        <p>{$row['subject_name']} / {$row['university_name']}</p>                        
                        <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                        <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                        <p>Tutor: {$row['tutor_first_name']} {$row['tutor_last_name']}</p>
                    </div>";
            }
            else {
                $current_courses .= "
                    <div class='container'>
                        <p>{$row['subject_name']} / {$row['university_name']}</p>                        
                        <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['course_fromDate'])) . "</small>
                        <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['course_toDate'])) . "</small>
                        <p>Tutor: {$row['tutor_first_name']} {$row['tutor_last_name']}</p>
                    </div>";
            }
        }
    }
    else {
        // no rows found
        $current_courses= "<div class='container'>You have not booked any courses yet</div>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/php/BE20_CR5_BrunoKreppel/style/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .profile-img {
            max-width: 100%;
            height: 550px; 
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }
        .profile-section {
            background-color: #fff;
            height: 550px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 300px; 
            margin-top: 72px;
        }
    </style>
</head>
<body>

<div class="container mt-3 pt-2">
        <div class="row">
           
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12  mb-2 d-flex justify-content-center">
                <div>
                    <h1 class="fw-bold mb-4 text-center">Welcome, <?= $user["firstName"] ?>.</h1>
                    <img src="../assets/<?= $user['image'] ?>" alt="Profile Image" class="profile-img">
                </div>
            </div>
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12  ">
                <div class="profile-section ">
                    <div class="pb-3 ">
                        <p><h2>Thank you, <?= $user["firstName"] ?>!</h2></p>
                        <p class="px-3 pt-4">Welcome to our university tutoring community! We're thrilled to have you on board as a valuable member. Your profile information is provided below, and you can update it as needed. If you have any questions or concerns, feel free to reach out. Enjoy your time and academic journey with us!</p>
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
                    <p><i class="ri-information-line"></i> <?= $user["profile_info"] ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php echo $current_courses ?>
    <?php echo $past_courses ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
