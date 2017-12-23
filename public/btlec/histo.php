<?php
//----------------------------------
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------
require('../../functions/form.bt.fn.php');
//----------------------------------


//----------------------------------
//			html head
include('../view/_head.php');
include('../view/_navbar.php');

//----------------------------------
//			traitement



//alim list deroulante
$services=listServices($pdoBt);

//recup msg mag non clots
//histoDdesMag
$msg=histoDdesMag($pdoBt);

//ut ??????????????
// $gt=$_GET['gt'];
// $nbMsg=sizeof($msg);

// affichage fichier joint
function isAttached($dbData)
{
	global $version;
	$href="";
	if(!empty($dbData))
	{
		$ico="<i class='fa fa-paperclip fa-lg' aria-hidden='true'></i>";
		$href= "<span class='boldtxt'>Pièce jointe : &nbsp; &nbsp; &nbsp; &nbsp; <a href='http://172.30.92.53/".$version ."upload/mag/" . $dbData . "'>" .$ico ."</a></span>";
	}
	return $href;
}



include ('histo.ct.php');
include('../view/_footer.php');

