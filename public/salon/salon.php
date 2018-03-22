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
		//formatage des données
		if($inscription['entrepot1']!="")
		{
			$entrepot1='oui';
		}
		else
		{
			$entrepot1='non';
		}
		if($inscription['entrepot2']!="")
		{
			$entrepot2='oui';
		}
		else
		{
			$entrepot2='non';
		}
		if($inscription['scapsav1']!="")
		{
			$scapsav1='oui';
		}
		else
		{
			$scapsav1='non';
		}
		if($inscription['scapsav2'] !="")
		{
			$scapsav2='oui';
		}
		else
		{
			$scapsav2='non';
		}
		if($inscription['repas2'] !="")
		{
			$repas2='oui';
		}
		else
		{
			$repas2='non';
		}
		if($inscription['date1'] !="")
		{
			$date1='oui';
		}
		else
		{
			$date1='non';
		}
		if($inscription['date2'] !="")
		{
			$date2='oui';
		}
		else
		{
			$date2='non';
		}
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
		$date1
		.'</td><td>'.
		$entrepot1
		.'</td><td>'
		.$scapsav1
		.'</td><td>'.
		$date2
		.'</td><td>'.
		$entrepot2
		.'</td><td>'.
		$scapsav2
		.'</td><td>'.
		$repas2
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