<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

header('Location:'. PORTAIL_SAV.'planning/mag-valid-rdv.php');

