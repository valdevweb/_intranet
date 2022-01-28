<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require '../../Class/achats/CdesDao.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/UserDao.php';
require '../../Class/FormHelpers.php';
// require_once '../../vendor/autoload.php';


$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoQlik = $db->getPdo('qlik');
$pdoFou = $db->getPdo('fournisseurs');
$pdoDAchat = $db->getPdo('doc_achats');

$cdesDao = new CdesDao($pdoQlik);
$cdesAchatDao = new CdesAchatDao($pdoDAchat);
$userDao = new UserDao($pdoUser);

// demande d'evo 190 : on prend les op de aujourd'hui à 7 semaine et non plus à partir de la 7ème semaine
$dOne = ((new DateTime())->modify('+49 day'));
$dateStartOne = new DateTime();
$dateEndOne = clone $dOne;
// demande evo 190  => on part d'autjourd'hui
// $dateStartOne=$dateStartOne->modify('monday this week');
$dateEndOne = $dateEndOne->modify('sunday this week');

$dTwo = ((new DateTime())->modify('+35 day'));
$dateStartTwo = clone $dTwo;
$dateEndTwo = clone $dTwo;
$dateStartTwo = $dateStartTwo->modify('monday this week');
$dateEndTwo = $dateEndTwo->modify('sunday this week');

$userGts = $userDao->getUserGts($_SESSION['id_web_user']);

$nbArtOne = $nbArtTwo = 0;

$param = null;
$queryString = "?";

if (isset($_SESSION['temp_relance'])) {
	unset($_SESSION['temp_relance']);
}
if (isset($_SESSION['temp_relance_perm'])) {
	unset($_SESSION['temp_relance_perm']);
}

// 3 cas pour filtrer les relances :
//
// l'utilisateur à filtré via le formulaire => gts sélectionnés
// l'utilisateur est un gestionniare donc a des gt attribué => ses gts
// l'utilisateur n'a pas de gt attribué => tout
if (isset($_POST['filter_gt'])) {
	if (empty($_POST['gt'][0])) {
		$errors[] = "Vous n'avez pas sélectionné de GT";
	} else {
		$param = 'AND (';
		$param .= join(' OR ', array_map(
			function ($value) {
				return "gt='" . $value . "'";
			},
			$_POST['gt']
		));
		$param .= ' )';
		for ($i = 0; $i < count($_POST['gt']); $i++) {
			$queryString .= $i . '=' . $_POST['gt'][$i] . '&';
		}
		$queryString = substr($queryString, 0, -1);
	}
} elseif (!empty($userGts)) {
	$param = 'AND (gt=' . join(' OR gt=', $userGts) . ')';

	for ($i = 0; $i < count($userGts); $i++) {
		$queryString .= $i . '=' . $userGts[$i] . '&';
	}
	$queryString = substr($queryString, 0, -1);
}


if (isset($_POST['launch_relance_one'])) {

	$_SESSION['temp_relance'] = $_POST['run_encours_id'];
	header('Location:relances-stepone.php');
}

if (isset($_POST['launch_relance_two'])) {
	$_SESSION['temp_relance'] = $_POST['rdeux_encours_id'];
	header('Location:relances-stepone.php');
}

if (isset($_POST['launch_relance_perm'])) {
	$_SESSION['temp_relance_perm'] = $_POST['rperm_encours_id'];
	header('Location:relances-stepone.php');
}




$relancesOne = $cdesDao->getCdesOpRelances($dateStartOne, $dateEndOne, $param);

$relancesOneInfos = $cdesAchatDao->getInfosOpRelances($dateStartOne, $dateEndOne, $param);
// on retire des relance les articles avec une date de livraison prévi saisie par les achats
$relancesOnePrevi = $cdesAchatDao->getInfosOpRelancesWithDatePrevi($dateStartOne, $dateEndOne, $param);

$relancesTwo = $cdesDao->getCdesOpRelances($dateStartTwo, $dateEndTwo, $param);
$relancesTwoInfos = $cdesAchatDao->getInfosOpRelances($dateStartTwo, $dateEndTwo, $param);
$relancesTwoPrevi = $cdesAchatDao->getInfosOpRelancesWithDatePrevi($dateStartTwo, $dateEndTwo, $param);

$relancesPerm = $cdesDao->getCdesPermRelances($param);
$relancesPermInfo = $cdesAchatDao->getInfosOpPerm($param);
$relancesPermPrevi = $cdesAchatDao->getInfosOpPermWithDatePrevi($param);




$nbArtOne = count($relancesOne) - count($relancesOnePrevi);
$nbArtTwo = count($relancesTwo) - count($relancesTwoPrevi);
$nbArtPerm = count($relancesPerm) - count($relancesPermPrevi);

$listGt = FournisseursHelpers::getGts($pdoFou, "GT", "id");

// paramétrage de la page 10-table-relance (on  utilise la même vue html pour les 3 periodes de relances)



