<?php
session_start();
require_once '../components/db_Connect.php';
require_once '../components/file_upload.php';
require_once '../components/clean.php';
$loc = "../";
require_once "../components/navbar.php";


if(isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])){
   
    // Function to validate date format
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

if (isset($_POST["create"])) {
    $fromDate = clean($_POST["fromDate"]);
    $ToDate = clean($_POST["ToDate"]);
    $price = clean($_POST["price"]);
    $image = fileUpload($_FILES["image"], "courses");
    $subjectId = clean($_POST["subjectId"]);
    $universityId = clean($_POST["universityId"]);
    $tutorId = clean($_POST["tutorId"]);

    // Validate date format
    if (!validateDate($fromDate) || !validateDate($ToDate)) {
        echo "Invalid date format";
        return;
    }

    // Validate price
    if (!is_numeric($price) || $price <= 0) {
        echo "Invalid price";
        return;
    }

    $sql = "INSERT INTO course (fk_subject_id, fk_university_id, fk_tutor_id, fromDate, ToDate, price, `image`)
    VALUES ($subjectId, $universityId, $tutorId, '$fromDate', '$ToDate', $price, '$image[0]');";

    if (mysqli_query($conn, $sql)) {
        echo "Record added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}


 }else{

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
    <form method="post" name="createForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fromDate" class="form-label">From Date:</label>
            <input type="datetime-local" name="fromDate" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="ToDate" class="form-label">To Date:</label>
            <input type="datetime-local" name="ToDate" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price" class="form-label">Price:</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group">
            <label for="subjectId" class="form-label">Subject ID:</label>
            <select name="subjectId" class="form-select" required>
                <?php
               if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            // SQL query
            $sql = "SELECT * FROM `subject`";
            $result = mysqli_query($conn, $sql);
            $subjects = "";
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $subjects .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    echo $subjects;
                } else {
                    echo '<option value="" disabled>No subjects found</option>';
                }
                mysqli_free_result($result);
            } else {
                echo '<option value="" disabled>Error retrieving subjects</option>';
            }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="universityId" class="form-label">University ID:</label>
            <select name="universityId" class="form-select" required>
                <?php
                   if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                // SQL query
                $sql = "SELECT * FROM university";
                $result = mysqli_query($conn, $sql);
                $university = "";
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $university .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                        echo $university;
                    } else {
                        echo '<option value="" disabled>No unis found</option>';
                    }
                    mysqli_free_result($result);
                } else {
                    echo '<option value="" disabled>Error retrieving unis</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tutorId" class="form-label">Tutors:</label>
            <select name="tutorId" class="form-select" required>
                <?php
                
                     if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    // SQL query
                    $sql = "SELECT * FROM users WHERE status = 'TUTOR'";
                    $result = mysqli_query($conn, $sql);
                    $tutors = "";
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $tutors .= '<option value="' . $row['id'] . '">' . $row['firstName'] . ' ' . $row['lastName'] . '</option>';
                            }
                            echo $tutors;
                        } else {
                            echo '<option value="" disabled>No tutors found</option>';
                        }
                        mysqli_free_result($result);
                    } else {
                        echo '<option value="" disabled>Error retrieving tutors</option>';
                    }
                    mysqli_close($conn);
                    ?>
            </select>
        </div>

        <input type="submit" value="Create" name="create" class="btn btn-primary mt-3 mb-5">
    </form>
</div>

<?php require_once '../components/footer.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
