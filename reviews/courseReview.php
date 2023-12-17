


<?php

session_start();
$loc = "../";
require_once '../components/db_Connect.php';
require_once "../components/navbar.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: ../courses/courses.php");
    die();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT 
        r.id AS review_id,
        c.id AS course_id,
        s.id AS subject_id,
        u.id AS user_id,
        r.rating AS review_rating,
        r.message AS review_message,
        r.creation_date AS review_creation_date,
        s.name AS subject_name,
        u.firstName AS user_firstName,
        u.lastName AS user_lastName
        FROM
        `reviews` r
        JOIN `course` c ON r.fk_course_id = c.id
        JOIN `subject` s ON c.fk_subject_id = s.id
        JOIN `users` u ON r.fk_user_id = u.id
        WHERE fk_course_id = $id
    ";

    $result = mysqli_query($conn, $sql);
    $cards = "";

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {

            $ratingStars = "";
            for ($i = 1; $i <= 5; $i++) {
                $active = ($row['review_rating'] >= $i) ? ' active' : "";
                $ratingStars .=  '<i class="fa fa-star' . $active . '"></i>';
            }
    
            $cards .= " 
            <div class='card text-bg-light mb-3'>
                <div class='card-header'>
                    <div class='star-rating'>
                    " . $ratingStars ."
                    </div>
                </div>
                <div class='card-body'>
                    <h5 class='card-title'>{$row['subject_name']}</h5>
                    <blockquote class='card-text blockquote'>
                        <p class='card-text fst-italic ms-3'>{$row['review_message']}</p>
                    </blockquote>
                    <p class='card-text'>{$row['review_creation_date']}</p>
                    <p class='card-text'>From: {$row['user_firstName']} {$row['user_lastName']}</p>
                </div>
            </div> ";
        }
    } else {

        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course reviews</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .star-rating {
        font-size: 1rem;
        }
        .star-rating i {
            cursor: unset;
            color: #ddd;
        }
        .star-rating i.active {
            color: #ffcc00;
        }
    </style>
</head>

<body>
 
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Course reviews</h1>
            </div>
        </div>
    </section>

    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?= $cards ?>
            </div>
        </div>
        <a href='../courses/courses.php' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-outline-secondary mx-2'>Back</button></a>
    </div>



    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>