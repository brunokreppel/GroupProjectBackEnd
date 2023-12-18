<?php

session_start();

$loc = "../";
require_once '../components/db_Connect.php';
require_once "../components/navbar.php";

$sql = "SELECT * FROM `subject`";
$result = mysqli_query($conn, $sql);
$subjects = "";

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $subjects .= " 
        <div class='card my-4'>
            <p class='card-header'>Subject ID: {$row['id']}</p>
            <div class='card-body'>
                <h5 class='card-title'>{$row['name']}</h5>
                <p class='card-text'>Description</p>
                <p class='card-text fst-italic'>{$row['description']}</p>
                <details class='pt-2 pb-4'>
                    <summary>Details</summary>
                        <p class='pt-2'>Core concepts</p>
                        <p class='card-text fst-italic'>{$row['core_concepts']}</p>
                        <p>Exam preparation</p>
                        <p class='card-text fst-italic'>{$row['exam_preparation']}</p>
                        <p>Importance</p>
                        <p class='card-text fst-italic'>{$row['importance']}</p>
                </details>
                <a href='updateSubject.php?id={$row['id']}' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-outline-warning mx-2'>Update</button></a>
                <a href='deleteSubject.php?id={$row['id']}' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-outline-danger mx-2'>Delete</button></a>
            </div>
        </div> ";
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
    <title>Subjects</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   
</head>

<body>
 
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Subjects</h1>
            </div>
        </div>
    </section>

    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="my-3">
                <?= $subjects ?>
            </div>
        </div>
    </div>



    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>