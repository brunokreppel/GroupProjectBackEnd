<?php
echo "
<style>
    /* Styles for navbar links on hover */
    .navbar-nav a:hover {
        color: #232389 !important;
    }

    /* Custom class for the second ul element */
    .navbar-nav-right {
        position: absolute;
        right: 10px; /* Adjust the right distance as needed */
    }

    /* Media query to remove position at a width of 991px */
    @media (max-width: 991px) {
        .navbar-nav-right {
            position: static;
        }
    }
</style>

<nav class='navbar navbar-expand-lg navbar-light' style='background-color: #fefefe;         box-shadow: 0 0.1px 5px rgba(0, 0, 0, 0.2);'>
    <div class='container-fluid'>
        <a class='navbar-brand' href='{$loc}index.php'><img src='{$loc}assets/output-onlinepngtools(2).png' alt='' width='130px' style='margin-right: -15px;'></a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
            aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'
            style='outline: none; border: none !important; box-shadow: none !important;'> 
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav'>
                <!-- Home link -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}index.php'>Home</a>
                </li>
                <!-- Courses link -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}courses/courses.php'>Courses</a>
                </li>
                <!-- Reviews link -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}reviews/reviews.php'>Reviews</a>
                </li>";

if (isset($_SESSION["TUTOR"]) || isset($_SESSION["ADM"])){
    echo "
                <!-- Dashboard link for logged-in tutors or admins -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}dashboard/dashboard.php'>Dashboard</a>
                </li>
            </ul>
    ";
}

// Check if there is an active session
if (isset($_SESSION["STUDENT"]) || isset($_SESSION["ADM"]) || isset($_SESSION["TUTOR"])) {
    echo "
            <ul class='navbar-nav navbar-nav-right'>
                <!-- Profile link for logged-in users -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/userProfile.php'>Profile</a>
                </li>
                <!-- Logout link for logged-in users -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/logout.php'>Logout</a>
                </li>
            </ul>";
} else {
    // If no active session, display Login and Register links
    echo "
            <ul class='navbar-nav navbar-nav-right'>
                <!-- Login link for users without an active session -->
                <li class='nav-item'>
                    <a class='nav-link' href='{$loc}user/login.php'>Login</a>
                </li>
                <!-- Register link for users without an active session -->
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
