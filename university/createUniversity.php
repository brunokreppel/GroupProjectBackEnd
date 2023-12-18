
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
     $name = $location = $extURL = $uni_description = "";
     $nameError = $locationError = $extURLError = $uni_descriptionError = $recordMessage = ""; 
   

    if (isset($_POST["create"])) {
        $name = clean($_POST["name"]);
        $location = clean($_POST["location"]);
        $extURL = clean($_POST["extURL"]);
        $uni_description = clean($_POST["uni_description"]);

        $error = false;

        // Validate name
        if(empty($name)) {
            $error=true;
            $nameError = "The name field cannot be empty";
        }

        // Validate location
        if(empty($location)) {
            $error=true;
            $locationError = "Location cannot be empty";
        }

        // Validate extURL
        if(empty($extURL)) {
            $error=true;
            $extURLError = "Website cannot be empty";
        }

        // Validate uni_description
        if(empty($uni_description)) {
            $error=true;
            $uni_descriptionError = "Description cannot be empty";
        }


        if ($error === false) {
            $sqlInsert = "INSERT INTO `university`(`name`, `location`,  `extURL`, `uni_description`)
            VALUES ('$name', '$location', '$extURL', '$uni_description')";

        if (mysqli_query($conn, $sqlInsert)) {
            $recordMessage = "University has been created";
            header("refresh: 2; url= universities.php");
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
    <title>Create University</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

        textarea{
            height: 20dvh;
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

    <h3 class="text-center">University</h3>
    <form method="POST">

        <div class="form-group">
            <label for="name" class="form-label">University name:</label>
            <input type="text" class="form-control" name="name" required>
            <span class="text-danger"><?= $nameError ?></span>
        </div>

        <div class="form-group">
            <label for="location" class="form-label">Location:</label>
            <input type="text" class="form-control" name="location" required>
            <span class="text-danger"><?= $locationError ?></span>
        </div>

        <div class="form-group">
            <label for="extURL" class="form-label">Website:</label>
            <input type="text" class="form-control" name="extURL" required>
            <span class="text-danger"><?= $extURLError ?></span>
        </div>

        <div class="form-group">
            <label for="uni_description" class="form-label">Description:</label>
            <textarea type="text" class="form-control" name="uni_description" required></textarea>
            <span class="text-danger"><?= $uni_descriptionError ?></span>
        </div>
        
        <button name="create" type="submit" class="btn btn-primary">Add a university</button>
    </form>

</div>

<?php require_once '../components/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
crossorigin="anonymous"></script>
</body>
</html>