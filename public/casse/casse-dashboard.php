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
//			INCLUDES
//------------------------------------------------------
require '../../Class/Db.php';


require('../../Class/Table.php');
require('../../Class/BaDao.php');
require('../../Class/casse/PalettesDao.php');
require('../../Class/casse/ExpDao.php');
require('../../Class/casse/CasseHelpers.php');
require('../../Class/casse/TrtDao.php');
require('../../Class/casse/CasseDao.php');

require('../../Class/mag/MagHelpers.php');
require('../../Class/CrudDao.php');
require('../../Class/UserDao.php');

require('casse-getters.fn.php');

$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoQlik = $db->getPdo('qlik');
$pdoCasse = $db->getPdo('casse');
$pdoMag = $db->getPdo('magasin');

$baDao = new BaDao($pdoQlik);
$paletteDao = new PalettesDao($pdoCasse);
$expDao = new ExpDao($pdoCasse);
$trtDao = new TrtDao($pdoCasse);
$casseDao = new CasseDao($pdoCasse);
$userDao = new UserDao($pdoUser);
$casseCrud = new CrudDao($pdoCasse);

$listAffectation = CasseHelpers::getAffectation($pdoCasse);

$arStatutPalette = CasseHelpers::getStatutsPalette($pdoCasse);
$listStatutPalette = CasseHelpers::getListStatutPalette($pdoCasse);
$listStatutPaletteIco = CasseHelpers::getListStatutPaletteIco($pdoCasse);


$listTrtMag = CasseHelpers::getTraitementsByType($pdoCasse, "mag"); //1
$listTrtSav = CasseHelpers::getTraitementsByType($pdoCasse, "sav"); //3
$listTrtOcc = CasseHelpers::getTraitementsByType($pdoCasse, "occasion"); //2
$listAffectation = CasseHelpers::getAffectation($pdoCasse);
$listAffectationIco = CasseHelpers::getAffectationIco($pdoCasse);

$listTrtUrl = CasseHelpers::getTraitementsUrl($pdoCasse);
$trtHisto = $trtDao->getTrtHistoByExp();

$errors = [];
$success = [];
$today = date('Y-m-d');
$start = date('Y-m-d', strtotime("2019-01-01"));
$yesterday = date('Y-m-d', strtotime("-1 days"));
$nbPalette = 0;
// palettes à expéditer
$expeds = $expDao->getExpDetails();



$paletteEnStock = $paletteDao->getStockPalette();



function logoStatut($palette)
{
	// statut en cours et en stock
	if ($palette['statut'] == 0) {
		$statutImg = '<img src="../img/casse/encours.jpg">';
		return $statutImg;
	}

	if ($palette['statut'] == 1) {
		$statutImg = '<img src="../img/casse/alivrer.png">';
		return $statutImg;
	}
	if ($palette['statut'] == 4) {
		$statutImg = '<img src="../img/casse/block.jpg">';
		return $statutImg;
	}


	// statut expédié
	if ($palette['exp'] == 1) {
		$statutImg = '<img src="../img/casse/done.png">';
		if ($palette['mt_fac'] != '') {
			$statutImg .= '<img src="../img/casse/creditcard.png">';
		}
		if ($palette['id_affectation'] == 3) {
			$statutImg .= '<img src="../img/casse/logo_deee.jpg">';
		}
		return $statutImg;
	}
}





if (!empty($paletteEnStock)) {
	$nbPalette = count($paletteEnStock);
}



$sumTot = 0;
$arMagSum = [];

$arrParam = [];
// mag et gt13 = blue, sav= vert
$classesAffectation = [
	'' => 'text-dark',
	1 => 'text-primary',
	2 => 'text-primary',
	3 => 'text-success'
];


if (isset($_POST['clear_form'])) {
	unset($_POST);
	unset($_SESSION['casse_filter']);
}

if (isset($_GET['field_1'])) {
	$_SESSION['casse_filter']['field_1'] = $_GET['field_1'];
}
if (isset($_GET['field_2'])) {
	$_SESSION['casse_filter']['field_2'] = $_GET['field_2'];
}


