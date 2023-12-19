<?php
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

// Check if the tutor is logged in
if (!isset($_SESSION["TUTOR"])) {
    header("Location: ../index.php");
    die();
}

// Get the tutor ID from the session
$tutor_id = $_SESSION["TUTOR"];

// Initialize the variable to store students' HTML content
$students = '';

// Check if the course ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the course ID from the URL
    $course_id = $_GET['id'];

    // SQL query to check if the tutor owns the specified course
    $check_ownership_sql = "SELECT * FROM course WHERE id = $course_id AND fk_tutor_id = $tutor_id";
    $ownership_result = mysqli_query($conn, $check_ownership_sql);

    // Check if the tutor owns the specified course
    if (mysqli_num_rows($ownership_result) > 0) {
        // Tutor owns the course, proceed to retrieve student information
        $sql = "SELECT users.id AS student_id,
            users.firstName AS student_first_name,
            users.lastName AS student_last_name,
            users.image AS student_image
        FROM booking
        JOIN users ON booking.user_id = users.id
        WHERE booking.fk_course_id = $course_id";

        // Execute the query and fetch the results
        $result = mysqli_query($conn, $sql);

        // Check if there are any students who booked the tutor's course
        if (mysqli_num_rows($result) > 0) {
            // Display the students who booked the tutor's course
            while ($row = mysqli_fetch_assoc($result)) {
                $students .= "<tr>";
                $students .= "<td>{$row['student_id']}</td>";
                $students .= "<td>{$row['student_first_name']}</td>";
                $students .= "<td>{$row['student_last_name']}</td>";
                $students .= "<td><img src='{$row['student_image']}' alt='Student Image' style='max-width: 50px; max-height: 50px;'></td>";
                $students .= "</tr>";
            }
        } else {
            $students = "<tr><td colspan='4'>No students have booked the tutor's course.</td></tr>";
        }

        // Free the result set
        mysqli_free_result($result);
    } else {
        // Tutor does not own the specified course
        $students = "<tr><td colspan='4'>You do not have permission to view bookings for this course.</td></tr>";
    }

    // Free the result set for ownership check
    mysqli_free_result($ownership_result);
} else {
    // Handle the case when course ID is not provided in the URL
    $students = "<tr><td colspan='4'>Course ID is missing in the URL.</td></tr>";
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
</head>
<body>
    
<div class="container mt-5">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Student ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Image</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $students; ?>
        </tbody>
    </table>
</div>

<?php require_once '../components/footer.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
