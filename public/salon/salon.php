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
$inscriptions=displayInscr($pdoBt);
$listing="";
if($inscriptions){
	foreach ($inscriptions as $inscription)
	{
		// echo $inscription['id_galec'];
		$listing.='<tr><td>'.$inscription['id_galec'].'</td><td>'.$inscription['nom_mag'].'</td><td>'.$inscription['nom'].'</td><td>'.$inscription['prenom'] .'</td><td>'.$inscription['fonction'].'</td><td>'.$inscription['date'].'</td><td>'.$inscription['entrepot'].'</td><td>'.$inscription['repas'].'</td></tr>';
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