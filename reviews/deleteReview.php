<?php
session_start();

require_once '../components/db_Connect.php';
$loggedInStudentId = $_SESSION["STUDENT"];

if(!isset($_SESSION["STUDENT"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["STUDENT"])) {

    if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `reviews` WHERE `id` = $id AND `fk_user_id` = $loggedInStudentId";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);  
        

        $sql1 = "DELETE FROM `reviews` WHERE `id` = $id";
        mysqli_query($conn, $sql1);

        header("Location: userReview.php");

    } else {
    header("Location: ../index.php");
    die();
    } 
}
    mysqli_close($conn);
?>
