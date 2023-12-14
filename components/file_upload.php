<?php
   
   function fileUpload($image, $source = 'user') {
        $message = "";
        $imageName = "";

        if ($image["error"] == 4) { 
            $imageName = "User-avatar.svg.png";

            if ($source == "courses") {
                $imageName = "Course.png";
            }

            $message = "No picture has been chosen, please upload your image later :)";
        } else {
            $checkIfImage = getimagesize($image["tmp_name"]);
            if (!$checkIfImage) { 
                $message = "Not an image";
            } else {
                $allowedFileTypes = array('jpg', 'jpeg', 'png');
                $fileExtension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedFileTypes)) { 
                    $message = "File is not supported. Upload only " . implode(", ", $allowedFileTypes) . " files.";
<<<<<<< HEAD
                    if ($source == "courses") {
                        $imageName = "Course.png";
                    } else {
                        $imageName = "User-avatar.svg.png";
                    }
                } else if ($image["size"] > 300000) { 
=======
                } else if ($image["size"] > 400000) { 
>>>>>>> 3041ca36df307e6e21dbab91d25e746add23cc8b
                    $message = "File is too large to upload.";
                    if ($source == "courses") {
                        $imageName = "Course.png";
                    } else {
                        $imageName = "User-avatar.svg.png";
                    }
                } else {
                    $imageName = uniqid("") . "." . $fileExtension; 
                    $destination = "../assets/{$imageName}";
                    if ($source == "courses") {
                        $destination = "../assets/{$imageName}";
                    }
                    move_uploaded_file($image["tmp_name"], $destination); 
                    $message = "Ok"; // Validation succeeded
                }
            }
        }

        return [$imageName, $message];
    }

?>


<!-- 
function fileUpload($image, $source = 'user'){
    $message = "";

    if($image["error"] == 4){ 
        $imageName = "User-avatar.svg.png";

        if($source == "courses"){
            $imageName = "Course.png";
        }

        $message = "No picture has been chosen, please upload your image later :)";
    }else{
        $checkIfImage = getimagesize($image["tmp_name"]); 
        $message = $checkIfImage ? "Ok" : "Not an image";
    }

    if($message == "Ok"){
        $ext = strtolower(pathinfo($image["name"],PATHINFO_EXTENSION)); 
        $imageName = uniqid(""). "." . $ext; 
        $destination = "../assets/{$imageName}"; 
        if($source == "courses"){
            $destination = "../assets/{$imageName}"; 
        }
        move_uploaded_file($image["tmp_name"], $destination); 
    }

    return [$imageName, $message]; 
} -->