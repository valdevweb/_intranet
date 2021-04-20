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

if(isset($_POST['submit'])){
	$idDetail=key($_POST['qte_previ']);
	foreach ($_POST['qte_previ'] as $idDetail => $value) {
		$date=(empty($_POST['date_previ'][$idDetail]))?null:$_POST['date_previ'][$idDetail];
		$qte=(empty($_POST['qte_previ'][$idDetail]))?null:$_POST['qte_previ'][$idDetail];
		$cmt=(empty($_POST['cmt'][$idDetail]))?"":$_POST['cmt'][$idDetail];
		$update=$cdesAchatDao->insertInfos($idDetail,$date, $qte, $cmt);
	}

	$successQ='?success=updated';
	unset($_POST);
	// unset($_SESSION['temp']);
	// header("Location: cdes-encours.php".$successQ,true,303);
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
				<?php if (!empty($listProd)): ?>
					<?php foreach ($listProd as $key => $prod): ?>
						<div class="row bg-blue py-2">
							<div class="col-lg-2">
								Article :
							</div>
							<div class="col-lg-2">
								Dossier :
							</div>
							<div class="col-lg-2">
								Marque :
							</div>
							<div class="col-lg-2">
								Référence :
							</div>
							<div class="col">
								Libellé :
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 py-2">
								<?=$prod['article']?>
							</div>
							<div class="col-lg-2 py-2">
								<?=$prod['dossier']?>
							</div>
							<div class="col-lg-2 py-2">
								<?=$prod['marque']?>
							</div>
							<div class="col-lg-2 py-2">
								<?=$prod['ref']?>
							</div>
							<div class="col">
								<?=strtolower($prod['libelle_art'])?>
							</div>
						</div>

						<div class="row ">
							<div class=" py-2 col-lg-2">
								Date commande :
							</div>
							<div class=" py-2 col-lg-2">
								Numéro :
							</div>
							<div class=" py-2 col-lg-1">
								Qte init. :
							</div>
							<div class=" py-2 col-lg-1">
								Qte art. :
							</div>
							<div class=" py-2 col-lg-1">
								Qte colis :
							</div>
							<div class=" py-2 col-lg-1">
								PCB :
							</div>
							<div class="py-2 col">
								Date livraison :
							</div>
						</div>

						<div class="row">
							<div class="col-lg-2">
								<?=($prod['date_cde']!=null)?date('d/m/y', strtotime($prod['date_cde'])):""?>
							</div>
							<div class="col-lg-2 ">
								<?=$prod['id_cde']?>
							</div>
							<div class="col-lg-1 text-right pr-4">
								<?=$prod['qte_init']?>
							</div>
							<div class="col-lg-1 text-right pr-4">
								<?=$prod['qte_cde']?>
							</div>
							<div class="col-lg-1 text-right pr-4">
								<?=$prod['qte_uv_cde']?>
							</div>
							<div class="col-lg-1 text-right pr-4">
								<?=$prod['cond_carton']?>
							</div>
							<div class="col">
								<?=($prod['date_liv']!=null)?date('d/m/y', strtotime($prod['date_liv'])):""?>
							</div>
						</div>
						<div class="row mt-4 mb-1">
							<div class="col">
								<h6 class="text-main-blue">Informations livraison :</h6>
							</div>
						</div>
						<?php if (!empty($listInfos)): ?>
							<?php if(isset($listInfos[$prod['id']])):?>
								<div class="row mb-2">
									<div class="col-lg-2">
										Date prévi :
									</div>
									<div class="col-lg-1">
										Qte prévi :
									</div>
									<div class="col-lg-6">
										Commentaires :
									</div>
									<div class="col-lg-1"></div>
									<div class="col-lg-1"></div>
									<div class="col"></div>
								</div>
								<?php foreach ($listInfos[$prod['id']] as $keyInfo => $value): ?>
									<div class="row mb-2">
										<div class="col-lg-2">
											<?=$listInfos[$prod['id']][$keyInfo]['date_previ']?>
										</div>
										<div class="col-lg-1">
											<?=$listInfos[$prod['id']][$keyInfo]['qte_previ']?>

										</div>
										<div class="col-lg-6">
											<?=$listInfos[$prod['id']][$keyInfo]['cmt']?>
										</div>
										<div class="col-lg-1">
											<a href="?update=<?=$listInfos[$prod['id']][$keyInfo]['id']?>" class="btn btn-secondary">Modifier</a>
										</div>
										<div class="col-lg-1">
											<a href="?del=<?=$listInfos[$prod['id']][$keyInfo]['id']?>" class="btn btn-danger">Supprimer</a>
										</div>
										<div class="col"></div>
									</div>
								<?php endforeach ?>
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
						<button class="btn btn-primary" name="submit">Enregistrer</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
require '../view/_footer-bt.php';
?>