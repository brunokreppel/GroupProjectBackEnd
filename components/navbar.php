<?php
echo "
<style>
    .navbar-nav a:hover {
        color: #800e13 !important;
    }
    /* Add a custom class for the second ul element */
    .navbar-nav-right {
        position: absolute;
        right: 10px; /* You can adjust the right distance as needed */
    }

    /* Add a media query to remove the position at a width of 991px */
    @media (max-width: 991px) {
        .navbar-nav-right {
            position: static;
        }
    }
</style>
<nav class='navbar navbar-expand-lg navbar-light' style='background-color: #ffffff;'>
    <div class='container-fluid'>
        <a class='navbar-brand' href='{$loc}index.php'>TutorSphere</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
            aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'
            style='outline: none; border: none !important; box-shadow: none !important;'> 
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav'>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}index.php'>Home</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}courses/courses.php'>Courses</a>
                </li>
                ";
if (isset($_SESSION["STUDENT"]) || isset($_SESSION["ADM"])){
    echo "
    <li class='nav-item'>
<a class='nav-link' href='{$loc}courses/createCourse.php'>Create-Course</a>
</li>
            </ul>
    ";
}



// Check if there is an active session
if (isset($_SESSION["STUDENT"]) || isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])) {
    echo "
            <ul class='navbar-nav navbar-nav-right'>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/userProfile.php'>Profile</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/logout.php'>Logout</a>
                </li>
            </ul>";
} else {
    // If no active session, display Login and Register links
    echo "
            <ul class='navbar-nav navbar-nav-right'>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/login.php'>Login</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/register.php'>Register</a>
                </li>
            </ul>";
}

echo "
        </div>
    </div>
</nav>
";
?>
