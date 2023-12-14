<?php 
session_start();
$loc = "";
require_once "components/navbar.php";
require_once 'components/db_connect.php';


$sql = "SELECT * FROM `users`";
$result = mysqli_query($conn, $sql);
$tutors = "";


if ($result) {
    while ($tutor = mysqli_fetch_assoc($result)) {

        $tutors .= " 
       
        <div class='swiper-slide'>
        <div class='swiper-wrapper swiperGrid'>
        <div class='swiperImgContainer'>
        <img src='assets/{$tutor['image']}' class='d-block carouselImg' alt='...'>
        </div>
            <div class='carousel_text'>
                <a href='#'><h2 class='py-3 fw-bold'>{$tutor['firstName']} {$tutor['lastName']}</h4></a>
                <p class='fw-light'>{$tutor['profile_info']}</p>
            </div>
        </div>
    </div>
        
        ";

    }

} else {
  
    echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/rootstyles.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <!-- Add these lines to the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    


    
</head>
<body>

    <div class="about_wrapper">
        <div class="about_container">
        <p class="mb-4">
            <div style="width: 60%;">
            <h1>About Us</h1>
            <br>
            <p>
                Established in 2021, Modern Tuition is dedicated to providing a top-tier education at an affordable rate for all families.
            </p>

            <p class="mb-4">
                Our team comprises more than 70 exceptionally skilled tutors, each thoroughly vetted through DBS checks and interviews, ensuring your peace of mind.
            </p>

            <p class="mb-4">
                We specialize in online and in-person tuition, offering homeschooling services across all age groups and subjects. Additionally, we provide targeted GCSE revision courses for Mathematics, English, and Science.
            </p>

            <a href="#" class="btn btn-warning">Learn more</a>

            </div>
        </div>
        <div class="about_container">
            <img src="assets/laptop.svg" alt="">
        </div>
    </div>

<div class="services_wrapper">
    <div class="row g-4">
        <div class="col-md-4 col-sm-6">
            <div class="services_card">


        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Free</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$0<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-5 mb-5">
              <li>10 users included</li>
              <li>2 GB of storage</li>
              <li>Email support</li>
              <li>Help center access</li>
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
          </div>
        </div>
    
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="services_card">
                
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Free</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$0<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-5 mb-5">
              <li>10 users included</li>
              <li>2 GB of storage</li>
              <li>Email support</li>
              <li>Help center access</li>
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
          </div>
        </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="services_card">
                
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Free</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$0<small class="text-body-secondary fw-light">/mo</small></h1>
            <ul class="list-unstyled mt-5 mb-5">
              <li>10 users included</li>
              <li>2 GB of storage</li>
              <li>Email support</li>
              <li>Help center access</li>
            </ul>
            <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for free</button>
          </div>
        </div>
            </div>
        </div>
    </div>
</div>

<div class="uni_wrapper">
<h1 class="text-center fw-bold"> Universities </h1>
<div class="uni_flex mt-5">
<img src="assets/Logo_Anglo.png" alt="">
<img src="assets/Logo_Charles.svg" alt="">
<img src="assets/Logo_CTU.png" alt="">
<img src="assets/Logo_Life.png" alt="">
<img src="assets/Logo_Ny.png" alt="">
</div>

</div>


 <!-- Swiper -->
 <div class="swiper mySwiper">
    <div class="swiper-wrapper">
    <?php echo $tutors?>
  
   
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div> 
  </div>


    

<div class="review_wrapper">
<div class='container py-5'>
            <div class='row align-items-md-stretch'>
                <div class='col-md-4 mt-2'>
                    <div class='h-100 p-4 text-bg-dark rounded-3'>
                        <h2>Lorem</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In tempore aperiam totam minima adipisci, architecto voluptas? Necessitatibus molestiae alias nobis a unde cupiditate sit. Quod tempore pariatur facilis magni debitis.</p>
                    </div>
                </div>
                <div class='col-md-4 mt-2'>
                    <div class='h-100 p-4 text-bg-secondary rounded-3'>
                        <h2>Lorem</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In tempore aperiam totam minima adipisci, architecto voluptas? Necessitatibus molestiae alias nobis a unde cupiditate sit. Quod tempore pariatur facilis magni debitis.</p>
                    </div>
                </div>
                <div class='col-md-4 mt-2'>
                    <div class='h-100 p-4 bg-body-tertiary border rounded-3'>
                        <h2>Lorem</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In tempore aperiam totam minima adipisci, architecto voluptas? Necessitatibus molestiae alias nobis a unde cupiditate sit. Quod tempore pariatur facilis magni debitis.</p>
                    </div>
                </div>
            </div>
        </div>
</div>




    
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    loop: true, // Enable infinite loop
  });
</script>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

     
</body>
</html>