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

if (isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])) {
    // Initialize variables
    $id = $fromDate = $toDate = $price = $subjectId = $universityId = $tutorId = $image = "";
    $priceError = "";
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

        // Check if a new image is provided for update
        $newImage = fileUpload($_FILES["image"], "courses");
        if (!empty($newImage[0])) {
            // If a new image is uploaded, update the image path
            $image = $newImage[0];
        }

        if ($error === false) {
            $sql = "UPDATE course SET 
                    fk_subject_id = $subjectId,
                    fk_university_id = $universityId,
                    fk_tutor_id = $tutorId,
                    fromDate = '$fromDate',
                    ToDate = '$toDate',
                    price = $price,
                    `image` = '$image'
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                $recordMessage = "Record has been updated successfully";
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
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <style>
        /* Custom styling for form elements */
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
        <div
            class="alert m-4 text-center <?php echo (strpos($recordMessage, "Error") !== false) ? "alert-danger" : "alert-success"; ?>"
            role="alert">
            <?php echo $recordMessage; ?>
        </div>
        <?php endif; ?>
        <form method="post" name="updateForm" enctype="multipart/form-data">
            <!-- Display the course id (not editable) -->
            <label>ID: <?php echo $id; ?></label>
            <input type="hidden" name="id" value="<?php echo $id; ?>">


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
                <?= $priceError ?>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">New Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="form-group">
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

            <div class="form-group">
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

            <div class="form-group">
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
            </div>

            <input type="submit" value="Update" name="update" class="btn btn-primary mt-3 mb-5">
        </form>
    </div>

    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
