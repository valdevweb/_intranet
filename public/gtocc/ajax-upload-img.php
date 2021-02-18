<?php
include('../../config/config.inc.php');

require '../../config/db-connect.php';

$response = ["success"=>false, "message"=>"Bad request"];
if(isset($_POST["submit"])) {
    $response["message"] ="Unknown error occurred";

    $target_dir= DIR_UPLOAD. 'flash\\';

    $temp = explode(".", $_FILES["file"]["name"]);
    $filename = $temp[0].date('YmdHis') . '.' . end($temp);


    $target_file = $target_dir . $filename;
    $webdir=URL_UPLOAD.'flash/'.$filename;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $response["message"] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $response["message"] = "File is not an image.";
        $uploadOk = 0;
    }

        // Check if file already exists
    if (file_exists($target_file)) {
        $response["message"] = "Sorry, file already exists.";
        $uploadOk = 0;
    }
        // Check file size
    if ($_FILES["file"]["size"] > 500000) {
        $response["message"] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
        // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $response["message"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
        // Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $response["message"] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $response["message"] = "The file ". $filename. " has been uploaded.";
        $response["success"] = true;
        $response["path"] = $webdir;

    } else {
        $response["message"] = "Sorry, there was an error uploading your file.";
    }
}
}
echo json_encode($response);