<?php 
session_start();
$loc = "../";
require_once "../components/navbar.php";
require_once '../components/db_connect.php';

if (!isset($_SESSION["ADM"]) && !isset($_SESSION["TUTOR"])) {

    header("Location: ../index.php");
    die();

}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;700&display=swap" rel="stylesheet">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
   
</head>
<body>

<div class="container my-5">
  <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5">
    <h1 class="text-body-emphasis mt-5">Courses</h1>
    <p class="col-lg-6 mx-auto mb-4">
      Create a new course to offer valuable content to your students. Customize the course details and make it engaging for effective learning.
    </p>
    <div class="d-flex justify-content-center">
      <div class="btn-group" role="group" aria-label="Course actions">
        <a href="../courses/createCourse.php" class="btn btn-primary px-4 mb-5 mx-2 rounded">Create</a>
        <a href="../courses/courses.php" class="btn btn-primary px-4 mb-5 mx-2 rounded">View All</a>
        <?php if (isset($_SESSION["TUTOR"])): ?>
          <a href="#" class="btn btn-primary px-4 mb-5 mx-2 rounded">View My</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5">
    <h1 class="text-body-emphasis mt-5">Subjects</h1>
    <p class="col-lg-6 mx-auto mb-4">
      Develop a new subject to enhance the curriculum. Define the subject scope, objectives, and outline to provide a comprehensive learning experience.
    </p>
    <div class="d-flex justify-content-center">
      <div class="btn-group" role="group" aria-label="Subject actions">
        <a href="../subject/createSubject.php" class="btn btn-primary px-5 mb-5 mx-2 rounded">Create</a>
        <a href="../subject/subjects.php" class="btn btn-primary px-5 mb-5 mx-2 rounded">View</a>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5">
    <h1 class="text-body-emphasis mt-5">Universities</h1>
    <p class="col-lg-6 mx-auto mb-4">
      Establish a new university with a unique identity and vision. Define the mission, academic programs, and campus culture to shape future generations.
    </p>
    <div class="d-flex justify-content-center">
      <div class="btn-group" role="group" aria-label="University actions">
        <a href="../university/createUniversity.php" class="btn btn-primary px-5 mb-5 mx-2 rounded">Create</a>
        <a href="../university/universities.php" class="btn btn-primary px-5 mb-5 mx-2 rounded">View</a>
      </div>
    </div>
  </div>
</div>

<?php require_once '../components/footer.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
