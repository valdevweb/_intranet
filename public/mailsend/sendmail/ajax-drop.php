<?php

require('../../../config/autoload.php');

require '../../../Class/Db.php';
require '../../../Class/CrudDao.php';
require '../../../Class/UserDao.php';


$db=new Db();
$pdoUser=$db->getPdo('web_users');

$userDao=new UserDao($pdoUser);

if(isset($_FILES['file']['name'])){
    for ($i=0; $i < count($_FILES['file']['name']); $i++) {

        $filename = $_FILES['file']['name'][$i];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filenameNoExt = basename($filename, '.'.$ext);
        $uploadFilename = $filenameNoExt.'-'.time() . '.' . $ext;
        $location = DIR_UPLOAD.'email\\'.$uploadFilename;

        $filesize = $_FILES['file']['size'][$i];
        if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$location)){
            $src = SITE_ADDRESS."/public/img/icons/file.png";

             // checking file is image or not
            if(is_array(getimagesize($location))){
                $src = URL_UPLOAD.'email\\'.$uploadFilename;
            }
            $data[$i] = array("name" => $filename,"size" => $filesize, "src"=> $src, "upload_filename" =>$uploadFilename);
        }

    }
    echo json_encode($data);
}




