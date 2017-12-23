<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	// echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
else {
	// echo "vous êtes connecté avec :";
	$_SESSION['id'];
}
$_SESSION['page_request']=$_SERVER['REQUEST_URI'];
require('../../functions/form.bt.fn.php');
//______________________________________

include('../view/_head.php');
include('../view/_navbar.php');

//lien qui sera  qui sera envoyée par mail

$services=listServices($pdoBt);

$gt=$_GET['gt'];
$tmp=showMagMsg($pdoBt,$gt);
$nbMsg=sizeof($msg);

$msg=allMagMsg($pdoBt);

include ('request.ct.php');
include('../view/_footer.php');

