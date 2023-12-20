
<?php

session_start();

$loc = "../";
require_once '../components/db_Connect.php';
require_once "../components/navbar.php";

$sql = "SELECT * FROM `university`";
$result = mysqli_query($conn, $sql);
$universities = "";

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $universities .= " 

        <div class='mt-4'>
            <div class='position-relative text-center text-muted bg-body border border-dashed rounded-5 CstmContainer'>
            <div class='reviewBorder'>
                <h1 class='text-body-emphasis CstmH1'>{$row['name']}</h1>
                <p class='col-lg-8 mx-auto mb-4 desc'>
                    Location:
                    <span>{$row['location']}</span>
                </p>
                <p class='col-lg-8 mx-auto mb-4 desc'>
                    Website:
                    <a href='{$row['extURL']}' class='website'><span class='fst-italic url'>{$row['extURL']}</span></a>
                </p>
                <details class='pt-2 pb-4'>
                    <summary class='desc'>Details</summary>
                        <p class='pt-2 desc'>Description</p>
                        <p class='card-text fst-italic'>{$row['uni_description']}</p>
                </details>
                <div class='d-flex justify-content-center'>
                        <div class='mb-5'>
                            <a href='updateUniversity.php?id={$row['id']}' class='UpdateU mb-4 mx-2'>Update</a>
                            <a href='deleteUniversity.php?id={$row['id']}' class='DeleteU  mb-4 mx-2'>Delete</a>
                        </div>
                </div>
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
    <title>Universities</title>
    <link rel="stylesheet" href="../style/rootstyles.css">
    <link rel="stylesheet" href="../style/sub_uni.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a32278c845.js" crossorigin="anonymous"></script>

   
</head>

<body>
 
    
    <div class="container mb-5 mt-4">
        <h1 class="headerH1 pb-5">Universities <i class="ri-school-line"></i></h1>
        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 rox-cols-lg-2 g-3">
            
            <?= $universities ?>   
            
        </div>
    </div>


    <?php require_once '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>