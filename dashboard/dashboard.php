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
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;700&display=swap" rel="stylesheet">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    
</head>
<body>

    <div class="container mb-5 mt-4">
        <h1 class="headerH1 pb-5">Dashboard <i class="ri-dashboard-line iconH1"></i></h1>
        <div class="row d-flex justify-content-center">
            <?php if (isset($_SESSION["TUTOR"])): ?>
                <div class="col-xl-8 mt-4">
            <?php else: ?>
                <div class="col-xl-4 mt-4">
            <?php endif; ?>
                    <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer">
                        <i class="ri-book-read-line dashboard-icon"></i>
                        <h1 class="text-body-emphasis CstmH1">Courses</h1>
                        <p class="col-lg-8 mx-auto mb-4">
                            Create, View, Update, and Delete the Courses.
                        </p>
                        <div class="d-flex justify-content-center">
                            <div class="btn-group" role="group" aria-label="Course actions">
                                <a href="../courses/createCourse.php" class="btn btn-primary mb-4 mx-2 rounded">Create</a>
                                <a href="../courses/courses.php" class="btn btn-primary mb-4 mx-2 rounded">View All</a>
                                <?php if (isset($_SESSION["TUTOR"])): ?>
                                    <a href="myCourses.php?id=<?php echo $_SESSION["TUTOR"]; ?>" class="btn btn-primary mb-4 mx-2 rounded">View My</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if (isset($_SESSION["ADM"])): ?>
                
            <div class="col-xl-4 mt-4">
                <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer">
                    <i class="ri-book-line dashboard-icon"></i>
                    <h1 class="text-body-emphasis CstmH1">Subjects</h1>
                    <p class="col-lg-8 mx-auto mb-4">
                         Create, View, Update, and Delete the Subjects.
                    </p>
                    <div class="d-flex justify-content-center">
                        <div class="btn-group" role="group" aria-label="Subject actions">
                            <a href="../subject/createSubject.php" class="btn btn-primary mb-4 mx-2 rounded">Create</a>
                            <a href="../subject/subjects.php" class="btn btn-primary mb-4 mx-2 rounded">View</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mt-4">
                <div class="position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer">
                    <i class="ri-building-line dashboard-icon"></i>
                    <h1 class="text-body-emphasis CstmH1">Universities</h1>
                    <p class="col-lg-8 mx-auto mb-4">
                    Create, View, Update, and Delete the Universities.
                    </p>
                    <div class="d-flex justify-content-center">
                        <div class="btn-group" role="group" aria-label="University actions">
                            <a href="../university/createUniversity.php" class="btn btn-primary mb-4 mx-2 rounded">Create</a>
                            <a href="../university/universities.php" class="btn btn-primary mb-4 mx-2 rounded">View</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
                <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
