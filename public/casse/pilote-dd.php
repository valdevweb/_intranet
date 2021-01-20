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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

require_once '../../vendor/autoload.php';

require('casse-getters.fn.php');

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function updatePalette($pdoCasse,$id){

	$req=$pdoCasse->prepare("UPDATE palettes SET date_dd_pilote = NOW() WHERE id= :id");
	$req->execute([
		':id'	=> $id
	]);
	return $req->rowCount();

}




if(isset($_GET['id']))
{
	$expInfo=getExpAndPalette($pdoCasse,$_GET['id']);
	// echo "<pre>";
	// print_r($expInfo);
	// echo '</pre>';
	$nb=count($expInfo);

}
else{
	$loc='Location:bt-casse-dashboard.php?error=1';
	header($loc);
}

if(VERSION=='_'){
	$dest='valerie.montusclat@btlec.fr';
	$cc=['valerie.montusclat@btlec.fr'];
}
else{
	$dest='pilotageprepa@btlec.fr';
	$cc=['valerie.montusclat@btlec.fr', 'christelle.trousset@btlec.fr'];

}
$link='<a href="'.SITE_ADDRESS.'/index.php?casse/pilote-palette-ok.php?id='.$_GET['id'].'"> en cliquant ici</a>';
$table='';
$table.='<table style="border-collapse: collapse; border: 1px solid grey;padding:10px;"><tr style="background-color:firebrick;color:white;"><th style="border: 1px solid grey;padding:10px;">Palette 4919</th><th style="border: 1px solid grey;padding:10px;">Palette contremarque</th></tr>';
foreach ($expInfo as $exp) {
	$table.='<tr><td style="border: 1px solid grey;padding:10px;">'.$exp['palette'].'</td><td style="border: 1px solid grey;padding:10px;">'.$exp['contremarque'].'</td></tr>';
}
$table.='</table>';

$htmlMail = file_get_contents('mail-pilote-dd.php');
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
// ->setTo(['pilotageprepa@btlec.fr'])

->setCc($cc);
// ->addBcc('valerie.montusclat@btlec.fr');
		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0
$delivered=$mailer->send($message);
if($delivered !=0)
{
	$majko=false;
	$idExp=$_GET['id'];
	$palettes=getDetailExp($pdoCasse,$idExp);
	foreach ($palettes as $palette) {
		$up=updatePalette($pdoCasse,$palette['id']);
		if($up!=1){
			$majko=true;
		}
	}
	if(!$majko){
		$loc='Location:bt-casse-dashboard.php?mailPilote';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base palette";
	}
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