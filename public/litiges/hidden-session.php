<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}


$_SESSION['id_galec']= $_GET['galec'];

	header('Location:declaration-basic.php');

