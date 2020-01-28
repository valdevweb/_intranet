<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// recup la ligne detail du litige pour tarif
function getDetail($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM details WHERE details.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

function updateDetail($pdoLitige,$artFound,$detail){
	$pu=$artFound['GESSICA.PFNP']/$artFound['GESSICA.PCB'];
	$valoLine=($detail['tarif']/$detail['qte_cde']*$detail['qte_litige'])-$pu*$_GET['inv_qte'];
	$req=$pdoLitige->prepare("UPDATE details SET inversion= :inversion, inv_article= :inv_article, inv_descr= :inv_descr, inv_tarif= :inv_tarif, valo_line= :valo_line, inv_fournisseur= :inv_fournisseur WHERE id=:id");
	$invTarif=$artFound['GESSICA.PANF']*
	$req->execute([
		':id'		=>$_GET['id'],
		':inversion'=>$artFound['GESSICA.Gencod'],
		':inv_article'	=>$artFound['GESSICA.CodeArticle'],
		':inv_descr'	=>$artFound['GESSICA.LibelleArticle'],
		':inv_tarif'	=>$pu,
		':valo_line'	=>$valoLine,
		':inv_fournisseur'=>$artFound['GESSICA.NomFournisseur'],
	]);
	return	$req->rowCount();
}

function getArt($pdoQlik){
	$req=$pdoQlik->prepare("SELECT * FROM basearticles WHERE id=:id");
	$req->execute([
		':id'	=>$_GET['id_inv']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function getSumLitige($pdoLitige, $idLitige){
	$req=$pdoLitige->prepare("SELECT sum(valo_line) as sumValo, dossiers.valo, id_reclamation FROM details LEFT JOIN dossiers ON details.id_dossier= dossiers.id WHERE details.id_dossier= :id");
	$req->execute([
		':id'		=>$idLitige
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateValoDossier($pdoLitige,$sumValo,$idLitige){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'			=>$sumValo,
		':id'			=>$idLitige
	]);
	return $req->rowCount();
}


if(isset($_GET['id_inv']) && isset($_GET['inv_qte'])){
	$artFound=getArt($pdoQlik);
	$detail=getDetail($pdoLitige);

	$maj=updateDetail($pdoLitige,$artFound,$detail);


	if($maj==1){
		$sumLitige=getSumLitige($pdoLitige, $detail['id_dossier']);
		$sumValo=$sumLitige['sumValo'];
		$update=updateValoDossier($pdoLitige,$sumValo, $detail['id_dossier']);
		$redir='?id='.$_GET['id'].'&success';
		unset($_POST);
		header("Location:edit-detail-litige.php?id=".$_GET['id'],true,303);
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
	<h1 class="text-main-blue py-5 ">Main title</h1>

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