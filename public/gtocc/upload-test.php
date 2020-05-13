<?php
include('../../config/config.inc.php');
//upload.php
if($_FILES["file"]["name"] != '')
{
 $test = explode('.', $_FILES["file"]["name"]);
 $ext = end($test);
 $name = rand(100, 999) . '.' . $ext;
 $location = "D:\\www\\_intranet\\upload\\flash\\". $name;
$webdir=UPLOAD_DIR.'\\flash\\'.$name;

 move_uploaded_file($_FILES["file"]["tmp_name"], $location);
 echo '<img src="'.$webdir.'" height="150" width="225" class="img-thumbnail" />';
}
?>
