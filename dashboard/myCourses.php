<?php
session_start();
require_once '../components/db_Connect.php';
$loc = "../";
require_once "../components/navbar.php";

// checks if the session is TUTOR
if (!isset($_SESSION["TUTOR"])) {
    header("Location: ../index.php");
    die();
}

// Check if the tutor ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect or handle the case when the tutor ID is not provided
    header("Location: ../index.php");
    die();
}

$sessionUserId = $_SESSION['TUTOR'];
$urlUserId = $_GET['id'];

// Compare the session ID with the ID from the URL to prevent unauthorized access
if ($sessionUserId !== $urlUserId) {
    // Redirect to the index page
    header('Location: ../index.php');
    exit();
}

// Modify the SQL query to filter by tutor ID
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
            course.fk_tutor_id = {$_GET['id']}"; 
$result = mysqli_query($conn, $sql);
$cards = "";

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cards .= "
           <div class='col mb-4'>
                <div class='card h-100 shadow-sm'>
                    <img src='../assets/{$row['course_image']}' class='bd-placeholder-img card-img-top' width='100%' height='225' role='img' alt='Course Image'>
                    <div class='card-body'>
                        <h5 class='card-title fw-bold'>{$row['subject_name']}</h5>
                        <p class='card-text'>{$row['university_name']}</p>
                        <div class='d-flex justify-content-between align-items-center mt-auto'>
                            <div class='btn-group'>
                                <a href='studentList.php?id={$row['course_id']}' class='btn btn-sm btn-outline-secondary'>Details <i class='ri-information-line'></i></a>
                                <a href='../courses/updateCourse.php?id={$row['course_id']}' class='btn btn-sm btn-outline-secondary'>Update <i class='ri-pencil-line'></i></a>
                            </div>
                            <small class='text-body-secondary'>From: " . date('F j, Y', strtotime($row['fromDate'])) . "</small>
                            <small class='text-body-secondary'>To: " . date('F j, Y', strtotime($row['ToDate'])) . "</small>
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
    <title>Your Courses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <style>
        /* Add your custom styles here */
        #noResultsMessage {
            display: none;
        }
        .headerH1{
    text-align: center;
    font-size: 2.6rem !important;
    font-weight: 700;
    margin: 30px 0 5px 0 !important;
    
}
.iconH1{
    font-size: 2.6rem;    

}
    </style>
</head>
<body>
<section class=" text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="headerH1">Your Courses <i class="ri-book-read-line iconH1"></i></h1>

            <p class="lead text-body-secondary">View and Edit your own Courses.</p>

            <div class="container ">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by course name...">
</div>
        </div>
    </div>
</section>



<div class="album py-5">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 d-flex justify-content-center">
            <?php echo $cards ?>
            <p id="noResultsMessage" class="text-center fw-bold">Nothing Found...</p>
        </div>
    </div>
</div>

<?php require_once '../components/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const cards = document.querySelectorAll(".col");
        const noResultsMessage = document.getElementById("noResultsMessage");

        // Set the initial state to 'none'
        noResultsMessage.style.display = 'none';

        function performSearch() {
            const query = searchInput.value.trim().toLowerCase();
            let foundResults = false;

            cards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                const match = cardText.includes(query);
                card.style.display = match ? 'flex' : 'none';

                if (match) {
                    foundResults = true;
                }
            });

            // Toggle the display of the noResultsMessage based on search results
            noResultsMessage.style.display = foundResults ? 'none' : 'block';
        }

        searchInput.addEventListener("input", performSearch);
    });
</script>
</body>
</html>
