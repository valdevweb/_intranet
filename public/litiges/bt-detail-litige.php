<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";

unset($_SESSION['goto']);


require 'echanges.fn.php';
require('../../Class/UserHelpers.php');
require('../../Class/MagHelpers.php');
require('../../Class/litiges/LitigeDao.php');
require('../../Class/litiges/LitigeHelpers.php');
require('../../Class/litiges/ActionDao.php');
require('../../Class/litiges/LitigeDialDao.php');
require('../../Class/OccHelpers.php');

//------------------------------------------------------
//			INFOS
//------------------------------------------------------
// 0=pas ajoutée, 1 ajoutée et correcte, 2 ajoutée mais incorrecte


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------





function updateValo($pdoLitige, $valo, $flag)
{
	$req = $pdoLitige->prepare("UPDATE dossiers SET valo= :valo, flag_valo= :flag_valo WHERE id= :id");
	$req->execute(array(
		':id'		=> $_GET['id'],
		':valo'		=> $valo,
		':flag_valo'	=> $flag
	));
	return $req->rowCount();
}
function getInvPaletteDetail($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT * FROM palette_inv WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=> $_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function sommeInvPalette($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT SUM(tarif) as valoInv, palette FROM palette_inv WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=> $_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function sommePaletteCde($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT SUM(tarif) as valoCde, palette,pj FROM details WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=> $_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function searchPalette($pdoQlik, $palette)
{
	$req = $pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE palette LIKE :palette");
	$req->execute(array(
		':palette'	=> '%' . $palette . '%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
// maj si recherche palette
function addPaletteInv($pdoLitige, $palette, $facture, $date_facture, $article, $ean, $dossier_gessica, $descr, $qte_cde, $tarif, $fournisseur, $cnuf)
{
	$req = $pdoLitige->prepare("INSERT INTO palette_inv (id_dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, found)
		VALUES (:id_dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :found)");
	$req->execute(array(
		':id_dossier'		=> $_GET['id'],
		':palette'			=> $palette,
		':facture'			=> $facture,
		':date_facture'	=> $date_facture,
		':article'			=> $article,
		':ean'				=> $ean,
		':dossier_gessica'	=> $dossier_gessica,
		':descr'			=> $descr,
		':qte_cde'			=> $qte_cde,
		':tarif'			=> $tarif,
		':fournisseur'		=> $fournisseur,
		':cnuf'			=> $cnuf,
		':found'			=> 1,

	));
	return $req->rowCount();
}


function updateCommission($pdoLitige, $etat)
{
	$req = $pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=> $etat,
		':date_commission'	=> date('Y-m-d H:i:s'),
		':id'		=> $_GET['id']

	]);
	return $req->rowCount();
}


// calcul valo totale uniquement si inversion de palette et palette reçue non toruvéé au moment de la déclaration
function getSumLitige($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT sum(valo_line) as sumValo, dossiers.valo, id_reclamation FROM details LEFT JOIN dossiers ON details.id_dossier= dossiers.id WHERE details.id_dossier= :id");
	$req->execute(
		[
			':id'		=> $_GET['id']
		]

	);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getSumPaletteRecu($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT sum(tarif) as sumValo FROM palette_inv  WHERE palette_inv.id_dossier= :id");
	$req->execute(
		[
			':id'		=> $_GET['id']
		]

	);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateValoDossier($pdoLitige, $sumValo)
{
	$req = $pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'			=> $sumValo,
		':id'			=> $_GET['id']
	]);
	return $req->rowCount();
}



function getPagination($pdoLitige)
{
	$req = $pdoLitige->query("SELECT id FROM dossiers ORDER BY dossier ASC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_COLUMN);
}
function addSerials($pdoLitige, $idDetail, $values)
{
	$req = $pdoLitige->prepare("UPDATE details SET serials=:serials WHERE id=:id");
	$req->execute([
		':id'		=> $idDetail,
		':serials' => stripslashes($values)
	]);
	return $req->rowCount();
}

$litigeDao = new LitigeDao($pdoLitige);
$dialDao = new LitigeDialDao($pdoLitige);
$actionDao = new ActionDao($pdoLitige);

$infoLitige = $litigeDao->getLitigeDossierDetailReclamMagEtatById($_GET['id']);
$firstDial = $litigeDao->getFirstDial($_GET['id']);
$infos = $litigeDao->getInfos($_GET['id']);
$analyse = $litigeDao->getAnalyse($_GET['id']);
$actionList = $litigeDao->getAction($_GET['id']);
$listContrainteNotif = LitigeHelpers::listContrainteNotif($pdoLitige);

$coutTotal = $infos['mt_transp'] + $infos['mt_assur'] + $infos['mt_fourn'] + $infos['mt_mag'];
$arMagOcc = MagHelpers::getListMagOcc($pdoMag);

if (isset($infoLitige[0])) {
	$codeBt = $infoLitige[0]['btlec'];
	$codeGalec = $infoLitige[0]['galec'];

	include 'ca/01-caphp.php';
}
if ($infos['ctrl_ok'] == 0) {
	$ctrl = "non contrôlé";
} elseif ($infos['ctrl_ok'] == 1) {
	$ctrl = "fait";
} elseif ($infos['ctrl_ok'] == 2) {
	$ctrl = "demandé";
}

if ($coutTotal != 0) {
	$coutTotal = number_format((float)$coutTotal, 2, '.', '');
}

$articleAZero = '';




if ($infoLitige[0]['flag_valo'] == 2) {
	$valoMag = 'impossible de calculer la valorisation';
	$articleAZero = '<i class="fas fa-info-circle text-main-blue pr-3"></i>Un des articles n\'a pas de tarif, veuillez cliquer sur le code article pour effectuer une recherche dans la base';
}



if (isset($_POST['validate'])) {
	if ($_SESSION['id_web_user'] != 959 && $_SESSION['id_web_user'] != 981) {
		header('Location:bt-detail-litige.php?notallowed&id=' . $_GET['id']);
	} elseif (!empty($_POST['cmt'])) {
		$idContrainte = 3;
		$action =  $actionDao->addActionLitige($_GET['id'], $_POST['cmt'], $idContrainte, '');
		$result = updateCommission($pdoLitige, 1);
		header('Location:bt-detail-litige.php?id=' . $_GET['id']);
	} else {
		$errors[] = "Veuillez saisir un commentaire";
	}
}
if (isset($_GET['notallowed'])) {
	$errors[] = "Vous n'êtes pas autorisé à modifier le statut 'validé en commission'";
}

if (isset($_POST['annuler'])) {
	header('Location:bt-detail-litige.php?id=' . $_POST['iddossier']);
}



if (isset($_POST['submit-serials'])) {
	$idDetail = "";
	foreach ($_POST as $key => $value) {
		if (strpos($key, "iddetail") !== false) {
			$idDetail = explode("-", $key)[1];
			$added = addSerials($pdoLitige, $idDetail, $_POST[$key]);
			if ($added >= 1) {
				$successStr = 'success=sn';
				unset($_POST);
				header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "&" . $successStr, true, 303);
			}
		}
	}
}

if (isset($_POST['not_read'])) {
	foreach ($_POST['not_read'] as $idDial => $value) {
		if (UserHelpers::isUserAllowed($pdoUser, ['94']) || $_SESSION['id_web_user'] == 1402) {
			$dialDao->updateRead($idDial, 0);
			header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "#" . $_POST['id_dial']);
		} else {
			$errors[] = "vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}
}
if (isset($_POST['read'])) {
	foreach ($_POST['read'] as $idDial => $value) {

		if (UserHelpers::isUserAllowed($pdoUser, ['94']) || $_SESSION['id_web_user'] == 1402) {
			$dialDao->updateRead($idDial, 1);
			header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "#" . $_POST['id_dial']);
		} else {
			$errors[] = "vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}
}



if (isset($_POST['not_read_action'])) {
	foreach ($_POST['not_read_action'] as $idAction => $value) {

		if (UserHelpers::isUserAllowed($pdoUser, ['94']) || $_SESSION['id_web_user'] == 1402) {
			$dialDao->updateReadAction($idAction, 0);
			header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "#" . $_POST['id_action']);
		} else {
			$errors[] = "vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}
}
if (isset($_POST['read_action'])) {

	foreach ($_POST['read_action'] as $idAction => $value) {
		if (UserHelpers::isUserAllowed($pdoUser, ['94'])  || $_SESSION['id_web_user'] == 1402) {
			$dialDao->updateReadAction($idAction, 1);
			header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "#" . $_POST['id_action']);
		} else {
			$errors[] = "vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}
}
if (isset($_GET['successpal'])) {
	$success[] = 'la palette a  été trouvée et la base de donnée mise à jour';
}
if (isset($_GET['success'])) {
	$arrSuccess = [
		'sn'		=> "Les numéros de séries ont bien été enregistrés"
	];
	$success[] = $arrSuccess[$_GET['success']];
}

$pagination = getPagination($pdoLitige);
$page = array_search($_GET['id'], $pagination);
$last = $pagination[count($pagination) - 1];

if ($_GET['id'] != $last) {
	$next = $pagination[$page + 1];
} else {
	$next = $last;
}

if ($_GET['id'] != 1) {
	$prev = $pagination[$page - 1];
} else {
	$prev = 0;
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

	<div class="row pb-3">
		<div class="col-8">
			<?php include('ca\10-cahtml.php'); ?>
		</div>
		<div class="col text-right">
			<?php if ($prev != 0) : ?>
				<a href="bt-detail-litige.php?id=<?= $prev ?>" class="grey-link"><i class="fas fa-angle-left pr-2 pt-2"></i>Litige précédent</a>
			<?php endif ?>
			<?php if ($next != $last) : ?>
				<a href="bt-detail-litige.php?id=<?= $next ?>" class="grey-link"><i class="fas fa-angle-right pl-5 pr-2 pt-1"></i>Litige suivant</a>

			<?php endif ?>
			<p class="text-right mt-5"><a href="bt-litige-encours.php" class="btn btn-primary">Retour</a></p>


		</div>
		<div class="col-auto align-self-end pt-5">
		</div>
	</div>

	<?php

	include('bt-detail-litige\02-view-head-dossier.php');
	?>






	<div class="bg-separation"></div>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<?php
	include('bt-detail-litige\03-view-btn.php');
	?>


	<div class="bg-separation"></div>
	<!-- infos produit -->
	<?php

	// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
	if ($infoLitige[0]['id_reclamation'] == 7) {
		include('bt-detail-litige\04-view-invpalette.php');
	} else {
		include('bt-detail-litige\04-view-prods.php');
	}

	?>


	<div class="bg-separation"></div>
	<?php
	include('bt-detail-litige\05-view-analyse.php');
	include('bt-detail-litige\06-view-info.php');
	include('bt-detail-litige\07-view-action.php');
	include('bt-detail-litige\08-view-echanges.php');


	?>

	<!-- MODAL SN -->
	<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-body">
					<h5 class="text-center text-violet">Numéros de séries :</h5>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $_GET['id'] ?>" method="post">
						<div class="form-group">
							<textarea class="form-control" name=""></textarea>
						</div>

						<div class="text-right">
							<button class="btn btn-primary" name="submit-serials">Enregistrer</button>

						</div>



					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-violet" data-dismiss="modal">Fermer</button>
				</div>
			</div>
		</div>
	</div>

</div>



<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(event) {
		var scrollpos = localStorage.getItem('scrollpos');
		if (scrollpos) window.scrollTo(0, scrollpos);
	});

	window.onbeforeunload = function(e) {
		localStorage.setItem('scrollpos', window.scrollY);
	};
	$(document).ready(function() {
		$('#largeModal').on('show.bs.modal', function(e) {
			var rowid = $(e.relatedTarget).data('id');
			console.log(rowid);
			$('textarea').attr('name', "iddetail-" + rowid);
			if (rowid) {
				$.ajax({
					type: 'POST',
					url: 'bt-detail-serial.php',
					data: 'idprod=' + rowid,
					success: function(html) {
						$('textarea').val(html);
						console.log(html);
					}
				});
			}
		});
		var url = window.location + '';
		var splited = url.split("?id=");
		if (splited[1] == undefined) {
			var line = '';
		} else {
			var line = splited[1];
		}

		$('.stamps').on('click', function() {
			console.log(line);
			$('#hiddeninput').val(line);
			$('#hidden').css("display", "block");
			$('#cmtarea').focus();
		});
		$('#annuler').on('click', function(e) {
			e.preventDefault();
			$('#hidden').css("display", "none");
		});
	});
</script>
<?php
require '../view/_footer-bt.php';
?>