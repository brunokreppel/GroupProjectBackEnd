<?php
// <a href='details.php?id={$row['course_id']}' class='btn btn-info mx-2 rounded'>Details</a>";
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

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
            users ON course.fk_tutor_id = users.id";


$result = mysqli_query($conn, $sql);
$cards = "";

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        $cards .= " <div class='col'>
        <div class='card shadow-sm'>
            <img src='../assets/{$row['course_image']}' class='bd-placeholder-img card-img-top' width='100%' height='225' role='img' alt='Course Image'>
            <div class='card-body'>
                <p class='card-text'>{$row['subject_name']}</p>
                <p class='card-text'>{$row['university_name']}</p>
                
                <div class='d-flex justify-content-between align-items-center'>
                    <div class='btn-group'>
                        <a href='courseDetails.php?id={$row['course_id']}' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-sm btn-outline-secondary'>Details</button></a>
                        <a href='updateCourse.php?id={$row['course_id']}' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-sm btn-outline-secondary'>Update</button></a>
                    </div>
                    <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['fromDate'])) . "</small>
                <small class='text-body-secondary'>" . date('F j, Y', strtotime($row['ToDate'])) . "</small>
            </div>
            </div>
        </div>
    </div>";

    }

} else {
  
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
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
    




<section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="fw-light">Courses</h1>
        <p class="lead text-body-secondary">Something short and leading about the Courses below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
        <p>
          <a href="#" class="btn btn-primary my-2">Filter</a>
          <a href="#" class="btn btn-secondary my-2">Filter</a>
          <a href="#" class="btn btn-secondary my-2">Filter</a>
          <a href="#" class="btn btn-secondary my-2">Filter</a>
        </p>
      </div>
    </div>
  </section>

  <div class="album py-5 bg-body-tertiary">
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">       
      <?php echo $cards ?>
      </div>
    </div>
  </div>



  <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>    
</body>
</html>
    
