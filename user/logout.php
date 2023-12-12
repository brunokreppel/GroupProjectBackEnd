<?php
    session_start();

    unset($_SESSION["STUDENT"]);
    unset($_SESSION["ADM"]);
    unset($_SESSION["TUTOR"]);


    session_unset();
    session_destroy();

    header("Location: ../index.php");