<?php
session_start();

require_once '../components/db_Connect.php';

if (!isset($_SESSION["ADM"])) {
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["ADM"])) {

    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $id = $_GET["id"];

        // Check if the subject has associated courses
        $courseCheckQuery = "SELECT COUNT(*) as course_count FROM `course` WHERE `fk_university_id` = $id";
        $courseCheckResult = mysqli_query($conn, $courseCheckQuery);
        $courseCount = mysqli_fetch_assoc($courseCheckResult)['course_count'];

        if ($courseCount > 0) {
            // Display confirmation message
            echo '<script>
                    const confirmDelete = confirm("There is currently a course to prepare for this university. Are you sure you want to delete it?");
                    if (confirmDelete) {
                        window.location.href = "delete_university.php?id=' . $id . '&confirm=true"; // Proceed with deletion
                    } else {
                        window.location.href = "universities.php"; // Return to universities.php
                    }
                  </script>';
        } else {
            // No associated courses, proceed with deletion
            $sql = "DELETE FROM `university` WHERE `id` = $id";
            mysqli_query($conn, $sql);
            header("Location: universities.php");
        }
    } else {
        header("Location: ../index.php");
        die();
    }
}

mysqli_close($conn);
?>
