<?php
    session_start();

    require_once '../components/db_connect.php';
    require_once '../components/clean.php';

    if(isset($_SESSION["STUDENT"]) || isset($_SESSION["ADM"]) || (isset($_SESSION["TUTOR"]))){
       // redirect to another page because the session has been already created
        //
        // <--------- INSERT MISSING REDIRECT e.g. header("Location: ../index.php");
        //
    }

    $emailError = $passError = "";

    if(isset($_POST["login"])){
        $email = clean($_POST["email"]);
        $password = clean($_POST["password"]);
        $error=false;

        if(empty($email)){
            $error = true;
            $emailError = "Email cannot be empty.";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $emailError = "Email has the wrong format.";
        }

        if(empty($password)){
            $error = true;
            $passError = "Password cannot be empty.";
        }

        if(!$error){

            // $password = hash("sha256", $password);

            $sql = "SELECT * FROM `users` WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);
                $hash = $row["passwd"];
                if (!password_verify($password, $hash)) {
                    $error = true;
                    $passError = "Invalid password";
                    }
            }
            else{
                echo "
                <div class='alert alert-danger' role='alert'>
                    Something went very wrong when trying to find a unique user for the email in the database!
                </div>";
            }
        }

        if(!$error){

                if($row["status"] === "STUDENT"){
                    $_SESSION["STUDENT"] = $row["id"];
                    // redirect to another page because the session for the STUDENT has been created
                    //
                    header("Location: userProfile.php");
                    //
                }
                elseif($row["status"] === "ADM"){
                    $_SESSION["ADM"] = $row["id"];
                    // redirect to another page because the session for the ADMIN has been created
                    //
                    header("Location: userProfile.php");
                    //
                }
                elseif($row["status"] === "TUTOR"){
                    $_SESSION["TUTOR"] = $row["id"];
                    // redirect to another page because the session for the TUTOR has been created
                    //
                    header("Location: userProfile.php");
                    //
                }

            }
 
        }
    

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <form method="post">
            <label>
                Email:
                <input type="email" name="email" class="form-control" value="<?= $email??""; ?>">
                <span><?= $emailError; ?></span>
            </label>
            <label>
                Password:
                <input type="password" name="password" class="form-control">
                <span><?= $passError; ?></span>
            </label>
            <input type="submit" value="Login" name="login" class="btn btn-primary">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

