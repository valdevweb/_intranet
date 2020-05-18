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
include('../../Class/Helpers.php');


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);






//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, dossiers.id_web_user, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom, valo, flag_valo, id_reclamation,inv_palette,inv_qte,id_robbery, commission, box_tete, box_art,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.facture,details.article,details.tarif,details.qte_cde, details.qte_litige,details.valo_line,details.dossier_gessica,details.descr,details.fournisseur,details.pj,DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, details.serials,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
function copyActualDetail($pdoLitige){
	$req=$pdoLitige->prepare("INSERT INTO details_modif (id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj) SELECT id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj FROM details WHERE details.id= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]);

	return $pdoLitige->lastInsertId();

}

function updateModifDeleted($pdoLitige, $lastinsertid){
	$req=$pdoLitige->prepare("UPDATE details_modif SET modif= :modif, updated_by= :updated_by, updated_on= :updated_on WHERE id= :id");
	$req->execute([
		':modif'		=>2,
		':updated_by'	=>$_SESSION['id_web_user'],
		':updated_on'	=>date('Y-m-d H:i:s'),
		':id'			=>$lastinsertid
	]);
}

function deleteDetail($pdoLitige){
	$req=$pdoLitige->prepare("DELETE FROM details WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['iddetaildelete']

	]);
	return $req->rowCount();

}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


$fLitige=getLitige($pdoLitige);

if(isset($_GET['iddetaildelete'])){
	$lastinsertid=copyActualDetail($pdoLitige);
	updateModifDeleted($pdoLitige, $lastinsertid);
	$del=deleteDetail($pdoLitige);
	if($del==1){
		$successQ='?id='.$_GET['id'].'&success=del';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}

}
if(isset($_GET['success'])){
	$arrSuccess=[
		'del'=>'ligne article supprimée',
	];
	$success[]=$arrSuccess[$_GET['success']];
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
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">Dossier N°<?=$fLitige[0]['dossier']?></h1>

		</div>
		<div class="col"><?=Helpers::returnBtn('bt-detail-litige.php?id='.$fLitige[0]['id_dossier'])?>
	</div>
</div>

<div class="row">
	<div class="col-lg-1"></div>
	<div class="col">
		<?php
		include('../view/_errors.php');
		?>
	</div>
	<div class="col-lg-1"></div>
</div>
<div class="row">
	<div class="col">
		<h5 class="text-main-blue pb-3">Supprimer un produit :</h5>

	</div>

</div>

<?php
	// étatn donné que l'on peut supprimer des articles, on peut se retrouver avec un dossier vide donc flitige vide
if(isset($fLitige) && !empty($fLitige)){
	// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette

	if($fLitige[0]['id_reclamation']==7)
	{
	// traitement pour affichage détail palette
		$detailInv=false;
		$detailCde=false;
		$majrecherchepalette='';
		$pj='';
		$invPal = sommeInvPalette($pdoLitige);
		$cdePal=sommePaletteCde($pdoLitige);
		include 'dt-invpalette-edit.php';
	}else{
		include 'dt-prod-edit.php';

	}

}
?>

<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>


