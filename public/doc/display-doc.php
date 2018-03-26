<?php

require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

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
$path=UPLOAD_DIR ."/documents/";

$odr=getDocument($pdoBt,"listing des ODR");
$assortiment=getDocument($pdoBt,"assortiment");
$panier=getDocument($pdoBt,"panier promo");
$gfk=getDocument($pdoBt,"resultats GFK");
$month=$months[$gfk["month"]];




//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-mig.php');
include('../view/_navbar.php');




// ------------------------------------------------------------------------------
include 'display-doc.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');

?>