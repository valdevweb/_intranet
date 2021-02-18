<?php
include('../../config/config.inc.php');
require '../../config/db-connect.php';

// htmlfile
function getFileNews($pdoOcc){
    $req=$pdoOcc->prepare("SELECT html_file, id FROM news WHERE html_file LIKE :html_file");
    $req->execute([
        ':html_file'    =>$_POST['htmlfile']
    ]);
    return $req->fetch(PDO::FETCH_ASSOC);
}
function addnewsFile($pdoOcc, $newid, $filename){
    $req=$pdoOcc->prepare("INSERT INTO news_file (id_occ_news, pj) VALUES (:id_occ_news, :pj)");
    $req->execute([
        ':id_occ_news'  =>$newid,
        ':pj'           =>$filename
    ]);
    return $req->rowCount();
}

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

        // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $response["message"] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $newid=getFileNews($pdoOcc);
            $done=addnewsFile($pdoOcc, $newid['id'], $filename);
            if($done==1){
                $response["message"] = "The file ". $filename. " has been uploaded.";
                $response["success"] = true;
                $response["path"] = $webdir;
            }else{
                $response["message"] = "Sorry, there was an error uploading your file.";
            }


        } else {
            $response["message"] = "Sorry, there was an error uploading your file.";
        }
    }
}
echo json_encode($response);