if (isset($_POST['search'])) {
	$params = $_POST['field'] . " = '" . $_POST['search_string'] . "'";
	$_SESSION['casse_filter']['search_field'] = $_POST['field'];
	$_SESSION['casse_filter']['search_value'] = $_POST['search_string'];
}


if (!isset($_SESSION['casse_filter'])) {
	// par défaut, on affiche les palettes non cloturées
	$params = "palettes.statut !=3";
	$palettesToDisplay = $paletteDao->getPaletteByFilter($params);
} else {
	if (isset($_SESSION['casse_filter']['search_field'])) {
		if ($_SESSION['casse_filter']['search_field'] == "ean") {
			$arrParam[] = " ean='" . $_SESSION['casse_filter']['search_value'] . "'";
		} elseif ($_SESSION['casse_filter']['search_field'] == "palette") {
			$arrParam[] = " palette LIKE '%" . $_SESSION['casse_filter']['search_value'] . "%'";
		} elseif ($_SESSION['casse_filter']['search_field'] == "id_casse") {
			$arrParam[] = " casses.id=" . $_SESSION['casse_filter']['search_value'];
		} elseif ($_SESSION['casse_filter']['search_field'] == "btlec") {
			$arrParam[] = " exps.btlec=" . $_SESSION['casse_filter']['search_value'];
		} elseif ($_SESSION['casse_filter']['search_field'] == "article") {
			$arrParam[] = " casses.article=" . $_SESSION['casse_filter']['search_value'];
		}
	}
	if (isset($_SESSION['casse_filter']['field_1'])) {
		$arrParam[] = " statut=" . $_SESSION['casse_filter']['field_1'];
	}
	if (isset($_SESSION['casse_filter']['field_2'])) {
		$arrParam[] = " palettes.id_affectation=" . $_SESSION['casse_filter']['field_2'];
	}
	if (!empty($arrParam)) {
		$params = join(' and ', array_map(function ($value) {
			return $value;
		}, $arrParam));
	}
	$palettesToDisplay = $paletteDao->getPaletteByFilter($params);
}

// calcul et créa tableau pour le bandeau de récap (nb palettes + montant + repart)
if (isset($palettesToDisplay)) {
	$nbpalette = count($palettesToDisplay);

	foreach ($palettesToDisplay as $key => $value) {
		$sumTot += $value['valopalette'];
		if (isset($arMagSum[$value['galec']])) {
			$arMagSum[$value['galec']] += $value['valopalette'];
		} else {
			$arMagSum[$value['galec']] = $value['valopalette'];
		}
	}
	$nbMagCol = ceil(count($arMagSum) / 2);
	$lig = 1;
}


// ajout num palette contremarque => traitement gt13
if (isset($_POST['save_contremarque'])) {
	$updateTrt = true;

	foreach ($_POST['contremarque'] as $id => $value) {
		if (empty($_POST['contremarque'][$id])) {
			$updateTrt = false;
		}
		$casseCrud->update("palettes", "id=" . $id, ['contremarque' => $_POST['contremarque'][$id]]);
	}

	if ($updateTrt) {
		$trtDao->insertTrtHisto($_POST['id_exp'], 1);
	}

	$successQ = '#exp-' . $_POST['id_exp'];
	unset($_POST);
	header("Location: " . $_SERVER['PHP_SELF'] . $successQ, true, 303);
}

// suppression de palette
if (isset($_GET['del-palette'])) {
	$casses = $casseDao->getCasseByPalette($_GET['del-palette']);
	if (empty($casses)) {
		$paletteDao->copyPaletteToDeleted($_GET['del-palette']);
		$casseCrud->update("palettes_deleted", "id=" . $_GET['del-palette'], ['deleted_on' => date('Y-m-d H:i:s'), 'deleted_by' => $_SESSION['id_web_user']]);
		$casseCrud->deleteOne("palettes", $_GET['del-palette']);
		$successQ = '?success=del-palette';
		header("Location: " . $_SERVER['PHP_SELF'] . $successQ, true, 303);
	} else {
		$errors[] = "avant de supprimer la palette, vous devez supprimer ou réaffecter les casses sur une autre palette";
	}
}

