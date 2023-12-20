

<?php

session_start();

$loc = "../";
require_once '../components/db_Connect.php';
require_once "../components/navbar.php";


if(!isset($_SESSION["ADM"])){
    header("Location: ../index.php");
    die();
}

if (isset($_SESSION["ADM"])) {

     // Predefined empty variables
     $name = $location = $extURL = $uni_description = "";
     $recordMessage = ""; 
     $error = false;

     if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `university` WHERE `id` = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $name = $row["name"];
                $location = $row["location"];
                $extURL = $row["extURL"];
                $uni_description = $row["uni_description"];
            } else {
                echo "No university found with ID: $id";
            }
            mysqli_free_result($result);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    }

    if (isset($_POST["update"])) {
        $name = $_POST["name"];
        $location = $_POST["location"];
        $extURL = $_POST["extURL"];
        $uni_description = $_POST["uni_description"];


        if ($error === false) {
            $sqlUpdate = "UPDATE `university` SET 
                `name`= '$name', 
                `location`= '$location', 
                `extURL`= '$extURL',
                `uni_description`= '$uni_description'
                WHERE id = $id";

            if (mysqli_query($conn, $sqlUpdate)) {
                $recordMessage = "University has been updated successfully";
                header("refresh: 2; url= universities.php");
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
    <title>Update University</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-control {
          
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }
      
    </style>
</head>
<body>

<div class="container formContainer" style="max-width: 700px;">

     <!-- Display record creation message -->
     <?php if (!empty($recordMessage)) : ?>
        <div class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>" role="alert">
            <?php echo $recordMessage; ?>
        </div>
    <?php endif; ?>

    <h2 class="fw-bold text-center mb-2">Update University</h2>
    <form method="POST">

        <div class="form-group">
            <label for="name" class="form-label">University name:</label>
            <input type="text" class="form-control" name="name"  value="<?= $row["name"]??"" ?>"></input>
        </div>

        <div class="form-group">
            <label for="location" class="form-label">Location:</label>
            <input type="text" class="form-control" name="location" value="<?= $row["location"]??"" ?>"></input>
        </div>


        <div class="form-group">
            <label for="extURL" class="form-label">Website:</label>
            <input type="text" class="form-control" name="extURL" value="<?= $row["extURL"]??"" ?>"></input>
        </div>

        <div class="form-group">
            <label for="uni_description" class="form-label">Description:</label>
            <textarea type="text" class="form-control" name="uni_description"><?= $row["uni_description"]??"" ?></textarea>
        </div>

        <button name="update" type="submit" class="btn btn-primary">Update</button>
        <a href='universities.php' class='btn-link text-decoration-none text-reset'><button type='button' class='btn btn-primary mt-3'>Back <i class="ri-arrow-go-back-fill"></i></button></a>
    </form>

</div>

<?php require_once '../components/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
crossorigin="anonymous"></script>
</body>
</html>