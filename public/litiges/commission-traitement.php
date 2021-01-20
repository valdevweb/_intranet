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

// require_once '../../vendor/autoload.php';


// muller=959
// user=981
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function updateCommission($pdoLitige,$com)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=>$com,
		':date_commission'	=>date('Y-m-d H:i:s'),
		':id'		=>$_GET['id']

	]);
	return $req->rowCount($pdoLitige);
}

function addAction($pdoLitige, $idContrainte){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=>$_GET['id'],
		':libelle'			=>$_POST['cmt'],
		':id_contrainte'	=>$idContrainte,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=>date('Y-m-d H:i:s'),
	]);
	return $req->rowCount();
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


if($_SESSION['id_web_user'] !=959 && $_SESSION['id_web_user'] !=981)
{

	header('Location:bt-litige-encours.php?notallowed');
	exit();
}

if(isset($_GET['etat']) && isset($_GET['id']))
{
	// si pending, on le passe en validé

	// si validated on le devalide
	if($_GET['etat']=='validated'){
		$result=updateCommission($pdoLitige,0);
	}
	if($result==1){
		header('Location:bt-litige-encours.php#'.$_GET['id']);
	}
	else{
		header('Location:bt-litige-encours.php?updatefailed');
	}
}

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


	<!-- ./container -->
</div>




<?php
require '../view/_footer-bt.php';
?>