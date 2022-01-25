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
require '../../Class/achats/CdesCmtDao.php';

require '../../Class/FormHelpers.php';


// require_once '../../vendor/autoload.php';


$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoQlik = $db->getPdo('qlik');

$pdoDAchat = $db->getPdo('doc_achats');

$cdesDao = new CdesDao($pdoQlik);
$cdesAchatDao = new CdesAchatDao($pdoDAchat);
$cdesCmtDao = new CdesCmtDao($pdoDAchat);
$paramForm = "";

if (isset($_SESSION['temp'])) {
	$param = "WHERE cdes_encours.id=" . join(' OR cdes_encours.id=', $_SESSION['temp']);
	$paramEncours = "AND (id_encours=" . join(' OR id_encours=', $_SESSION['temp']) . ')';

	$listProd = $cdesDao->getEncoursByIds($param);
	$listInfos = $cdesAchatDao->getInfosIdEncours($paramEncours);
} else {
	echo "Aucun article sélectionné. Veuillez cocher les articles sur lesquels vous souhaitez saisir des informations.<a href='cdes-encours.php'>Retour</a>";
	exit();
}
$totalColis = 0;
$totalUv = 0;
foreach ($listProd as $key => $prod) {
	$totalUv += $prod['qte_uv_cde'];
	$totalColis += $prod['qte_cde'];
}

if (isset($_POST['save'])) {
	foreach ($_POST['qte_previ'] as $idDetail => $value) {
		if ($_POST['date_previ'][$idDetail] != null || $_POST['qte_previ'][$idDetail] != null) {
			$date = (empty($_POST['date_previ'][$idDetail])) ? null : $_POST['date_previ'][$idDetail];
			$qte = (empty($_POST['qte_previ'][$idDetail])) ? null : $_POST['qte_previ'][$idDetail];

			$cdesAchatDao->insertInfos(null, $idDetail, $date, $qte);
		}
		if ($_POST['cmt_galec'][$idDetail] != null || $_POST['cmt_btlec'][$idDetail] != null){
			$cmtBtlec = (empty($_POST['cmt_btlec'][$idDetail])) ? "" : $_POST['cmt_btlec'][$idDetail];
			$cmtGalec = (empty($_POST['cmt_galec'][$idDetail])) ? "" : $_POST['cmt_galec'][$idDetail];
			$cmtExist = $cdesCmtDao->getCmt($idDetail);

			if (empty($cmtExist)) {
				$cdesCmtDao->insertCmt($idDetail, null, $cmtBtlec, $cmtGalec);
			} else {
				$cdesCmtDao->updateCmt($idDetail, null, $cmtBtlec, $cmtGalec);
			}
		}
	}

	$successQ='?success=saved';
	unset($_POST);
	header("Location:".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if (isset($_GET['del'])) {
	$cdesAchatDao->maskInfo($_GET['del']);
	$successQ = '?success=deleted';
	unset($_POST);
	header("Location:" . $_SERVER['PHP_SELF'] . $successQ, true, 303);
}
if (isset($_GET['update'])) {
	$paramForm = '?update=' . $_GET['update'];
	$infoLiv = $cdesAchatDao->getInfo($_GET['update']);
}
if (isset($_POST['update'])) {

	$idInfo=$_POST['update'];
	$date = (empty($_POST['date_previ_update'][$idInfo])) ? null : $_POST['date_previ_update'][$idInfo];
	$qte = (empty($_POST['qte_previ_update'][$idInfo])) ? null : $_POST['qte_previ_update'][$idInfo];

	$cdesAchatDao->updateInfo($idInfo, $date, $qte);
	$successQ = '?success=updated';


	unset($_POST);
	header("Location:" . $_SERVER['PHP_SELF'] . $successQ, true, 303);
}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row pt-5">
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
		<div class="col text-orange font-weight-bold">
			Saisie globale :
		</div>
	</div>
	<?php
	include 'edit-encours/10-form-global.php';
	include 'edit-encours/11-info.php';

	?>
	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . $paramForm ?>" method="post">
				<?php if (!empty($listProd)) : ?>
					<?php foreach ($listProd as $key => $prod) : ?>
						<?php include 'edit-encours/12-row-info-prod.php'; ?>
						<?php $sommePrevi[$prod['id']] = 0; // on crée somme previ que l'on ait des infos ou pas
						?>
						<?php if (!empty($listInfos)) : ?>
							<?php if (isset($listInfos[$prod['id']])) : ?>
								<?php include 'edit-encours/13-row-info-previ.php'; ?>
							<?php endif ?>
						<?php endif ?>
						<?php if (isset($infoLiv) && isset($_GET['update'])) : ?>
							<?php include 'edit-encours/14-form-modif.php'; ?>
						<?php endif ?>
						<?php include 'edit-encours/15-form-add-info.php'; ?>
					<?php endforeach ?>

				<?php endif ?>
				<div class="fixed-zone">
					<div class="row ">
						<div class="col-2"></div>
						<div class="col text-right">
							<button class="btn btn-danger" name="save">Enregistrer</button>
						</div>
						<div class="col-1"></div>

					</div>
				</div>
			</form>
		</div>
	</div>

</div>
<script>
	$(document).ready(function() {
		$('#total').on("change", function() {
			var cmtTotal = "TOTALITE";

			if ($(this).is(':checked')) {
				console.log(1);
				$('#text_global').val(cmtTotal);
				$('.cmt').each(function() {
					var cmtActuel = $(this).val();
					$(this).val(cmtActuel + "\n" + cmtTotal);
				});

				$('.qte-saisie[data-id]').each(function() {
					var id = $(this).data('id');
					var inputRestant = $('#input-restant-' + id);
					var restant = inputRestant.val();
					$('#qte-saisie-' + id).val(restant);

				});

			} else {
				console.log(0);
				$('#text_global').val("");
				$('.qte-saisie').val("");
				$('.date').val("");
				$('.cmt').each(function() {
					var cmt = $(this).val();
					cmt = cmt.replace(cmtTotal, "");
					$(this).val(cmt);
				});
			}
		});
		$('#date_globale').on("change", function() {
			var date = $('#date_globale').val();
			$(".date").val(date);
		});
		$('.update').on("click", function(){
			var id=$(this).data('id-prod-update');
			$('.update-form-'+id).removeClass('d-none');
			$('.show-update-'+id).addClass('d-none');

			console.log(id);
		});
		$('#text_global').keyup(function() {
			var cmt = $('#text_global').val();
			var cmtActuel = $(".cmt").val(cmt);
			$(".cmt").val(cmtActuel + cmt);
		});
		$('.qte-saisie').keyup(function() {
			var saisie = $(this).val();
			var id = $(this).data('id');

			var div = $("div").find(`[data-id-prod-restant='${id}']`)
			var uv = $('[data-id-prod-uv="' + id + '"]');
			var nbUv = uv.text();

			var restant = nbUv - saisie;
			div.empty();
			div.append("Restant : <span class='text-success font-weight-bold'>" + restant + "</span>");

		})
	});
	document.addEventListener("DOMContentLoaded", function(event) {
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
</script>
<?php
require '../view/_footer-bt.php';
?>