<?php
//----------------------------------
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------
require('../../functions/form.bt.fn.php');
require('../../functions/form.fn.php');

require "../../functions/stats.fn.php";
$page=basename(__file__);
$descr="consultation des demandes magasins";
$action="consultation";
$data=addRecord($pdoStat,$page,$action, $descr);
//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";
//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------
//			msg non clos
$msg=ddesMag($pdoBt);
//			liste déroulante
$services=listServices($pdoBt);
//				recherche du service du user connecté dans l'array services
$found_key = array_search($_SESSION['id_service'], array_column($services, 'id'));

//				découpe le tableau à partir de la valeur recherchée jusqu'à la fin du tableau
$userService =array_slice($services,$found_key,1);
$one=array_slice($services,$found_key+1);
//				découpe du début jusquà la valeur recherchée
$two=array_slice($services,0,$found_key);
function color($id,$services)
{
	$found_key = array_search($id, array_column($services, 'id'));
	$colorName= $services[$found_key]['color'];
	return $colorName;
}
//		msg sans réponse depuis plus de 5 jours
function warning($dateMsg)
{
	$today = new DateTime();
	$interval = $today->diff($dateMsg);
    $interval=$interval->format('%d');

	 if($interval>=5){
	 	return "<span class='warning'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>".$interval . " jours de retard</span>";
	 }
	 else
	 {
	 	return "";
	 }
}

//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------

include('../view/_head.php');
include('../view/_navbar.php');

//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------
include ('dashboard.ct.php');
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
include('../view/_footer.php');