// recup url de redirection dans table traitements en fonction de l'id_trt passé ds url
if (isset($_GET['id_trt'])) {
	$url = ($listTrtUrl[$_GET['id_trt']]) ?? "";
	if (empty($url)) {
		$errors[] = "Le traitement n'a pas été reconnu";
	}
	if (!file_exists($url)) {
		$errors[] = "La page de traitement demandée, n'existe pas";
	}
	if (empty($errors)) {
		header("Location: " . $url . "?id=" . $_GET['id_exp'] . "&id_trt=" . $_GET['id_trt']);
	}
}


// cas ou va sur detail-palette sans id en paramètre => redirige ici
if (isset($_GET['error'])) {
	if ($_GET['error'] == 1) {
		$errors[] = "Vous avez été redirigé, cette page n'est pas accessible";
	} elseif ($_GET['error'] == 2) {
		$errors[] = "Une erreur est survenue";
	}
}

if (isset($_GET['success'])) {
	$arrSuccess = [
		'del-palette' => 'Palette supprimée',
	];
	$success[] = $arrSuccess[$_GET['success']];
}



if (isset($_GET['mailPilote'])) {
	$success[] = "Le mail a bien été envoyé aux pilotes";
}

if (isset($_GET['majExp'])) {
	$success[] = "La date d'expédition a bien été enregistrée";
}
if (isset($_GET['mailMag'])) {
	$success[] = "Le mail a bien été envoyé magasin";
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

<div class="container no-padding">
	<div class="row no-gutters" id="mini-menu">
		<div class="col">
			<img src="../img/litiges/brokenphone2.png" class="img-fluid">
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<!-- mini nav -->
	<div class="row">
		<div class="col"></div>
		<div class="col-auto">
			<ul class="css-not-selector-shortcut sibling-fade">
				<li><a href="declare-casse.php" class="link-main-blue">Déclarer</a></li>
				<li><a href="#searching" class="link-main-blue">Rechercher</a></li>
				<li><a href="#stock" class="link-main-blue">En stock</a></li>
				<li><a href="#traitement" class="link-main-blue">Traitement</a></li>
			</ul>
		</div>
		<div class="col"></div>
	</div>
	<?php if (isset($palettesToDisplay)) : ?>
		<div class="result-zone px-5 pb-2 pt-2 mb-2">
			<!-- récap casses affichées -->
			<?php include('casse-dashboard/12-search-stat.php');	?>
			<!-- formlaire por filtrer les casses -->
			<?php include('casse-dashboard/11-form-search.php');	?>
		</div>
		<!-- tableau résultat casses trouvées par palette -->
		<?php include('casse-dashboard/13-search-table-result.php') ?>
	<?php endif ?>
	<div class="row">
		<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
	</div>
	<div class="bg-separation"></div>
	<!-- palettes de la table qlik.palettes4919   -->
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue" id="stock">Palettes en stock :</h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<p>
				<?php foreach ($arStatutPalette as $key => $statut) : ?>
					<?php if ($statut['id'] != 0) : ?>
						<?= $statut['ico'] . " : " . $statut['statut'] ?>
					<?php endif ?>
				<?php endforeach ?>
			</p>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php if (!empty($paletteEnStock)) : ?>
				<ul id="list-palette">
					<?php foreach ($paletteEnStock as $palette) : ?>
						<li>
							<a href="detail-palette.php?id=<?= $palette['paletteid'] ?>" class="<?= $classesAffectation[$palette['statut']] ?? '' ?>">
								<?= $palette['palette'] ?>
							</a> :
							<?php if (isset($listStatutPalette[$palette['statut']])) : ?>
								<?= $listStatutPaletteIco[$palette['statut']] ?>
								<span class="pl-3 <?= $classesAffectation[$palette['statut']] ?? ''  ?>">
									<?= $listStatutPalette[$palette['statut']] ?>
								</span>
							<?php else : ?>
								<i class="fas fa-hourglass-start text-primary"></i>
								<span class="pl-3 <?= $classesAffectation[$palette['statut']] ?? ''  ?>">en stock</span>

							<?php endif ?>

						</li>
					<?php endforeach ?>
				</ul>
			<?php else : ?>
				<p>aucune palette de casse en stock</p>
			<?php endif ?>
			<p class="alert alert-primary">Cliquez sur une palette pour en afficher le contenu et la positionner sur une livraison</p>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
	</div>
	<div class="bg-separation"></div>
	<!-- palettes présentes sur une expéd -->

	<?php if ($userDao->userHasThisRight($_SESSION['id_web_user'], 105)) : ?>

		<div class="row py-3">
			<div class="col">
				<h5 class="text-main-blue" id="traitement">Palettes à livrer :</h5>
			</div>
		</div>
		<?php if (!empty($expeds)) : ?>
			<?php include "casse-dashboard/14-table-en-stock.php" ?>
		<?php else : ?>
			aucune palette n'a été sélectionnée pour une livraison magasin
		<?php endif ?>

		<div class="row">
			<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
		</div>
	<?php endif ?>

</div>
<?php include 'casse-dashboard/15-modal-update-palette-nb.php' ?>





<script src="../js/sortmultitable.js"></script>

<script type="text/javascript">
	function sortTable(n) {
		sort_table(document.getElementById("palettes"), n);
	}
	url = window.location.href;

	var url = window.location.href;
	var splited = url.split("#");
	console.log(url);
	if (splited[1] == undefined) {
		var line = '';
	} else if (splited.length == 2) {
		var line = splited[1];
		console.log(line);
		// line=line.replace("palette-", "");
		$('#' + line).addClass("anim");
	}



	// // url.searchParams.get("field_1");
	// const urlSearchParams = new URLSearchParams(window.location.search);
	// const params = Object.fromEntries(urlSearchParams.entries());
	// console.log(params);
	$(document).ready(function() {
		$('#edit-palette').on('show.bs.modal', function(e) {
			var idPalette = $(e.relatedTarget).data('id-palette');
			var palette = $(e.relatedTarget).data('palette');
			$('#palette-modal').val(palette);
			$('#id-palette-modal').val(idPalette);

		});


		$('button#submit_search[type="submit"]').attr('disabled', 'disabled');
		$("input[name='field']").on("click", function() {
			$('button#submit_search[type="submit"]').removeAttr('disabled');
		})
		// boite de dialogue confirmation clic sur lien
		$('.red-link').on('click', function(e) {
			var webid = '<?php echo $_SESSION['id_web_user']; ?>';
			console.log(webid);
			if (webid != 981 && webid != 959 && webid != 1043 && webid != 1279) {
				alert("vous n'avez pas les droits pour supprimer une casse. Merci de faire votre demande à Christelle Trousset et/ou Nathalie Pazik ");
				e.preventDefault();
			} else {
				return confirm('Etes vous sûrs de vouloir supprimer cette casse ?');
			}
		});
		$('#mailpilote').on('click', function() {
			return confirm('Envoyer le mail de demande de contrôle des palettes aux pilotes ?');
		});
		$('#mailMag').on('click', function() {
			return confirm('Envoyer le mail d\'information au magasin ?');
		});

		// field_1=pending
		$('.legend').on('click', function() {
			var param = $(this).attr('data-statut');;
			const params = new URLSearchParams(location.search);
			params.set('field_1', param);
			window.history.replaceState({}, '', `${location.pathname}?${params}`);
			location.reload();

		});
		$('#affectation').on('change', function() {
			var param = $("option:selected", this).val();
			const params = new URLSearchParams(location.search);
			params.set('field_2', param);
			window.history.replaceState({}, '', `${location.pathname}?${params}`);
			location.reload();
		});
	});
</script>



<?php
require '../view/_footer-bt.php';
?>