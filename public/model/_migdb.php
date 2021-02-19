<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}




$db=new Db();
$pdoUser=$db->getPdo('web_users');