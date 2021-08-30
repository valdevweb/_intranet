<?php
require('../../config/config.inc.php');

define("DIR_UPLOAD_OPP",DIR_UPLOAD."opportunites\\");
define("URL_UPLOAD_OPP",URL_UPLOAD."opportunites/");


if(isset($_FILES['file']['name'])){
    for ($i=0; $i < count($_FILES['file']['name']); $i++) {
        $filename = $_FILES['file']['name'][$i];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $uploadFilename = time() . '.' . $ext;
        $location = DIR_UPLOAD_OPP.$uploadFilename;

        $filesize = $_FILES['file']['size'][$i];

        if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$location)){
            $src = SITE_ADDRESS."/public/img/icons/file.png";

            if(is_array(getimagesize($location))){
             $src = URL_UPLOAD_OPP.$uploadFilename;

         }
         $return_arr[$i] = array("name" => $filename,"size" => $filesize, "src"=> $src, "upload_filename" =>$uploadFilename);
     }


 }
 echo json_encode($return_arr);
}