<?php
session_start();
require_once '../components/db_Connect.php';
require_once '../components/file_upload.php';
$loc = "../";
require_once "../components/navbar.php";

// new validateDate function that works with datetime
function validateDateTimeLocal($datetime)
{
    // Adjust the regular expression based on the datetime-local format
    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
    return preg_match($pattern, $datetime);
}

function check_course ($id, $user_id, $from_date, $to_date) {

    require '../components/db_connect.php';
    //
    // check if there are any other course for this tutor in this timeframe
    //
    $courses=true; // variable returns false if there are already courses and they overlap with the given timeframe

    //
    // don't update to date in the past
    //
    $now = date('Ymd');
    if ($now > date('Ymd', strtotime($from_date)))
    {
        $courses=false;
    }

    $sql = "SELECT  course.id AS course_id,
                    course.fromDate AS course_fromDate,
                    course.ToDate AS course_toDate
            from course
            where course.fk_tutor_id = '$user_id'
            AND course.id != $id";
    
    $user_courses = mysqli_query($conn, $sql);
    if (mysqli_num_rows($user_courses) == 0) {
        //
        // no courses exist for this tutor, everything okay
        //
        $courses=true;
        }
    else {
        //
        // courses exist, check if their timeframes are in this range
        //
        while ($row_courses = mysqli_fetch_assoc($user_courses)) {
            if ( 
                 date('Ymd', strtotime($from_date)) <= date('Ymd', strtotime($row_courses['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date)) >= date('Ymd', strtotime($row_courses['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date))  <= date('Ymd', strtotime($row_courses['course_toDate']))
              ) {
                $courses=false;
            }
            if ( 
                 date('Ymd', strtotime($from_date)) <= date('Ymd', strtotime($row_courses['course_toDate'])) &&
                 date('Ymd', strtotime($from_date)) >= date('Ymd', strtotime($row_courses['course_fromDate'])) &&
                 date('Ymd', strtotime($to_date))  >= date('Ymd', strtotime($row_courses['course_toDate']))                                                          
                ) {
                $courses=false;
            }
        }
    }
    return $courses;
}

if (isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])) {
    // Initialize variables
    $id = $fromDate = $toDate = $price = $subjectId = $universityId = $tutorId = $image = "";
    $priceError = $tutorError = "";
    $error = false;

    // Fetch the course details based on the passed id
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM course WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $fromDate = $row["fromDate"];
                $toDate = $row["ToDate"];
                $price = $row["price"];
                $subjectId = $row["fk_subject_id"];
                $universityId = $row["fk_university_id"];
                $tutorId = $row["fk_tutor_id"];
                $image = $row["image"];
            } else {
                echo "No course found with ID: $id";
            }
            mysqli_free_result($result);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if (isset($_POST["update"])) {
        $fromDate = $_POST["fromDate"];
        $toDate = $_POST["ToDate"];
        $price = $_POST["price"];
        $subjectId = $_POST["subjectId"];
        $universityId = $_POST["universityId"];
        $tutorId = $_POST["tutorId"];

        // Validate price
        if (!is_numeric($price) || $price <= 0) {
            $error = true;
            $priceError = "Price must be a positive Number.";
        }

        if (!check_course($id, $tutorId, $fromDate, $toDate)) {
            //
            // a course in this timeframe already exists for this tutor
            //
            $error = true;
            $tutorError = "A course in this timeframe already exists for this tutor/Don't update to a date in the past";
        }

        // Check if a new image is provided for update
        if ($error === false) {
            $newImage = fileUpload($_FILES["image"], "courses");
            }
                // if (!empty($newImage[0])) {
                //     // If a new image is uploaded, update the image path
                //     $image = $newImage[0];
                // }

        if ($error === false) {
            if($_FILES["image"]["error"] == 0){


                if ($row["image"] !== "Course.png") {
                    unlink("../assets/$row[image]");

                }

                $sql = "UPDATE course SET 
                        fk_subject_id = $subjectId,
                        fk_university_id = $universityId,
                        fk_tutor_id = $tutorId,
                        fromDate = '$fromDate',
                        ToDate = '$toDate',
                        price = $price,
                        `image` = '$newImage[0]'
                        WHERE id = $id";
            } else {

                $sql = "UPDATE course SET 
                        fk_subject_id = $subjectId,
                        fk_university_id = $universityId,
                        fk_tutor_id = $tutorId,
                        fromDate = '$fromDate',
                        ToDate = '$toDate',
                        price = $price
                        WHERE id = $id";

            }

            if (mysqli_query($conn, $sql)) {
                $recordMessage = "Record has been updated successfully";
                header("refresh: 3; url= courses.php");
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
    <title>Update Course</title>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;700&display=swap" rel="stylesheet">
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>
   
</head>
<body>

<div class="container formContainer" style="max-width: 700px !important">

        <!-- Display record creation message -->
        <?php if (!empty($recordMessage)) : ?>
        <div
            class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>"
            role="alert">
            <?php echo $recordMessage; ?>
        </div>
        <?php endif; ?>
        <form method="post" name="updateForm" enctype="multipart/form-data">
        <h2 class="fw-bold text-center mb-3">Update Course</h2>

        <div class="d-flex justify-content-center">
     <!-- Display the course id (not editable) -->
     <label>ID: <?php echo $id; ?></label>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </div>
       


            <div class="form-group">
                <label for="fromDate" class="form-label">From Date:</label>
                <input type="datetime-local" name="fromDate" class="form-control" value="<?php echo $fromDate; ?>">
            </div>

            <div class="form-group">
                <label for="ToDate" class="form-label">To Date:</label>
                <input type="datetime-local" name="ToDate" class="form-control" value="<?php echo $toDate; ?>">
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Price:</label>
                <input type="number" name="price" class="form-control" required value="<?php echo $price; ?>">
                <div class="text-danger">
                    <?= $priceError ?>
                </div>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">New Image:</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="form-group mb-2">
                <label for="subjectId" class="form-label">Subject ID:</label>
                <select name="subjectId" class="form-select" required>
                    <!-- Populate subjects dropdown options -->
                    <?php
                    $sql = "SELECT * FROM `subject`";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($row['id'] == $subjectId) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                        }
                        mysqli_free_result($result);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <label for="universityId" class="form-label">University ID:</label>
                <select name="universityId" class="form-select" required>
                    <!-- Populate universities dropdown options -->
                    <?php
                    $sql = "SELECT * FROM university";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($row['id'] == $universityId) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                        }
                        mysqli_free_result($result);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <label for="tutorId" class="form-label">Tutors:</label>
                <select name="tutorId" class="form-select" required>
                    <!-- Populate tutors dropdown options -->
                    <?php
                    $sql = "SELECT * FROM users WHERE status = 'TUTOR'";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($row['id'] == $tutorId) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['firstName'] . ' ' . $row['lastName'] . '</option>';
                        }
                        mysqli_free_result($result);
                    }
                    ?>
                </select>
                <div class="text-danger">
                    <?= $tutorError ?>
                </div>
            </div>

            <input type="submit" value="Update" name="update" class="btn btn-primary mt-3 ">
        </form>
    </div>

    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
