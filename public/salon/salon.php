<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------
// REQUIRED FUNCTIONS
//----------------------------------------------------
require '../../functions/salon.fn.php';
// require 'export.php';

//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------
//
$nbMag=nbMagSalon($pdoBt);
$inscriptions=displayInscr($pdoBt);
$nbRepas=nbRepasFn($pdoBt);
$dayOne=dayOneFn($pdoBt);
$dayTwo=dayTwoFn($pdoBt);
$visiteOne=visiteOneFn($pdoBt);
$visiteTwo= visiteTwoFn($pdoBt);
$nbInscription=count($inscriptions);
$listing="";
if($inscriptions){
	foreach ($inscriptions as $inscription)
	{
		//formatage des données


		// echo $inscription['id_galec'];
		$listing.='<tr><td>'.
		$inscription['id_galec']
		.'</td><td>'.
		$inscription['nom_mag']
		.'</td><td>'
		.$inscription['nom']
		.'</td><td>'.
		$inscription['prenom']
		.'</td><td>'.
		$inscription['fonction']
		.'</td><td>'.
		$inscription['date1']
		.'</td><td>'.
		$inscription['date2']
		.'</td><td>'.
		$inscription['visite']
		.'</td><td>'.
		$inscription['repas2']
		.'</td><td>'.
		$inscription['dateInscr']
		.'</td></tr>';
	}
}

	// echo "<pre>";
	// var_dump($inscriptions);
	// echo '</pre>';

//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="demande mag au service ".$gt ;
// $page=basename(__file__);
// $action="envoi d'une demande";
// addRecord($pdoStat,$page,$action, $descr);
//------------------------------------->
//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head.php';
require '../view/_navbar.php';

require 'salon.ct.php';



//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------





//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>