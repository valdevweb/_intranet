<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require '../../Class/Db.php';
require '../../Class/CdesDao.php';
require '../../Class/CdesAchatDao.php';

require '../../Class/FormHelpers.php';


// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');

$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);

$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$paramForm="";
if(isset($_SESSION['temp'])){
	$param="WHERE id=".join(' OR id=',$_SESSION['temp']);
	$paramEncours="AND (id_encours=".join(' OR id_encours=',$_SESSION['temp']).')';

	$listProd=$cdesDao->getEncoursByIds($param);
	$listInfos=$cdesAchatDao->getInfosIdEncours($paramEncours);


}else{
	echo "Aucun article sélectionné. Veuillez cocher les articles sur lesquels vous souhaitez saisir des informations.<a href='cdes-encours.php'>Retour</a>";
	exit();
}

if(isset($_POST['save'])){
	$idDetail=key($_POST['qte_previ']);
	foreach ($_POST['qte_previ'] as $idDetail => $value) {
		if($_POST['date_previ'][$idDetail]!=null || $_POST['qte_previ'][$idDetail]!=null || $_POST['cmt'][$idDetail] !=null ){
			$dateGlobale=(empty($_POST['date_globale']))?null:$_POST['date_globale'];

			$date=(empty($_POST['date_previ'][$idDetail]))?$dateGlobale:$_POST['date_previ'][$idDetail];
			$qte=(empty($_POST['qte_previ'][$idDetail]))?null:$_POST['qte_previ'][$idDetail];
			$cmt=(empty($_POST['cmt'][$idDetail]))?"":$_POST['cmt'][$idDetail];
			$cdesAchatDao->insertInfos($idDetail,$date, $qte, $cmt);
		}
	}
	$successQ='?success=saved';
	unset($_POST);
	header("Location:".$_SERVER['PHP_SELF'].$successQ,true,303);

}

