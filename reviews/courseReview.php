

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
        WHERE c.id = $id
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
            <div class='col-xl-4 mt-4'>
                <div class='position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer'>
                <div class='reviewBorder'>
                    <div class='star-rating pt-2 pb-3'>
                    " . $ratingStars ."
                    </div>
                    <h1 class='text-body-emphasis CstmH1'>{$row['subject_name']}</h1>
                    <p class='col-lg-8 mx-auto mb-4 fst-italic reviewP'>
                        {$row['review_message']}
                    </p>
                    <div class='d-flex justify-content-center p-2'>
                        <div>
                            <p class='card-text'>{$row['review_creation_date']}</p>
                            <p class='card-text reviewFrom'>From: {$row['user_firstName']} {$row['user_lastName']}</p>                            
                        </div>
                    </div>
                </div>
                </div>
            </div>

            ";
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
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/review.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">


    <style>
        .star-rating {
        font-size: 1.2rem;
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
 

    <div class="container mb-5">
        <h1 class="headerH1">Course reviews <i class="ri-double-quotes-l"></i></h1>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            
                <?= $cards ?>
                
        </div>
    </div>


    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>