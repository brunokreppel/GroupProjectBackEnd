<?php
session_start();
$loc = "../";
require_once '../components/db_Connect.php';
require_once "../components/navbar.php";

$loggedInStudentId = $_SESSION["STUDENT"];

if(!isset($_SESSION["STUDENT"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["STUDENT"])) {

     // Predefined empty variables
     $rating = $message = $course_id = "";
     $recordMessage = ""; 
     $error = false;

     if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `reviews` WHERE `id` = $id AND `fk_user_id` = $loggedInStudentId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $rating = $row["rating"];
                $message = $row["message"];
                $course_id = $row["fk_course_id"];
            } else {
                echo "No course found with ID: $id";
            }
            mysqli_free_result($result);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    }
   
    if (isset($_POST["update"])) {
        $rating = $_POST["rating"];
        $message = $_POST["message"];
        $course_id = $_POST["course_id"];


        if ($error === false) {
            $sqlUpdate = "UPDATE `reviews` SET 
                `rating`= $rating, 
                `message`= '$message', 
                `fk_course_id`= $course_id
                WHERE id = $id";

            if (mysqli_query($conn, $sqlUpdate)) {
                $recordMessage = "Review has been updated successfully";
                header("refresh: 2; url= userReview.php");
            } else {
                $recordMessage = "Error updating record: " . mysqli_error($conn);
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
    <title>Document</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }
        .star-rating {
        font-size: 24px;
        }
        .star-rating i {
            cursor: pointer;
            color: #ddd;
        }
        .star-rating i:hover,
        .star-rating i.active {
            color: #ffcc00;
        }
    </style>
</head>
<body>

<div class="container">

     <!-- Display record creation message -->
     <?php if (!empty($recordMessage)) : ?>
        <div class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>" role="alert">
            <?php echo $recordMessage; ?>
        </div>
    <?php endif; ?>

    <h3 class="text-center">Update review</h3>
    <form method="POST" name="updateForm">
            <label>ID: <?php echo $id; ?></label>
            <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
            <label for="rating" class="form-label">Rating:</label>
            <div class="star-rating">
                <i class="fa fa-star" data-rating="1"></i>
                <i class="fa fa-star" data-rating="2"></i>
                <i class="fa fa-star" data-rating="3"></i>
                <i class="fa fa-star" data-rating="4"></i>
                <i class="fa fa-star" data-rating="5"></i>
            </div>
            <input type="hidden" name="rating" id="rating-value" value="<?= $row["rating"]??"" ?>"></input>
        </div>


        <div class="form-group">
            <label for="message" class="form-label">Your message:</label>
            <textarea type="text" name="message" class="form-control" cols="100" rows="5"><?= $row["message"]??"" ?></textarea>
        </div>

        
        <div class="form-group">
            <label for="course_id">Select a Course:</label>
            <select name="course_id" class="form-select" required>
                <?php

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to retrieve subject names associated with courses

                $sql = "SELECT c.id AS course_id, 
                s.id AS subject_id,
                s.name AS subject_name, 
                c.* 
                FROM `course` c 
                JOIN `subject` s ON c.fk_subject_id = s.id";

                $result = mysqli_query($conn, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $subjectId = $row['course_id'];
                        $subjectName = $row['subject_name'];
                        $selected = ($row['course_id'] == $subjectId) && ($row['subject_name'] == $subjectName) ? 'selected' : '';
                        echo '<option value="' . $subjectId . '" ' . $selected . '>' . $subjectName . '</option>';
                    }
                    mysqli_free_result($result);
                }
                ?>
            </select>
        </div>

        
        <button name="update" type="submit" class="btn btn-primary">Update review</button>
        <a href='userReview.php' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-outline-secondary mx-2'>Back</button></a>
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