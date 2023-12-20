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
     $nameError = $descriptionError = $core_conceptsError = $exam_preparationError = $importanceError = $recordMessage = ""; 
   

    if (isset($_POST["create"])) {
        $name = clean($_POST["name"]);
        $description = clean($_POST["description"]);
        $core_concepts = clean($_POST["core_concepts"]);
        $exam_preparation = clean($_POST["exam_preparation"]);
        $importance = clean($_POST["importance"]);

        $error = false;


        // Validate name
        if(empty($name)) {
            $error=true;
            $nameError = "This field cannot be empty";
        }

        // Validate description
        if(empty($description)) {
            $error=true;
            $descriptionError = "Description cannot be empty";
        }

        // Validate core_concepts
        if(empty($core_concepts)) {
            $error=true;
            $core_conceptsError = "This field cannot be empty";
        }

        // Validate exam_preparation
        if(empty($exam_preparation)) {
            $error=true;
            $exam_preparationError = "This field cannot be empty";
        }

        // Validate importance
        if(empty($importance)) {
            $error=true;
            $importanceError = "This field cannot be empty";
        }

        if ($error === false) {
            $sqlInsert = "INSERT INTO `subject`(`name`, `description`,  `core_concepts`, `exam_preparation`, `importance`)
            VALUES ('$name', '$description', '$core_concepts', '$exam_preparation', '$importance')";

        if (mysqli_query($conn, $sqlInsert)) {
            $recordMessage = "Subject has been created";
            header("refresh: 2; url= subjects.php");
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
    <title>Create Subject</title>
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

    <h2 class="fw-bold text-center mb-2">Subject</h2>
    <form method="POST">

        <div class="form-group">
            <label for="name" class="form-label">Subject name:</label>
            <input type="text" class="form-control" name="name" required>
            <span class="text-danger"><?= $nameError ?></span>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description:</label>
            <input type="text" class="form-control" name="description" required>
            <span class="text-danger"><?= $descriptionError ?></span>
        </div>

        <div class="form-group">
            <label for="core_concepts" class="form-label">Core concepts:</label>
            <textarea type="text" class="form-control" name="core_concepts" required></textarea>
            <span class="text-danger"><?= $core_conceptsError ?></span>
        </div>

        <div class="form-group">
            <label for="exam_preparation" class="form-label">Exam preparation:</label>
            <textarea type="text" class="form-control" name="exam_preparation" required></textarea>
            <span class="text-danger"><?= $exam_preparationError ?></span>
        </div>

        <div class="form-group">
            <label for="importance" class="form-label">Importance:</label>
            <textarea type="text" class="form-control" name="importance" required></textarea>
            <span class="text-danger"><?= $importanceError ?></span>
        </div>
        
        <button name="create" type="submit" class="btn btn-primary">Create</button>
    </form>

</div>

<?php require_once '../components/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
crossorigin="anonymous"></script>
</body>
</html>