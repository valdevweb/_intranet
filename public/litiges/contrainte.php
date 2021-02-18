<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';

require '../../Class/LitigeDao.php';
require '../../Class/UserHelpers.php';

require 'info-litige.fn.php';
require 'echanges.fn.php';



//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



$errors=[];
$success=[];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


function getMagSav($pdoSav,$galec){
	$req=$pdoSav->prepare("SELECT sav FROM mag WHERE galec = :galec");
	$req->execute([
		':galec'		=>$galec
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getLdSav($pdoSav, $sav, $module){
	$req=$pdoSav->prepare("SELECT email FROM mail_sav LEFT JOIN sav_users ON mail_sav.id_user_sav=sav_users.id WHERE mail_sav.sav= :sav AND module= :module");
	$req->execute([
		':sav'			=>$sav,
		':module'		=>$module
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getLdAchat($pdoUser,$serviceId){
	$req=$pdoUser->prepare("SELECT email FROM intern_users WHERE id_service= :service");
	$req->execute([
		':service'			=>$serviceId
	]);

	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function updateCtrl($pdoLitige, $etat)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>$etat,
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}

function getActionMsg($pdoLitige){
	$req=$pdoLitige->prepare("SELECT libelle FROM action WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['action']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

$litigeDao= new LitigeDao($pdoLitige);


$infos=$litigeDao->getInfos($_GET['id']);
$analyse=$litigeDao->getAnalyse($_GET['id']);
$litige=$litigeDao->getLitigeDossierDetailReclamMagEtatById($_GET['id']);


$firstCmt=getComment($pdoLitige);

//------------------------------------------------------
//			CONTRAINTE ACTUELLES
//------------------------------------------------------
/*

1 = envoi mail de demande de contrôle de stock
3= mettre le contrôle de stock à  oui
 */
if($_GET['contrainte']==2){
	include('contrainte-stock.php');
}
// controle de stock fait
elseif($_GET['contrainte']==1){
	include('contrainte-stock-retour.php');
}
// demande d'intervention du pole sav
elseif($_GET['contrainte']==4){
	include('contrainte-sav.php');
}
elseif($_GET['contrainte']==7){
	include('contrainte-video-rep.php');
}
// demande intervention service achats
elseif($_GET['contrainte']==8 || $_GET['contrainte']==9 || $_GET['contrainte']==10){
	include('contrainte-achats.php');
}
// envoi demande de recherhce video a Benoit
elseif($_GET['contrainte']==6){
	include('contrainte-video.php');
}
// commmssion sav
elseif($_GET['contrainte']==12){
	include('contrainte-commission-sav.php');
}



echo $_GET['contrainte'];
echo '<br>';
echo $_GET['id'];

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
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

</div>







<?php

require '../view/_footer-bt.php';

?>