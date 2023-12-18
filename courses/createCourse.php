<?php
session_start();
require_once '../components/db_Connect.php';
require_once '../components/file_upload.php';
require_once '../components/clean.php';
$loc = "../";
require_once "../components/navbar.php";

// new validateDate function that works with datetime
function validateDateTimeLocal($datetime)
{
    // Adjust the regular expression based on the datetime-local format
    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
    return preg_match($pattern, $datetime);
}

if (isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])) {

     // Predefined empty variables
     $fromDate= $toDate = $price = $image = $subjectId = $universityId = $tutorId = "";
     $dateError = $priceError = $recordMessage = ""; 
   

    if (isset($_POST["create"])) {
        $fromDate = clean($_POST["fromDate"]);
        $toDate = clean($_POST["ToDate"]);
        $price = clean($_POST["price"]);
        $image = fileUpload($_FILES["image"], "courses");
        $subjectId = clean($_POST["subjectId"]);
        $universityId = clean($_POST["universityId"]);
        $tutorId = clean($_POST["tutorId"]);

        $error = false;


        if (!validateDateTimeLocal($fromDate) || !validateDateTimeLocal($toDate)) {
            $error = true;
            $dateError = "Invalid date format. Use the a real Date";
        }
        if (strtotime($fromDate) > strtotime($toDate)) {
            $error=true;
            $dateError = "ToDate should be after FromDate";
        }
        
        // Validate price
        if (!is_numeric($price) || $price <= 0) {
            $error = true;
            $priceError = "Price must be a positive Number.";
        }

        if ($error === false) {
            $sql = "INSERT INTO course (fk_subject_id, fk_university_id, fk_tutor_id, fromDate, ToDate, price, `image`)
                    VALUES ($subjectId, $universityId, $tutorId, '$fromDate', '$toDate', $price, '$image[0]')";

        if (mysqli_query($conn, $sql)) {
            $recordMessage = "Record added successfully";
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
    <title>Document</title>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;700&display=swap" rel="stylesheet">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
   
</head>
<body>

<div class="container formContainer" style="max-width: 700px !important">
     <?php if (!empty($recordMessage)) : ?>
        <div class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>" role="alert">
            <?php echo $recordMessage; ?>
        </div>
    <?php endif; ?>
    <form method="post" name="createForm" enctype="multipart/form-data" class="px-1">
    <h2 class="fw-bold text-center mb-3">Create Course</h2>

        <div class="form-group">
            <label for="fromDate" class="form-label">From Date:</label>
            <input type="datetime-local" name="fromDate" class="form-control" required>
            <div class="text-danger mb-2">
            <?= $dateError ?>
            </div>
        </div>

        <div class="form-group">
            <label for="ToDate" class="form-label">To Date:</label>
            <input type="datetime-local" name="ToDate" class="form-control" required>
            <div class="text-danger mb-2">
            <?= $dateError ?>
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="form-label">Price:</label>
            <input type="number" name="price" class="form-control" required>
            <div class="text-danger mb-2">
            <?= $priceError ?>
            </div>
        </div>

        <div class="form-group mb-2">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group mb-2">
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

        <div class="form-group mb-2">
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

        <div class="form-group mb-2">
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
                    // Check if the logged-in user is a tutor
                    $loggedInTutor = isset($_SESSION["TUTOR"]) && $_SESSION["TUTOR"] == $row['id'];

                    // Add the selected attribute for the logged-in tutor
                    $selected = $loggedInTutor ? 'selected' : '';

                    $tutors .= '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['firstName'] . ' ' . $row['lastName'] . '</option>';
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

        <input type="submit" value="Create" name="create" class="btn btn-primary mt-3">
    </form>
</div>

<?php require_once '../components/footer.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