if(isset($_GET['del'])){
	$cdesAchatDao->deleteInfo($_GET['del']);
	$successQ='?success=deleted';
	unset($_POST);
	header("Location:".$_SERVER['PHP_SELF'].$successQ,true,303);
}
if(isset($_GET['update'])){
	$paramForm='?update='.$_GET['update'];
	$infoLiv=$cdesAchatDao->getInfo($_GET['update']);
}
if(isset($_POST['update'])){
	$date=(empty($_POST['date_previ_update']))?null:$_POST['date_previ_update'];
	$qte=(empty($_POST['qte_previ_update']))?null:$_POST['qte_previ_update'];
	$cmt=(empty($_POST['cmt_update']))?"":$_POST['cmt_update'];
	$cdesAchatDao->updateInfo($_GET['update'], $date, $qte, $cmt);
	$successQ='?success=updated';
	unset($_POST);
	header("Location:".$_SERVER['PHP_SELF'].$successQ,true,303);

}
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Saisie d'info livraison</h1>
		</div>
		<div class="col-auto">
			<a href="cdes-encours.php" class="btn btn-primary">Retour</a>
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
			<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']).$paramForm?>" method="post">
				<div class="row">
					<div class="col">
						Pour ne pas avoir à saisir une date prévisionnelle de livraison pour tous les articles, vous pouvez la saisir ci-dessous. Elle sera ajoutée automatiquement aux articles pour lesquels vous aurez saisi une quantité et/ou un commentaire
					</div>
				</div>
				<div class="row">
					<div class="col"></div>
					<div class="col-auto text-center">
						<div class="form-group form-inline">
							<label for="date_globale" class="text-orange font-weight-bold">Date prévisionnelle globale : </label>
							<input type="date" class="form-control ml-2" name="date_globale" id="date_globale">
						</div>
					</div>
					<div class="col"></div>
				</div>

				<?php if (!empty($listProd)): ?>
					<?php foreach ($listProd as $key => $prod): ?>
						<div class="row">
							<div class="col border-left border-right border-top pb-3">
								<div class="row bg-dark-grey py-2">
									<div class="col">
										<?=$prod['libelle_art']?>

									</div>
								</div>
								<div class="row ">
									<div class="col-lg-2">
										<span class="font-weight-bold">Article :</span>
										<?=$prod['article']?>
									</div>
									<div class="col-lg-2">
										<span class="font-weight-bold">Dossier :</span>
										<?=$prod['dossier']?>
									</div>
									<div class="col-lg-2">
										<span class="font-weight-bold">Marque :</span>
										<?=$prod['marque']?>

									</div>
									<div class="col-lg-2">
										<span class="font-weight-bold">Référence :</span>
										<?=$prod['ref']?>
									</div>
									<div class="col">
										<span class="font-weight-bold">EAN :</span>
										<?=$prod['ean']?>
									</div>
								</div>
								<div class="row ">
									<div class="col-auto">
										<span class="font-weight-bold">Date commande : </span>
										<?=($prod['date_cde']!=null)?date('d/m/y', strtotime($prod['date_cde'])):""?>

									</div>
									<div class="col-auto">
										<span class="font-weight-bold">Numéro : </span>
										<?=$prod['id_cde']?>

									</div>
									<div class="col-auto">
										<span class="font-weight-bold">Qte init. :</span>
										<?=$prod['qte_init']?>

									</div>
									<div class="col-auto">
										<span class="font-weight-bold"> Colis restants: </span>
										<?=$prod['qte_cde']?>
									</div>
									<div class="col-auto">
										<span class="font-weight-bold">UV restants : </span>
										<?=$prod['qte_uv_cde']?>
									</div>
									<div class="col-auto">
										<span class="font-weight-bold">PCB : </span>
										<?=$prod['cond_carton']?>
									</div>
									<div class="col-auto">
										<span class="font-weight-bold">Date livraison : </span>
										<?=($prod['date_liv']!=null)?date('d/m/y', strtotime($prod['date_liv'])):""?>

									</div>
								</div>
							</div>
						</div>

						<?php if (!empty($listInfos)): ?>
							<?php if(isset($listInfos[$prod['id']])):?>
								<div class="row">
									<div class="col border-left border-right border-bottom">
										<div class="row">
											<div class="col text-center">
												<h6 class="text-orange">Informations livraison :</h6>
											</div>
										</div>
										<?php foreach ($listInfos[$prod['id']] as $keyInfo => $value): ?>

											<div class="row">
												<div class="col font-weight-bold text-orange">
													<i class="fas fa-arrow-alt-circle-right pr-2"></i>Infos du <?=date('d/m/y', strtotime($listInfos[$prod['id']][$keyInfo]['date_insert']))?>
												</div>
											</div>
											<div class="row mb-2">
												<div class="col-lg-2">
													<span class="font-weight-bold">Date prévi : </span><?=($listInfos[$prod['id']][$keyInfo]['date_previ']!=null)?date('d/m/y', strtotime($listInfos[$prod['id']][$keyInfo]['date_previ'])):""?>
												</div>
												<div class="col-lg-2">
													<span class="font-weight-bold">Qte prévi : </span><?=$listInfos[$prod['id']][$keyInfo]['qte_previ']?>

												</div>
												<div class="col-lg-5">
													<span class="font-weight-bold">Commentaires : </span><?=$listInfos[$prod['id']][$keyInfo]['cmt']?>
												</div>
												<div class="col text-right">
													<a href="?update=<?=$listInfos[$prod['id']][$keyInfo]['id']?>" class="btn btn-secondary">Modifier</a>
													<a href="?del=<?=$listInfos[$prod['id']][$keyInfo]['id']?>" class="btn btn-danger">Supprimer</a>
												</div>

											</div>
										<?php endforeach ?>
									</div>
								</div>
							<?php endif ?>
						<?php endif ?>
						<?php if (isset($infoLiv)): ?>
							<div class="row border bg-light-grey rounded p-3 mb-5">
								<div class="col">
									<div class="row">
										<div class="col text-main-blue text-center">
											Modifier l'info livraison :
										</div>
									</div>
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<label>Date de livraison prévisionnelle :</label>
												<input type="date" class="form-control" name="date_previ_update" value="<?=$infoLiv['date_previ']?>">
											</div>
										</div>
										<div class="col-auto">
											<div class="form-group">
												<label>Quantité prévisionnelle :</label>
												<input type="text" class="form-control w-60" name="qte_previ_update" placeholder="qte prévi" value="<?=$infoLiv['qte_previ']?>">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label>Commentaires :</label>
												<textarea class="form-control" name="cmt_update"  row="3"><?=$infoLiv['cmt']?></textarea>
											</div>
										</div>
										<div class="col-lg-2 mt-5">
											<button class="btn btn-primary" name="update">Mettre à jour</button>

										</div>
									</div>
								</div>
							</div>
						<?php endif ?>
						<div class="row border rounded p-3 mb-5">
							<div class="col-auto">
								<div class="form-group">
									<label>Date de livraison prévisionnelle :</label>
									<input type="date" class="form-control" name="date_previ[<?=$prod['id']?>]" >
								</div>
							</div>
							<div class="col-auto">
								<div class="form-group">
									<label>Quantité prévisionnelle :</label>
									<input type="text" class="form-control w-60" name="qte_previ[<?=$prod['id']?>]" placeholder="qte prévi">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Commentaires :</label>
									<textarea class="form-control" name="cmt[<?=$prod['id']?>]"  row="3"></textarea>
								</div>
							</div>
						</div>

					<?php endforeach ?>

				<?php endif ?>

				<div class="row mb-5">
					<div class="col text-right">
						<button class="btn btn-primary" name="save">Enregistrer</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
require '../view/_footer-bt.php';
?>