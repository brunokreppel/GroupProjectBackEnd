<?php

session_start();

$loc = "../";
require_once '../components/db_Connect.php';
require_once '../components/clean.php';
require_once "../components/navbar.php";


if(!isset($_SESSION["ADM"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["ADM"])) {

     // Predefined empty variables
     $name = $description = $core_concepts = $exam_preparation = $importance = "";
     $recordMessage = ""; 
     $error = false;

     if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `subject` WHERE `id` = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $name = $row["name"];
                $description = $row["description"];
                $core_concepts = $row["core_concepts"];
                $exam_preparation = $row["exam_preparation"];
                $importance = $row["importance"];
            } else {
                echo "No subject found with ID: $id";
            }
            mysqli_free_result($result);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    }
   

    if (isset($_POST["update"])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $core_concepts = $_POST["core_concepts"];
        $exam_preparation = $_POST["exam_preparation"];
        $importance = $_POST["importance"];


        if ($error === false) {
            $sqlUpdate = "UPDATE `subject` SET 
                `name`= '$name', 
                `description`= '$description', 
                `core_concepts`= '$core_concepts',
                `exam_preparation`= '$exam_preparation', 
                `importance`= '$importance'
                WHERE id = $id";

            if (mysqli_query($conn, $sqlUpdate)) {
                $recordMessage = "Subject has been updated successfully";
                header("refresh: 2; url= subjects.php");
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
    <title>Create Subject</title>
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

    <h3 class="text-center">Subject</h3>
    <form method="POST">

        <div class="form-group">
            <label for="name" class="form-label">Subject name:</label>
            <input type="text" class="form-control" name="name"  value="<?= $row["name"]??"" ?>"></input>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description:</label>
            <input type="text" class="form-control" name="description" value="<?= $row["description"]??"" ?>"></input>
        </div>

        <div class="form-group">
            <label for="core_concepts" class="form-label">Core concepts:</label>
            <textarea type="text" class="form-control" name="core_concepts"><?= $row["core_concepts"]??"" ?></textarea>
        </div>

        <div class="form-group">
            <label for="exam_preparation" class="form-label">Exam preparation:</label>
            <textarea type="text" class="form-control" name="exam_preparation"><?= $row["exam_preparation"]??"" ?></textarea>
        </div>

        <div class="form-group">
            <label for="importance" class="form-label">Importance:</label>
            <textarea type="text" class="form-control" name="importance"><?= $row["importance"]??"" ?></textarea>
        </div>

        
        <button name="update" type="submit" class="btn btn-primary">Update subject</button>
        <a href='subjects.php' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-outline-secondary mx-2'>Back</button></a>
    </form>

</div>

<?php require_once '../components/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
crossorigin="anonymous"></script>
</body>
</html>