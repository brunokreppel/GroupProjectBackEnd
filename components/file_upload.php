<?php
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
   }

?>

