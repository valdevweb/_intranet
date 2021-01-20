<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

header('Location:'. PORTAIL_SAV.'planning/mag-valid-rdv.php');

