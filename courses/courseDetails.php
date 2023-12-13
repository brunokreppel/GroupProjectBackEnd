<?php
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

if (isset($_GET["id"]) && !empty($_GET["id"])) {

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
$cards = "";

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Container for Course Information
        echo "
        <div class='container py-4'>
            <header class='pb-3 mb-4 border-bottom'>
                <a href='/' class='d-flex align-items-center text-body-emphasis text-decoration-none'>
                    <span class='fs-4'>Jumbotron example</span>
                </a>
            </header>

            <div class='p-5 mb-4 bg-body-tertiary rounded-3'>
                <div class='container-fluid py-5'>
                    <h1 class='display-5 fw-bold'>{$row['subject_name']}</h1>
                    <p class='col-md-8 fs-4'>{$row['subject_description']}</p>
                </div>
            </div>

            <div class='row align-items-md-stretch'>
                <div class='col-md-6'>
                    <div class='h-100 p-5 text-bg-dark rounded-3'>
                        <h2>{$row['university_name']}</h2>
                        <p>{$row['university_description']}</p>
                        <button class='btn btn-outline-light' type='button'>Example button</button>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='h-100 p-5 bg-body-tertiary border rounded-3'>
                        <h2>{$row['tutor_firstName']} {$row['tutor_lastName']}</h2>
                        <p>{$row['tutor_profile_info']}</p>
                        <button class='btn btn-outline-secondary' type='button'>Example button</button>
                    </div>
                </div>
            </div>
        </div>
        ";
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
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

</head>
<body>
    








  <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>    
</body>
</html>
    
