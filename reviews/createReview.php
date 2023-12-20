<?php
session_start();
$loc = "../";
require_once '../components/db_Connect.php';
require_once '../components/clean.php';
require_once "../components/navbar.php";

$loggedInStudentId = $_SESSION["STUDENT"];

if(!isset($_SESSION["STUDENT"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["STUDENT"])) {

     // Predefined empty variables
     $rating = $message = $course_id = $user_id = "";
     $messageError = $ratingError = $recordMessage = ""; 
   

    if (isset($_POST["create"])) {
        $rating = clean($_POST["rating"]);
        $message = clean($_POST["message"]);
        $course_id = clean($_POST["course_id"]);
        $user_id = clean($_POST["user_id"]);

        $error = false;


        // Validate message
        if(empty($message)) {
            $error=true;
            $messageError = "Message field cannot be empty";
        }

        if ($error === false) {
            $sqlInsert = "INSERT INTO `reviews`(`rating`, `message`,  `fk_course_id`, `fk_user_id`)
                    VALUES ($rating, '$message', $course_id, $loggedInStudentId)";

        if (mysqli_query($conn, $sqlInsert)) {
            $recordMessage = "Review has been created";
            header("refresh: 2; url= userReview.php");
        } else {
            $recordMessage = "Error adding record: " . mysqli_error($conn);
        }
        }
    }
} else {
    header("Location: ../index.php");
    die();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Review</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/review.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container formContainer" style="max-width: 600px !important">

     <!-- Display record creation message -->
     <?php if (!empty($recordMessage)) : ?>
        <div class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>" role="alert">
            <?php echo $recordMessage; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="px-1" name="createForm">
    <h2 class="fw-bold text-center mb-3">Create Review</h2>

        <div class="form-group">
            <label for="rating" class="form-label">Rating:</label>
            <div class="star-rating">
                <i class="fa fa-star" data-rating="1"></i>
                <i class="fa fa-star" data-rating="2"></i>
                <i class="fa fa-star" data-rating="3"></i>
                <i class="fa fa-star" data-rating="4"></i>
                <i class="fa fa-star" data-rating="5"></i>
            </div>
            <input type="hidden" name="rating" id="rating-value" required></input>
        </div>


        <div class="form-group">
            <label for="message" class="form-label">Your message:</label>
            <textarea type="text" name="message" class="form-control" cols="100" rows="5" required></textarea>
            <div class="text-danger mb-2">
                <?= $messageError ?>
            </div>
        </div>
        
        <div class="form-group mb-2">
            <label for="course_id">Select a Course:</label>
            <select name="course_id" class="form-select mt-2" required>
                <?php

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to retrieve subject names associated with courses
                $sql = "SELECT c.id AS course_id, s.id AS subject_id, c.*, s.* FROM `course` c JOIN `subject` s ON c.fk_subject_id = s.id";
                $result = mysqli_query($conn, $sql);
                $options = "";
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $subjectId = $row['course_id'];
                            $subjectName = $row['name'];
                            $options .= "<option value='$subjectId'>$subjectName</option>";
                        }
                        echo $options;
                    } else {
                        $options = "<option value='' disabled>No subjects found</option>";
                    }
                    mysqli_free_result($result);
                } else {
                    echo '<option value="" disabled>Error retrieving subjects</option>';
                }
                ?>
            </select>
        </div>


        <?php

        // SQL query for student_id
       
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch details for the logged-in student
        

        $sql = "SELECT * FROM users WHERE status = 'STUDENT' AND `id` = '$loggedInStudentId'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Display the details of the logged-in student
                echo '<div class="form-group mb-2">';
                echo '<label for="user_id" class="form-label">Student:</label>';
                echo '<input name="user_id" class="form-control" value="' . $row["firstName"] . ' ' . $row["lastName"] . '">';
                echo '</div>';
            } else {
                echo '<input value="" disabled>No student found</input>';
            }
            mysqli_free_result($result);
        } else {
            echo '<input value="" disabled>Error retrieving student</input>';
        }

        mysqli_close($conn);
        ?>
        
        <button name="create" type="submit" class="btn btn-primary updateReview">Create</button>
    </form>

</div>

<?php require_once '../components/footer.php' ?>


<script>
    const stars = document.querySelectorAll('.star-rating i');
    const ratingHolder = document.getElementById('rating-value');
    
    let getRating = (element) => parseInt(element.getAttribute("data-rating"));

    setStarsActive(stars, ratingHolder.value);

    stars.forEach((star) => {
        star.addEventListener("click", () => {
            const rating = getRating(star);
            ratingHolder.value = rating;
            setStarsActive(stars, rating);
        });
    });

    function setStarsActive(stars, rating){
        stars.forEach((s) => {
            s.classList.remove("active");
            if(getRating(s) <= rating) 
                s.classList.add("active");
        });
    }

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>