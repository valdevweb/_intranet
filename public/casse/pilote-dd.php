<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../config/db-connect.php';
require_once '../../vendor/autoload.php';
require('casse-getters.fn.php');

require('../../Class/casse/TrtDao.php');
$trtDao=new TrtDao($pdoCasse);





if(isset($_GET['id'])){
	$expInfo=getExpAndPalette($pdoCasse,$_GET['id']);
	$nb=count($expInfo);
}
else{
	$loc='Location:casse-dashboard.php?error=1';
	header($loc);
}

if(VERSION=='_'){
	$dest=['valerie.montusclat@btlec.fr'];
	$cc=['valerie.montusclat@btlec.fr'];
}
else{
	$dest=['pilotageprepa@btlec.fr'];
	$cc=['valerie.montusclat@btlec.fr', 'christelle.trousset@btlec.fr'];

}
$link='<a href="'.SITE_ADDRESS.'/index.php?casse/pilote-palette-ok.php?id='.$_GET['id'].'"> en cliquant ici</a>';
$table='';
$table.='<table style="border-collapse: collapse; border: 1px solid grey;padding:10px;"><tr style="background-color:firebrick;color:white;"><th style="border: 1px solid grey;padding:10px;">Palette 4919</th><th style="border: 1px solid grey;padding:10px;">Palette contremarque</th></tr>';
foreach ($expInfo as $exp) {
	$table.='<tr><td style="border: 1px solid grey;padding:10px;">'.$exp['palette'].'</td><td style="border: 1px solid grey;padding:10px;">'.$exp['contremarque'].'</td></tr>';
}
$table.='</table>';


$htmlMail = file_get_contents('mail/mail-pilote-dd.php');
$htmlMail=str_replace('{MAG}',$expInfo[0]['btlec'],$htmlMail);
$htmlMail=str_replace('{NB}',$nb,$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);
$htmlMail=str_replace('{TABLE}',$table,$htmlMail);
$subject='Portail BTLec Est - Casses : demande de contrôle et mise en RAQ';

// ---------------------------------------
// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')

->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
->setTo($dest)
->setCc($cc);

$delivered=$mailer->send($message);
if($delivered !=0){
	$trtDao->insertTrtHisto($_GET['id'], $_GET['id_trt']);
	header('Location:casse-dashboard.php?#exp-'.$_GET['id']);
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
	DEBUT CONTENU CONTAINER
	*********************************-->
	<div class="container">

	</div>

	<?php
	require '../view/_footer-bt.php';
?>