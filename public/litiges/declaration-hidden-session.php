<?php
require('../../config/autoload.php');
require '../../config/db-connect.php';

if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}


$_SESSION['id_galec']= $_GET['galec'];

	header('Location:declaration-stepone.php');

