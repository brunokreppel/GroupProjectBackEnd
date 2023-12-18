
<?php
session_start();

require_once '../components/db_Connect.php';

if (!isset($_SESSION["ADM"])) {
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["ADM"]) && isset($_GET["id"]) && isset($_GET["confirm"]) && $_GET["confirm"] === "true") {
    $id = $_GET["id"];

    // Proceed with deletion
    $sql = "DELETE FROM `university` WHERE `id` = $id";
    mysqli_query($conn, $sql);
    header("Location: universities.php");
} else {
    header("Location: ../index.php");
    die();
}

mysqli_close($conn);
?>