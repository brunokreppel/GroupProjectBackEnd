<?php
    session_start();

    require_once '../components/db_connect.php';
    require_once '../components/clean.php';
    require_once '../components/file_upload.php';

    if(!isset($_SESSION["STUDENT"]) && !isset($_SESSION["ADM"]) && !isset($_SESSION["TUTOR"])){
        header("Location: ../index.php");
        die();
    }

    if(isset($_SESSION["ADM"])){
        $id = $_GET["id"]??$_SESSION["ADM"];
    }
    elseif (isset($_SESSION["STUDENT"])) {
        $id = $_SESSION["STUDENT"];
    }
    else {
        $id = $_SESSION["TUTOR"];
    }

    $fnameError = $lnameError = $emailError = $dateofbirthError = $passError = $phone_numberError = $profile_infoError = ""; // define variables that will hold error messages later, for now empty string 

    // get current record from database 

    $sql = "SELECT * FROM `users` WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if(isset($_POST["update"])){



        // get inputs from below form

        $email = clean($_POST["email"]);
        $password = clean($_POST["password"]);
        $first_name = clean($_POST["first_name"]);
        $last_name = clean($_POST["last_name"]);
        $dateOfBirth = clean($_POST["dateOfBirth"]);
        $profile_info = clean($_POST["profile_info"]);
        $phone_number = clean($_POST["phone_number"]);

        // initialize error to be false

        $error = false;

        // simple validation for the "first name"
        if(empty($first_name)){
            $error = true;
            $fnameError = "Please, enter your first name";
        }elseif(strlen($first_name) < 3){
            $error = true;
            $fnameError = "Name must have at least 3 characters.";
        }elseif(!preg_match("/^[a-zA-Z\s]+$/", $first_name)){
            $error = true;
            $fnameError = "Name must contain only letters and spaces.";
        }

        // simple validation for the "last name"
        if(empty($last_name)){
            $error = true;
            $lnameError = "Please, enter your last name";
        }elseif(strlen($last_name) < 3){
            $error = true;
            $lnameError = "Last name must have at least 3 characters.";
        }elseif(!preg_match("/^[a-zA-Z\s]+$/", $last_name)){
            $error = true;
            $lnameError = "Last name must contain only letters and spaces.";
        }

       // simple validation for the "email"

        if(empty($email)){
            $error = true;
            $emailError = "Email cannot be empty.";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $emailError = "Email has the wrong format.";
        }
        else{
            if ($email !== $row["email"]) {
                $sql = "SELECT email FROM `users` WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) !== 0){
                    $error = true;
                    $emailError = "Email already exists.";
                }
            }
        }

        // simple validation for the "Date Of Birth"

        if(empty($dateOfBirth)) {
            $error=true;
            $dateofbirthError = "Date of Birth cannot be empty";
        }

        // simple validation for the password

        if(empty($password)){
            $error = true;
            $passError = "Password cannot be empty.";
        }
        elseif(strlen($password) < 6){
            $error = true;
            $passError = "Password must be at least 6 chars long.";
        }

        // simple validation for the phone number

        if(empty($phone_number)){
            $error = true;
            $phone_numberError = "Phone number cannot be empty.";
        }

        // do the picture last
        if($error === false){
            $picture = fileUpload($_FILES["picture"]);
        }

        if($error === false){
            $password = password_hash ($password, PASSWORD_DEFAULT);
            //$password = hash("sha256", $password);

            if ($_FILES["picture"]["error"] == 0){
                if ($row["image"] !== "User-avatar.svg.png"){
                    if ($row["image"] !== "User-avatar.svg.png"){
                        unlink("../assets/$row[image]");
                    }
                }
                $sql = "UPDATE `users` SET  `email`='$email',
                                            `passwd`='$password',
                                            `firstName`='$first_name',
                                            `lastName`='$last_name',
                                            `dateOfBirth`='$dateOfBirth',
                                            `image`='$picture[0]',
                                            `phone_number`='$phone_number',
                                            `profile_info`='$profile_info'

                        WHERE id = $id";
            }
            else {
                $sql = "UPDATE `users` SET  `email`='$email',
                                            `passwd`='$password',
                                            `firstName`='$first_name',
                                            `lastName`='$last_name',
                                            `dateOfBirth`='$dateOfBirth',
                                            `phone_number`='$phone_number',
                                            `profile_info`='$profile_info'

                        WHERE id = $id";

            }

            $result = mysqli_query($conn, $sql);

            if($result){
                echo "
            <div class='alert alert-success' role='alert'>
                User updated!
            </div>";
            }
            else{
                echo "
                <div class='alert alert-danger' role='alert'>
                    Something went wrong!
                </div>";
            }
        }

    }
    mysqli_close($conn);
?>

<!-- if ($row["image"] !== "User-avatar.svg.png"){
    unlink("../assets/$row[image]");
} -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
    <div class="container">
    <form method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                    <label for="fname" class="form-label">First name</label>
                    <input type="text" class="form-control" id="fname" name="first_name" value="<?= $row["firstName"]??""; ?>">
                    <span class="text-danger"><?= $fnameError ?></span>
                </div>
                <div class="mb-3">
                    <label for="lname" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="lname" name="last_name" value="<?= $row["lastName"]??""; ?>">
                    <span class="text-danger"><?= $lnameError ?></span>
                </div>
                <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">Date Of Birth</label>
                    <input type="date" class="form-control" id="dateofbirth" name="dateOfBirth" value="<?= $row["dateOfBirth"]??""; ?>">
                    <span class="text-danger"><?= $dateofbirthError ?></span>
                </div>
                <div class="mb-3">
                    <label for="pinfo" class="form-label">Information</label>
                    <textarea name="profile_info" id="pinfo" cols="50" rows="5"><?= $row["profile_info"]??""; ?></textarea>
                    <span class="text-danger"><?= $profile_infoError ?></span>
                </div>
                <div class="mb-3">
                    <label for="pnumber" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="pnumber" name="phone_number" value="<?= $row["phone_number"]??""; ?>">
                    <span class="text-danger"><?= $phone_numberError ?></span>
                </div>
                <div class="mb-3">
                    <label for="picture" class="form-label">Profile picture</label>
                    <input type="file" class="form-control" id="picture" name="picture">
                    <img style='width:100px;height:100px;' src='../assets/<?=$row["image"] ?>'>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $row["email"]??""; ?>">
                    <span class="text-danger"><?= $emailError ?></span>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span class="text-danger"><?= $passError ?></span>
                </div>
            <input type="submit" value="update" name="update" class="btn btn-primary">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>