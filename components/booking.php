<?php

function book_course ($user_id, $course_id) {

    require_once 'db_connect.php';
    $error=false;


    // insert new record into booking with course+user

    $sql="INSERT INTO `booking`(`fk_course_id`, `user_id`) VALUES ('$course_id','$user_id')";
    $result = mysqli_query($conn, $sql);

    if(!$result){
        $bcError ="sql-stmnt: ".$sql." went very wrong";
        $error=true;
    }
    if (!$error) {
        $sql="SELECT `id` FROM `booking` WHERE `fk_course_id`= `$course_id` AND `user_id`= `$user_id`";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            $bcError ="sql-stmnt: ".$sql." went very wrong";
            $error=true;
        }
        $row = mysqli_fetch_assoc($result);
        $booking_id = $row["id"];
        if (!$error) {
            $sql="INSERT INTO `users_booking`(`fk_user_id`, `fk_booking_id`) VALUES ('$user_id','$booking_id')";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                $bcError ="sql-stmnt: ".$sql." went very wrong";
                $error=true;
            }
        }

    }
    return $error;
}
