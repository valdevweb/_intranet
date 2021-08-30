<?php
require('../../config/config.inc.php');


if(isset($_FILES['file']['name'])){
    for ($i=0; $i < count($_FILES['file']['name']); $i++) {

        $filename = $_FILES['file']['name'][$i];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $uploadFilename = time() . '.' . $ext;
        $location = DIR_UPLOAD.'gesap\\'.$uploadFilename;

        $filesize = $_FILES['file']['size'][$i];


        if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$location)){
            $src = SITE_ADDRESS."/public/img/icons/file.png";

    // checking file is image or not
            if(is_array(getimagesize($location))){
                $src = URL_UPLOAD.'gesap\\'.$uploadFilename;
            }
            $data[$i] = array("name" => $filename,"size" => $filesize, "src"=> $src, "upload_filename" =>$uploadFilename);
        }

    }
    echo json_encode($data);
}