if (isset($_GET['success'])) {
	$arrSuccess = [
		'relance' => 'Relances envoyées avec succès',
	];
	$success[] = $arrSuccess[$_GET['success']];
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid bg-white">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Relances commandes en cours</h1>
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


	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
		<div class="row" id="un">
			<div class="col-lg-3"></div>
			<div class="col p-3 border rounded">
				<div class="row">
					<div class="col-auto align-self-center no-padding ml-4">
						<i class="fas fa-filter pr-3 text-orange fa-3x"></i>
					</div>
					<div class="col">
						<div class="row">
							<div class="col">
								<h5 class="text-main-blue  my-3">Filtrer
									<div class="text-small"></div>
								</h5>
							</div>
						</div>
					</div>

					<div class="col cols-four">
						<?php foreach ($listGt as $keyGt => $value) : ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="<?= $keyGt ?>" id="<?= $listGt[$keyGt] ?>" name="gt[]">
								<label class="form-check-label" for="<?= $listGt[$keyGt] ?>"><?= ucfirst(strtolower($listGt[$keyGt])) ?></label>
							</div>
						<?php endforeach ?>
					</div>
					<div class="col-lg-4 align-self-end">
						<button class="btn btn-primary" name="filter_gt">Valider</button>
					</div>
				</div>
			</div>
			<div class="col-lg-3"></div>
		</div>
	</form>


	<!-- titre relance à 7 semaines -->
	<div class="row mt-5" id="deux">
		<div class="col-auto align-self-center no-padding ml-4">
			<i class="fas fa-edit pr-3 text-orange fa-3x"></i>
		</div>
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="text-main-blue  my-3">Relances à 7 semaines
						<div class="text-small">Opérations débutant entre le <?= $dateStartOne->format('d-m-Y') ?> et le <?= $dateEndOne->format('d-m-Y') ?></div>
					</h5>
				</div>
			</div>
		</div>
	</div>

	<!-- tableau relances à 7 semaine -->
	<div class="row">
		<div class="col">
			Nombre de ligne de commande en attente de livraison : <?= ($nbArtOne) ?? "" ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($relancesOne)) : ?>
				<?php include 'encours-relances/10-param-relance-un.php' ?>
				<?php include 'encours-relances/11-table-relances.php' ?>
			<?php else : ?>
				<div class="alert alert-info">Aucune relance à afficher. Vérifiez que la sélection des GTs est correcte</div>
			<?php endif ?>
		</div>
	</div>

	<!-- titre relance à 5 semaines -->
	<div class="row" id="trois">
		<div class="col-auto align-self-center no-padding ml-4">
			<i class="fas fa-edit pr-3 text-orange fa-3x"></i>
		</div>
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="text-main-blue  my-3">Relances à 5 semaines
						<div class="text-small">Début d'opération entre le <?= $dateStartTwo->format('d-m-Y') ?> et le <?= $dateEndTwo->format('d-m-Y') ?></div>
					</h5>
				</div>
			</div>
		</div>
	</div>
	<!-- tableau relances à 7 semaine -->

	<div class="row">
		<div class="col">
			Nombre de ligne de commande en attente de livraison : <?= ($nbArtTwo) ?? "" ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($relancesOne)) : ?>
				<?php include 'encours-relances/10-param-relance-two.php' ?>
				<?php include 'encours-relances/11-table-relances.php' ?>
			<?php else : ?>
				<div class="alert alert-info">Aucune relance à afficher. Vérifiez que la sélection des GTs est correcte</div>
			<?php endif ?>
		</div>
	</div>
	<!-- titre relance à 5 semaines -->
	<div class="row" id="quatre">
		<div class="col-auto align-self-center no-padding ml-4">
			<i class="fas fa-edit pr-3 text-orange fa-3x"></i>
		</div>
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="text-main-blue  my-3">Relances permanent
						<div class="text-small">Commandes de plus de une semaine</div>
					</h5>
				</div>
			</div>
		</div>
	</div>
	<!-- tableau relances à 7 semaine -->

	<div class="row">
		<div class="col">
			Nombre de ligne de commande en attente de livraison : <?= ($nbArtPerm) ?? "" ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($relancesPerm)) : ?>
				<?php include 'encours-relances/10-param-relance-perm.php' ?>
				<?php include 'encours-relances/11-table-relances.php' ?>
			<?php else : ?>
				<div class="alert alert-info">Aucune relance à afficher. Vérifiez que la sélection des GTs est correcte</div>
			<?php endif ?>
		</div>
	</div>

	<div id="floating-nav">
		<h6 class="text-main-blue text-center">Aller à</h6>
		<div class="pb-2"><i class="fas fa-filter fa-sm circle-icon-blue mr-3"></i><a href="#un">Filtres</a></div>
		<div class="pb-2"><i class="fas fa-angle-double-right fa-sm circle-icon-orange mr-3"></i><a href="#deux">Relances à 7 semaines</a></div>
		<div class="pb-2"><i class="fas fa-angle-double-right fa-sm circle-icon-orange mr-3"></i><a href="#trois">Relances à 5 semaines</a></div>
		<div class="pb-2"><i class="fas fa-angle-double-right fa-sm circle-icon-orange mr-3"></i><a href="#quatre">Relances sur le 1000</a></div>
	</div>


</div>
<script src="../js/excel-filter.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		// $('#table-relance-un').excelTableFilter();
		//
		$('#all_relance_one').change(function() {
			if ($(this).prop("checked")) {
				$('.input_relance_one').prop('checked', true);
			} else {
				$('.input_relance_one').prop('checked', false);

			}
		});
		$('#all_relance_two').change(function() {
			if ($(this).prop("checked")) {
				$('.input_relance_two').prop('checked', true);
			} else {
				$('.input_relance_two').prop('checked', false);

			}
		});
		$('#all_relance_perm').change(function() {
			if ($(this).prop("checked")) {
				$('.input_relance_perm').prop('checked', true);
			} else {
				$('.input_relance_perm').prop('checked', false);

			}
		});

		$("#relance-auto").submit(function(e) {
			$('button[name="launch_relance_one_auto"]').hide();
			$('button[name="launch_relance_two_auto"]').hide();
			$('button[name="launch_relance_perm_auto"]').hide();
			$('#wait').text("Merci de patienter...");

		});
	});
</script>

<?php
require '../view/_footer-bt.php';
?>