<?php
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    //
    // no id for course provided
    //
    header("Location: courses.php");
    die();
}

$bcError ="";

function check_booking ($user_id, $from_date, $to_date) {

    require '../components/db_Connect.php';
    //
    // check if there are any other bookings for this student in this timeframe
    //

    $bookings=true; // variable returns false if there are already bookings and they overlap with the given timeframe

    //
    // don't book a course in the past
    //
    $now = date('Ymd');
    if (
        $now > date('Ymd', strtotime($to_date)) ||
       ($now > date('Ymd', strtotime($from_date)) && $now <= date('Ymd', strtotime($to_date)))
    )
    {
        $bookings=false;
    }

    $sql = "SELECT  booking.id AS booking_id,
                    course.id AS course_id,
                    course.fromDate AS course_fromDate,
                    course.ToDate AS course_toDate
            from booking
            JOIN course ON booking.fk_course_id = course.id
            where booking.user_id = '$user_id' ";
    
    $user_bookings = mysqli_query($conn, $sql);
    if (mysqli_num_rows($user_bookings) == 0) {
        //
        // no bookings exist for this user, everything okay
        //
        $bookings=true;
        }
    else {
        //
        // bookings exist, check if their timeframes are out of range
        //

        while ($row_bookings = mysqli_fetch_assoc($user_bookings)) {
         
            if ( 
                 date('Ymd', strtotime($from_date)) <= date('Ymd', strtotime($row_bookings['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date)) >= date('Ymd', strtotime($row_bookings['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date))  <= date('Ymd', strtotime($row_bookings['course_toDate']))
                ) {
                $bookings=false;
            }
            if ( 
                 date('Ymd', strtotime($from_date)) <= date('Ymd', strtotime($row_bookings['course_toDate'])) &&
                 date('Ymd', strtotime($from_date)) >= date('Ymd', strtotime($row_bookings['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date))  >= date('Ymd', strtotime($row_bookings['course_toDate']))                                                          
                ) {
                $bookings=false;
            }

        } // endwhile
    }
    return $bookings;
}

//
// if you are a student you can book this course
//

if(isset($_POST["BookCourse"])) {

    $error=false;

    $user_id = $_SESSION["STUDENT"];
    $course_id = $_GET["id"];

    // insert new record into booking with course+user

    $sql="INSERT INTO `booking`(`fk_course_id`, `user_id`) VALUES ('$course_id','$user_id')";
    
    $result = mysqli_query($conn, $sql);

    if(!$result){
        $bcError ="sql-stmnt: ".$sql." went very wrong";
        $error=true;
    }
    if (!$error) {

        $sql="SELECT `id` FROM `booking` WHERE `fk_course_id`='$course_id' AND `user_id`='$user_id'";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            $bcError ="sql-stmnt: ".$sql." went very wrong";
            $error=true;
        }
        else {
            $row = mysqli_fetch_assoc($result);
            $booking_id = $row["id"];
            $sql="INSERT INTO `users_booking`(`fk_user_id`, `fk_booking_id`) VALUES ('$user_id','$booking_id')";

            $result = mysqli_query($conn, $sql);
            if (!$result) {
                $bcError ="sql-stmnt: ".$sql." went very wrong";
                $error=true;
            
            }
            else {
            echo "
            <div class='alert alert-success' role='alert'>
                Booking created!
            </div>";
            header("Location: ../user/userProfile.php");
            }
        }
    // mysqli_close($conn);
    }
}


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
            course.id = $_GET[id]";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: courses.php");
    die();
}

$book = "";
$cards = "";
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Container for Course Information
        if (isset($_SESSION["STUDENT"]) && check_booking($_SESSION["STUDENT"],$row['fromDate'],$row['ToDate'])) {
            $book .= "<form method='post'>
                <input type='submit' value='Book this course' name='BookCourse' class='btn btn-primary w-100'>
                <span class='text-danger'><?= $bcError ?></span>
            </form>";
            }
        $cards .= "</div>
        ";

        $cards .= "
        <div class='container py-4'>
            <header class='pb-3 mb-4 border-bottom'>
                <h2 href='/' class='d-flex align-items-center text-body-emphasis text-decoration-none'>
                    <span class='fs-2 fw-bold'>Course Details <i class='ri-book-read-line'></i></span>
                </h2>
            </header>

            <div class='px-5 pb-3 pt-4 mb-4 bg-body-tertiary rounded-3'>
            <div class='container-fluid py-4 border-end border-2' style='max-width: 950px; margin-left: 10px; padding-left: 0;'>
                <h1 class='display-5 fw-bold pb-3'>{$row['subject_name']}</h1>
                <p class='col-md-10 fs-6'>{$row['subject_description']}</p>
                <p class='col-md-10 fs-6'>{$row['subject_core_concepts']}</p>
                <p class='col-md-10 fs-6'>{$row['subject_exam_preparation']}</p>
                <p class='col-md-10 fs-6'>{$row['subject_importance']}</p>
            </div>
        </div>
        

            <div class='row align-items-md-stretch'>
                <div class='col-md-6'>
                    <div class='h-100 p-5 text-bg-dark rounded-3'>
                        <h2 class='fw-bold pb-3'>{$row['university_name']}</h2>
                        <p>{$row['university_description']}</p>
                        <p>{$row['university_location']}</p>
                        <a href='{$row['university_extURL']}'>University Link</a>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='h-100 p-5 bg-body-tertiary border rounded-3'>
                        <h2 class='fw-bold pb-3'>Tutor:</h2>
                        <p class='fw-bold'><i class='ri-user-line'></i> {$row['tutor_firstName']} {$row['tutor_lastName']}</p>
                        <p><span class='fw-bold'><i class='ri-mail-line'></i> Email:</span> {$row['tutor_email']}</p>
                        <p ><span class='fw-bold'><i class='ri-phone-line'></i> Phone:</span> {$row['tutor_phone_number']}</p>
                        <p><span class='fw-bold'><i class='ri-information-line'></i> Info: </span>{$row['tutor_profile_info']}</p>
                        <h2 class='fw-bold pb-3'>Course Details:</h2>
                        <p></p>
                        
                        <!-- Course Dates -->
                        <div class='pb-2'>
                        <time datetime='{$row['fromDate']}'>
                        <i class='fas fa-calendar-alt me-2'></i><span class='fw-bold'>From:</span> " . date('F j, Y', strtotime($row['fromDate'])) . "
                    </time>
                        </div>
                    
                        
                        <div class='pb-2'>
                        <time datetime='{$row['ToDate']}'>
                        <i class='fas fa-calendar-alt me-2'></i><span class='fw-bold'>To:</span> " . date('F j, Y', strtotime($row['ToDate'])) . "
                    </time>
                        </div >
                     
                        
                        <div class='fw-bold mb-3'><i class='ri-currency-line'></i> Price:  <span class='fw-light '> {$row['price']}$</span></div>
                       <div> $book </div>
                    </div>
                </div>
            </div>";

   
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>
    
    <style>
      
    </style>
<style>

   
</style>
</head>
<body>
    

    
<div>
<?php echo $cards ?>
</div>
   




<?php require_once '../components/footer.php' ?>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>    
</body>
</html>
    
