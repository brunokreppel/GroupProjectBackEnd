<?php

session_start();
require_once 'db_connect.php';

$bcError ="";

if(isset($_POST["BookCourse"])) {

    $error=false;

    $user_id = $_SESSION["STUDENT"];
    $course_id = 1;

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

            echo "
            <div class='alert alert-success' role='alert'>
                Booking created!
            </div>";
        }

    }
    mysqli_close($conn);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />

</head>
<body>


    <div class="container">
        <h1 class="text-center">book course</h1>
        <form method="post" autocomplete="off" enctype="multipart/form-data">
            <button name="BookCourse" type="submit" class="btn btn-primary">Book Course</button>
            <span class="text-danger"><?= $bcError ?></span>
        </form>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> 
     
</body>
</html>
