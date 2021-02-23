<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------
// css dynamique
//----------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);



$months=array('','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');

function getDocument($pdoBt,$type)
{
$req=$pdoBt->prepare("SELECT type, DATE_FORMAT(date, '%d/%m/%Y') as datefull,file,MONTH(date) as month, YEAR(date) as year FROM documents WHERE type= :type");
$req->execute(array(
	':type' =>$type
));
$result=$req->fetch(PDO::FETCH_ASSOC);
return $result;

}
$path=URL_UPLOAD ."/documents/";

$odr=getDocument($pdoBt,"listing des ODR");
$assortiment=getDocument($pdoBt,"assortiment et panier promo");
$panier=getDocument($pdoBt,"panier promo");
$gfk=getDocument($pdoBt,"resultats GFK");
$tel=getDocument($pdoBt,"Tickets et BRII");
$mdd=getDocument($pdoBt,"point stock MDD");

$month=$months[$gfk["month"]];




//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');




// ------------------------------------------------------------------------------
include 'display-doc.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer-bt.php');

?>