<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

require '../../Class/Db.php';



$db=new Db();
$pdoUser=$db->getPdo('web_users');