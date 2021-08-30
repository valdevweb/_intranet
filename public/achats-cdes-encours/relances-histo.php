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
require '../../Class/achats/CdesDao.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesRelancesDao.php';

require '../../Class/FournisseursHelpers.php';
require '../../Class/UserDao.php';
require '../../Class/FouDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$cdesRelancesDao=new CdesRelancesDao($pdoDAchat);

$userDao= new UserDao($pdoUser);
$fouDao=new FouDao($pdoFou);

$listGt=FournisseursHelpers::getGts($pdoFou, "GT","id");

$userGts=$userDao->getUserGts($_SESSION['id_web_user']);

$date=(new DateTime())->modify('-15 days');


if (isset($_POST['filter_gt'])) {
	if(empty($_POST['gt'][0])){
		$errors[]="Vous n'avez pas sélectionné de GT";

	}else{
		$param='AND (';
		$param.=join(' OR ',array_map(
			function($value){return "gt='".$value."'";},
			$_POST['gt']));
		$param.=' )';

	}


}elseif(!empty($userGts)){
	$param='AND (gt='.join(' OR gt=',$userGts). ')';


}
$relances=$cdesRelancesDao->getRelancesSince($date->format('Y-m-d'), $param);

$wR="";


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Relances des 2 dernières semaines</h1>
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


	<div class="row mb-5">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-lg-4"></div>

					<div class="col text-center">
						<div class="text-main-blue">Sélectionnez un ou plusieurs GT :</div>

					</div>
					<div class="col-lg-4"></div>

				</div>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col cols-four border rounded">
						<?php foreach ($listGt as $keyGt => $value): ?>

							<div class="form-check">

								<input class="form-check-input" type="checkbox" value="<?=$keyGt?>" id="<?=$listGt[$keyGt]?>" name="gt[]">
								<label class="form-check-label" for="<?=$listGt[$keyGt]?>"><?=ucfirst(strtolower($listGt[$keyGt]))?></label>

							</div>

						<?php endforeach ?>
					</div>
					<div class="col-lg-4"></div>

				</div>

				<div class="row mb-3">
					<div class="col-lg-4"></div>
					<div class="col text-right">
						<button class="btn btn-primary" name="filter_gt">Valider</button>
					</div>
					<div class="col-lg-4"></div>

				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php if (!empty($relances)): ?>
				<?php foreach ($relances as $key => $relance): ?>
					<?php if ($relance['id_relance']!=$wR): ?>
						<div class="row font-weight-bold text-main-blue">

							<div class="col-lg-3">
								<?=$relance['fournisseur']?>
							</div>
							<div class="col">
									Relance du : <?=date('d-m-Y', strtotime($relance['date_envoi']))?>
							</div>
						</div>
						<?php endif ?>

							<div class="row">
								<div class="col-lg-1"></div>
								<div class="col-auto">
									<?=$relance['article']?>/<?=$relance['dossier']?>

								</div>
								<div class="col">
									<?=$relance['ref']?>
								</div>
								<div class="col">
									<?=$relance['libelle_art']?>
								</div>
							</div>

						<?php $wR=$relance['id_relance']?>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>
	</div>



	<?php
	require '../view/_footer-bt.php';
	?>