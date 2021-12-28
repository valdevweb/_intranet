<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//----------------------------------
require '../../config/db-connect.php';

require('../../functions/form.fn.php');
require('../../Class/BtUserManager.php');
require('../../Class/MsgManager.php');
require('../../Class/MagDao.php');
require('../../Class/Mag.php');

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
// $msg=ddesMag($pdoBt);

$userManager=new BtUserManager();
$msgManager=new MsgManager();
$magManager=new MagDao($pdoMag);

$listServicesContact=$userManager->listServicesContact($pdoUser);
$selected="";
$userServiceList=[];
$userServiceList=$userManager->getUserServiceContact($pdoBt,$_SESSION['id_web_user']);


function checkSelectedDash($idService){
	if(isset($_POST['services'])){
		if($_POST['services']==$idService){
			return " selected ";
		}
	}elseif(!empty($_SESSION['id_service'])){
		if($_SESSION['id_service']==$idService){
			return " selected ";
		}
	}else{
		if($idService==""){
			return " selected ";
		}
	}
}


if(isset($_POST['services'])){

	$msg=$msgManager->getListDdeClosService($pdoBt, $_POST['services']);
}elseif(!empty($_SESSION['id_service'])){
	$msg=$msgManager->getListDdeClosService($pdoBt, $_SESSION['id_service']);
}else{
	$msg=$msgManager->getListDdeClos($pdoBt);

}



function formatPJ($incFileStrg){
	$href="";
	if(!empty($incFileStrg)){
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg=explode( '; ', $incFileStrg );
		for ($i=0;$i<count($incFileStrg);$i++){
			$ico="<i class='fa fa-paperclip fa-lg pl-5 pr-3 hvr-pop' aria-hidden='true'  ></i>";
			$href.= "<a class='pj' href='".URL_UPLOAD."mag/" . $incFileStrg[$i] . "' target='blank'>" .$ico ."ouvrir</a>";
		}
		$href="<p>".$href."</p>";

	}

	return $href;
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
function nbRep($pdoBt, $idMsg)
{
	$req=$pdoBt->prepare("SELECT count(t_replies.id_msg) AS nb_rep, t_replies.id_msg, max(t_replies.date_reply)  AS last_reply_date, t_replies.replied_by FROM replies t_replies WHERE t_replies.id_msg= :id_msg GROUP BY t_replies.id_msg");
	$req->execute(array(
	':id_msg' =>$idMsg
	 ));
	return $req->fetch(PDO::FETCH_ASSOC);
}

//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------

include('../view/_head-bt.php');
include('../view/_navbar.php');

//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------
include ('dashboard.ct.php');
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
include('../view/_footer-bt.php');

