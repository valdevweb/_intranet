<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
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



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function addBoxDetail($pdoLitige,$article,$dossier,$descr,$fourn,$qte,$tarif)
{
	$req=$pdoLitige->prepare("INSERT INTO details_box(id_details, article, dossier, descr, fournisseur, qte, tarif) VALUES (:id_details, :article, :dossier, :descr, :fournisseur, :qte, :tarif)");
	$req->execute(array(
		':id_details'	=>$_SESSION['id_detail'],
		 ':article'	=>$article,
		 ':dossier'	=>$dossier,
		 ':descr'	=>$descr,
		 ':fournisseur'	=>$fourn,
		 ':qte'	=>$qte,
		 ':tarif'	=>$tarif,

	));
	return $req->rowCount();
}

function updateDetails($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE details SET tarif= :tarif, boxok= 1 WHERE id= :id");
	$req->execute(array(
		':tarif'	=>$_SESSION['boxvalo'],
		':id'		=>$_SESSION['id_detail']
	));
	return $req->rowCount();
}
function updateDossiers($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET flag_valo= 0 WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_SESSION['box']))
{
	foreach ($_SESSION['box'] as $box)
	{
		$row=addBoxDetail($pdoLitige,$box['article'],$box['dossier'],$box['libelle'],$box['fournisseur'],$box['qte'],$box['tarif']);
	}
	if($row==1)
	{
		$maj=updateDetails($pdoLitige);
	}
	if($maj==1)
	{
		$majdossier=updateDossiers($pdoLitige);
		if($majdossier==1)
		{
		$success[]='maj effectutée';
		unset($_SESSION['box']);
		unset($_SESSION['id_detail']);
		unset($_SESSION['boxvalo']);
		$loc='Location:bt-detail-litige.php?id='.$_GET['id'];
		header($loc);
		}
		else
		{
		$errors[]='erreur de mise à jour du dossier';
		}

	}
	else{
		$errors[]='erreur de mise à jour';


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
	<h1 class="text-main-blue py-5 ">Mise à jour box</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>