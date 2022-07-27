<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';
require '../../Class/Uploader.php';
require '../../Class/mag/MagHelpers.php';
require '../../Class/Helpers.php';
require '../../Class/CrudDao.php';
require '../../Class/Db.php';
require('../../Class/casse/TrtDao.php');
$trtDao = new TrtDao($pdoCasse);



function updatePaletteDateCloture($pdoCasse)
{
	$req = $pdoCasse->prepare("UPDATE palettes SET date_clos= :date_clos, statut= :statut WHERE id_exp= :id");
	$req->execute([
		':id'	=> $_GET['id'],
		':date_clos' => date('Y-m-d H:i:s'),
		':statut'		=> 3
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}

function closeCasse($pdoCasse)
{
	$req = $pdoCasse->prepare("UPDATE casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp=exps.id SET casses.etat=1 WHERE exps.id= :id");
	$req->execute([
		':id'	=> $_GET['id']
	]);
	return $req->rowCount();
}

function closeExp($pdoCasse, $file)
{
	$req = $pdoCasse->prepare("UPDATE exps SET exp=1, file= :file WHERE id= :id");
	$req->execute([
		':id'	=> $_GET['id'],
		':file'		=> $file
	]);

	return $req->rowCount();
}

function closeExpNofac($pdoCasse)
{
	$req = $pdoCasse->prepare("UPDATE exps SET exp=1 WHERE id= :id");
	$req->execute([
		':id'	=> $_GET['id'],

	]);
	return $req->rowCount();
}




//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors = [];
$success = [];

$db = new Db();


$pdoUser = $db->getPdo('web_users');
$pdoCasse = $db->getPdo('casse');
$pdoMag = $db->getPdo('magasin');


$casseCrud = new CrudDao($pdoCasse);


$infoExp = $casseCrud->getOneById("exps", $_GET['id']);


$mag = MagHelpers::deno($pdoMag, $infoExp['galec']);


if (isset($_POST['clos'])) {
	$file = "";
	// mag
	if ($infoExp['id_affectation'] == 1) {
		$dirUpload = DIR_UPLOAD . 'casse\\';
		$uploader   =   new Uploader();
		$uploader->setDir($dirUpload);
		$uploader->allowAllFormats();
		$uploader->setMaxSize(5);

		if ($uploader->uploadFile('file')) {
			$file = $uploader->getUploadName();
		}
	}
	if (empty($errors)) {
		$closeExp = closeExp($pdoCasse, $file);
		$added = updatePaletteDateCloture($pdoCasse);
		$close = closeCasse($pdoCasse);
		if ($infoExp['id_affectation'] == 2 || $infoExp['id_affectation'] == 1) {
			include 'casse-clos/send-mail.php';
		}
		$trtDao->insertTrtHisto($_GET['id'], $_GET['id_trt']);
		header('Location:casse-dashboard.php?#exp-' . $_GET['id']);
	}
}




include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
	DEBUT CONTENU CONTAINER
	*********************************-->
<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">Clôture de l'expédition n°<?= $_GET['id'] ?></h1>
		</div>

		<div class="col"><?= Helpers::returnBtn('casse-dashboard.php') ?></div>
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
	<?php if ($infoExp) : ?>
		<?php if ($infoExp['mt_fac'] == null) : ?>
			<div class="row mt-5">
				<div class="col-lg-1"></div>
				<div class="col">
					<h5 class="text-center"><i class="fas fa-bomb text-red fa-2x  pr-3"></i>Cette expédition n'a pas été facturée, êtes vous sûr de vouloir la clôturer ?</h5>
				</div>
				<div class="col-lg-1"></div>
			</div>
		<?php endif ?>
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<div class="alert alert-primary text-center">
					<div class="text-left pb-3">Rappel des montants facturés le <b><?= date('d-m-Y', strtotime($infoExp['date_fac'])) ?></b> :</div>
					<table>
						<tr>
							<td>Facture : </td>
							<td class="text-right"><?= $infoExp['mt_fac'] ?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir blanc :</td>
							<td class="text-right"><?= $infoExp['mt_blanc'] ?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir brun :</td>
							<td class="text-right"><?= $infoExp['mt_brun'] ?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir Gris : </td>
							<td class="text-right"><?= $infoExp['mt_gris'] ?>&euro;</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<?php if ($infoExp['id_affectation'] == 3 || $infoExp['id_affectation'] == 1) : ?>
			<div class="row pb-5">
				<div class="col-lg-1"></div>
				<div class="col">
					<form class="form-inline" method="post" action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'] . '&id_trt=' . $_GET['id_trt'] ?>" enctype="multipart/form-data">
						<div class="form-group">
							<label for='file'>Joindre le compte rendu : </label><input type='file' class='form-control-file' id='file' name='file'>
						</div>
						<button type="submit" class="btn btn-primary mt-4" name="clos">Clôturer</button>
					</form>
				</div>
				<div class="col-lg-1"></div>
			</div>
		<?php else : ?>
			<div class="row mb-5 pb-5">
				<div class="col-lg-1"></div>
				<div class="col text-center">
					<form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'] . '&id_trt=' . $_GET['id_trt'] ?>" method="post">
						<button type="submit" class="btn btn-primary mt-4" name="clos">Clôturer</button>
					</form>
				</div>
				<div class="col-lg-1"></div>
			</div>
		<?php endif ?>
	<?php endif ?>
</div>



<?php
require '../view/_footer-bt.php';
?>