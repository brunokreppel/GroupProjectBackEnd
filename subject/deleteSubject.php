<?php

session_start();

require_once '../components/db_Connect.php';

if(!isset($_SESSION["ADM"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["ADM"])) {

    if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `subject` WHERE `id` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);  
        

        $sql1 = "DELETE FROM `subject` WHERE `id` = $id";
        mysqli_query($conn, $sql1);

        header("Location: subjects.php");

    } else {
    header("Location: ../index.php");
    die();
    } 
}
    mysqli_close($conn);
?>