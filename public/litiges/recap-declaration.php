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








//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossiers.id as id,details.id as detail_id,details.dossier,palette,facture, date_facture,DATE_FORMAT(date_facture,'%d-%m-%Y')as datefac,article, ean, dossier_gessica, descr,qte_cde,tarif,fournisseur FROM dossiers LEFT JOIN details ON dossiers.id=details.id_dossier WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$fLitige=getLitige($pdoLitige);




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
	<h1 class="text-main-blue py-5 ">Ouverture du dossier de litige n° <?=$fLitige['dossier']?></h1>
	<!-- start row -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<div class="alert alert-success">
				Votre dossier litige <strong>n° <?= $fLitige['dossier'] ?> </strong>a été transmis à BTLec pour étude. <br> Vous pourrez consulter l'avancement de votre dossier sur le portail sous cette référence.
			</div>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
		<!-- ./row -->

</div>
<?php
	require '../view/_footer-bt.php';
?>