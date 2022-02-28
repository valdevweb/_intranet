<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require '../../Class/litiges/LitigeDao.php';
require '../../Class/litiges/LitigeHelpers.php';
require '../../Class/FormHelpers.php';
require '../../Class/mag/MagHelpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
function searchDbArt($pdoQlik){

	$req=$pdoQlik->prepare("SELECT * FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean");
	$req->execute([
		':ean'		=>'%'.$_POST['ean'].'%'
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return $data;
}

function searchDbArticleByEanOrArticle($pdoQlik){
	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeArticle` as article, `GESSICA.CodeDossier` as dossier, `GESSICA.PANF` as panf, `GESSICA.PCB` as pcb, `GESSICA.LibelleArticle` as libelle, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur, `GESSICA.Gencod` as gencod FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean OR `GESSICA.CodeArticle` LIKE :ean");
	$req->execute([
		':ean'		=>'%'.$_POST['article'].'%'
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function searchDbLitigeByEanOrArticle($pdoQlik, $btlec){
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE (gencod LIKE :ean OR  article LIKE :ean) AND btlec= :btlec");
	$req->execute([
		':ean'		=>'%'.$_POST['article'].'%',
		':btlec'		=>$btlec
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function searchDbOccByEan($pdoOcc){
	$req=$pdoOcc->prepare("SELECT article_palette, designation as libelle, ean as gencod, quantite as qte, pa as tarif FROM palettes_articles WHERE ean LIKE :ean");
	$req->execute([
		':ean'		=>'%'.$_POST['article'].'%',
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function addArticle($pdoLitige, $key, $article, $dossier, $palette, $facture, $dateFacture, $qteCde, $cnuf,$occArticlePalette,$valo ){
	$req=$pdoLitige->prepare("INSERT INTO details (id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif,  fournisseur, cnuf, qte_litige, id_reclamation, occ_article_palette, valo_line) VALUES (:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :qte_litige, :id_reclamation, :occ_article_palette, :valo_line)");
	$req->execute([
		':id_dossier'		=>$_POST['id_litige'][$key],
		':dossier'		=>$_POST['dossier_litige'][$key],
		':palette'		=>$palette,
		':facture'		=>$facture,
		':date_facture'		=>$dateFacture,
		':article'		=>$article,
		':ean'		=>$_POST['ean'][$key],
		':dossier_gessica'		=>$dossier,
		':descr'		=>$_POST['descr'][$key],
		':qte_cde'		=>$qteCde,
		':tarif'		=>$_POST['tarif'][$key],
		':fournisseur'		=>$_POST['fournisseur'][$key],
		':cnuf'		=>$cnuf,
		':qte_litige'		=>$_POST['qte_litige'][$key],
		':id_reclamation'		=>$_POST['id_reclamation'][$key],
		':occ_article_palette'	=>$occArticlePalette,
		':valo_line'		=>$valo,
	]);
	return $pdoLitige->lastInsertId();
}

function modifValo($pdoLitige){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'	=>$_POST['valo_totale'],
		':id'	=>$_GET['id']
	]);
}

function updateValo($pdoLitige){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo =(SELECT sum(valo_line) as sumValo FROM details WHERE id_dossier= :id_dossier) WHERE id= :id_dossier");
	$req->execute([
		':id_dossier'	=>$_GET['id']
	]);


}
$errors=[];
$success=[];



$litigeDao=new LitigeDao($pdoLitige);

$detailLitige=$litigeDao ->getDetail($_GET['id']);
$listReclamationsIncludingMasked=LitigeHelpers::listReclamationIncludingMasked($pdoLitige);
$listReclamations=LitigeHelpers::listReclamation($pdoLitige);
$listReclamationsEdit=LitigeHelpers::listReclamationEdit($pdoLitige);


if(isset($_POST['update_valo'])){
	modifValo($pdoLitige);
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}


if(isset($_POST['update_detail'])){
	// on récupère l'index du bouton submit qui a été  cliqué
	$key=implode(array_keys($_POST['update_detail']));

	$litigeDao->saveDetailInModif($_POST['id_detail'][$key]);
	//si on etait sur une inversion de référence (inv_ref existe), on a 2 cas :
	//on reste en inversion de réf après la modification,
	//on décide ne ne plus être en inversion de référence après la modification
	//et dans ce dernier cas, on remet les champs inv à 0.
	//si on n'était pas sur une inversion de réfénrece, on initialise aussi les champs inv à 0
	if(isset($_POST['inv_ref'][$key]) && $_POST['id_reclamation'][$key]==5){
		$inversion=$_POST['inversion'][$key];
		$invArticle=$_POST['inv_article'][$key];
		$invQte=$_POST['inv_qte'][$key];
		$invTarif=$_POST['inv_tarif'][$key];

	}else{
		$inversion=$invArticle=$invQte=$invTarif=null;

	}

	$litigeDao->updateDetailAllCases($_POST['id_detail'][$key], $_POST['qte_cde'][$key], $_POST['tarif'][$key], $_POST['id_reclamation'][$key], $_POST['qte_litige'][$key], $inversion, $invArticle, $invQte, $invTarif, $_POST['valo_line'][$key]);
	$idTableModif=$litigeDao->saveDetailInModif($_POST['id_detail'][$key], true);
	// echo 'id table modif'. $idTableModif;


	if($idTableModif>0){
		if($litigeDao->updateModif( $idTableModif,1)){
			updateValo($pdoLitige);
			$successQ='?id='.$_GET['id'];
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}else{
			$errors[]="une erreur est survenue";
		}
	}else{
		$errors[]= "impossible de recopier les données initiales";

	}
}

if(isset($_POST['search'])){
	$found=searchDbArt($pdoQlik);
	if(!$found){
		$result="EAN non trouvé";
	}
}

if(isset($_POST['delete_detail'])){
	$key=implode(array_keys($_POST['delete_detail']));
	$idDetail=$_POST['id_detail'][$key];
	$idTableModif=$litigeDao->saveDetailInModif($idDetail, true);
	$litigeDao->deleteDetail($idDetail);
	updateValo($pdoLitige);
	$litigeDao->updateModif($idTableModif,3);
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if (isset($_POST['search_2'])) {
	if($_POST['cde_mag']==1){
		$btlec=MagHelpers::btlec($pdoMag, $detailLitige[0]['galec']);
		$listArticles=searchDbLitigeByEanOrArticle($pdoQlik, $btlec);
	}elseif($_POST['cde_mag']==2){
		$listArticles=searchDbArticleByEanOrArticle($pdoQlik);
	}elseif ($_POST['cde_mag']==3) {
		$listArticles=searchDbOccByEan($pdoOcc);

	}

}

if(isset($_POST['add_article'])){
	// on récupère l'index du bouton submit qui a été  cliqué
	$key=implode(array_keys($_POST['add_article']));
	if(empty($_POST['qte_litige'][$key]) || empty($_POST['id_reclamation'][$key])){
		$errors[]="Merci de renseigner la quantité et le type de réclamation";
	}
	if(empty($errors)){
		if(empty($_POST['qte_cde'][$key])){
			$qteCde=1;
			$valo=$_POST['tarif'][$key]*$_POST['qte_litige'][$key];

		}else{
			$qteCde=$_POST['qte_cde'][$key];
			$valo=$_POST['tarif'][$key]/$_POST['qte_cde'][$key]*$_POST['qte_litige'][$key];
		}
		$article=!empty($_POST['article'][$key])?$_POST['article'][$key]:null;
		$dossier=!empty($_POST['dossier'][$key])?$_POST['dossier'][$key]:null;
		$cnuf=!empty($_POST['cnuf'][$key])?$_POST['cnuf'][$key]:null;

		$facture=isset($_POST['facture'][$key])?$_POST['facture'][$key]:null;
		$dateFacture=isset($_POST['date_facture'][$key])?$_POST['date_facture'][$key]:null;
		$palette=isset($_POST['palette'][$key])?$_POST['palette'][$key]:null;
		$occArticlePalette=isset($_POST['occ_article_palette'][$key])?$_POST['occ_article_palette'][$key]:null;

		$lastInsertId=addArticle($pdoLitige, $key,$article, $dossier, $palette, $facture, $dateFacture, $qteCde, $cnuf, $occArticlePalette, $valo);
		$idTableModif=$litigeDao->saveDetailInModif($lastInsertId, true);

		if($litigeDao->updateModif($idTableModif,2)){
			updateValo($pdoLitige);
		}
		$successQ='?id='.$_GET['id'].'&success=add';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}

}

if(isset($_GET['success'])){
	$arrSuccess=[
		'add'=>'Article ajouté avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
}

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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

	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue ">Modification du  litige  <?=$detailLitige[0]['dossier']?></h1>
		</div>
		<div class="col-auto">
			<a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a>
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

	<!-- valo totale -->
	<div class="row">
		<div class="col">
			<h5 class="text-main-blue">Modification de la valo totale du dossier :</h5>
		</div>
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">

				<div class="row">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="valo_totale" id="valo_totale" value="<?=$detailLitige[0]['valo']?>">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-primary" name="update_valo">Modifier</button>
					</div>
				</div>
			</form>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5 class="text-main-blue">Modification des détails articles du dossier :</h5>
		</div>
	</div>
	<?php include 'edit-litige\01-modif.php' ?>
	<div class="row mt-5">
		<div class="col">
			<h5 class="text-main-blue">Ajout d'articles à la déclaration :</h5>
		</div>
	</div>
	<?php include 'edit-litige\02-add.php' ?>


	<!-- ./container -->
</div>



<?php
require '../view/_footer-bt.php';
?>

