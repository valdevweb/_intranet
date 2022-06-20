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
require '../../Class/GesapDao.php';
// require_once '../../vendor/autoload.php';


$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoDAchat = $db->getPdo('doc_achats');


$gesapDao = new GesapDao($pdoDAchat);

$listGesap = $gesapDao->getListGesap();
$listFiles = $gesapDao->getListFiles();


if (isset($_POST['add'])) {


	if (empty($_POST['op']) || empty($_POST['salon']) || empty($_POST['cata']) || empty($_POST['code_op']) || empty($_POST['date_remonte'])) {
		$errors[] = "Vous devez renseigner les champs nom de l'opération, catalogue, salon, code opération et date limite de remontée";
	}
	if (isset($_POST['file_ga']) && empty($_POST['ga_name'])) {
		$errors[] = "Merci de saisir le numéro du guide d'achat";
	}


	if (empty($errors)) {


		if (!empty($_POST['ga_name']) && isset($_POST['file_ga'])) {
			$idGesap = $gesapDao->insertGesapWithGa($_POST['file_ga'][0]);
		} else {
			$idGesap = $gesapDao->insertGesapWithoutGa();
		}
		if (isset($_POST['file_otherfile'])) {
			for ($i = 0; $i < count($_POST['file_otherfile']); $i++) {
				$gesapDao->insertFileWithOrdre($idGesap, $_POST['file_otherfile'][$i], $_POST['readable_otherfile'][$i], $_POST['ordre_otherfile'][$i]);
			}
		}
	}

	if (empty($errors)) {
		$successQ = '?success';
		unset($_POST);
		header("Location: " . $_SERVER['PHP_SELF'] . $successQ, true, 303);
	}
}







//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Gestion des infos GESAP</h1>
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

	<div class="row" id="un">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3"></i>Ajout de GESAP</h5>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php include 'gesap-gestion/01-form-add.php' ?>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row" id="deux">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-book-reader pr-3"></i>Gesap à venir</h5>
		</div>
	</div>
	<div class="row">
		<div class="col-auto">
			<div class="form-group">
				<label for="search">Rechercher/filtrer</label>
				<input type="text" class="form-control" name="search" id="search">
			</div>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php if (!empty($listGesap)) : ?>
				<?php include 'gesap-gestion/02-table-gesap.php' ?>

			<?php endif ?>
		</div>
	</div>
	<div class="bg-separation"></div>



</div>
<script src="../js/dragndrop.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		$("#search").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#table-gesap tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});


		$("html").on("dragover", function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
		$("html").on("drop", function(e) {
			e.preventDefault();
			e.stopPropagation();
		});

		var idName = "#ga";
		var readable = false;
		var order = false;

		$(idName + ' .upload-area').on('dragenter', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName + " .upload-area p").text("Déposez");
		});

		$(idName + ' .upload-area').on('dragover', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName + " .upload-area p").text("Déposez");
		});

		$(idName + ' .upload-area').on('drop', function(e) {
			e.stopPropagation();
			e.preventDefault();

			var files = e.originalEvent.dataTransfer.files;
			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
				console.log(files[i])
			}


			uploadData(fd, 'drag-and-drop.php', idName, readable, order);
		});

		$(idName + " .uploadfile").click(function() {
			$(idName + " .dragndropfile").click();
		});

		$(idName + " .dragndropfile").change(function() {
			var fd = new FormData();
			var nbFiles = ($(idName + ' .dragndropfile')[0].files).length;
			for (var i = 0; i < nbFiles; i++) {
				var file = $(idName + ' .dragndropfile')[0].files[i];

				console.log($(idName + ' .dragndropfile')[0].files[i])
				fd.append('file', file);
				fd.append('file[]', file);

			}
			uploadData(fd, 'drag-and-drop.php', idName, readable, order);
		});



		var idNameTwo = "#otherfile";

		readableTwo = true;
		orderTwo = true;

		$(idNameTwo + ' .upload-area').on('dragenter', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(idNameTwo + ".upload-area p").text("Déposez");
		});

		$(idNameTwo + ' .upload-area').on('dragover', function(e) {
			e.stopPropagation();
			e.preventDefault();
			$(idNameTwo + " .upload-area p").text("Déposez");
		});

		$(idNameTwo + ' .upload-area').on('drop', function(e) {
			e.stopPropagation();
			e.preventDefault();

			$(idName + " .upload-area p").text("Téléchargement..");
			var files = e.originalEvent.dataTransfer.files;
			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
				console.log(files[i])
			}
			uploadData(fd, 'drag-and-drop.php', idNameTwo, readableTwo, orderTwo);
		});

		$(idNameTwo + " .uploadfile").click(function() {
			$(idNameTwo + " .dragndropfile").click();
		});

		$(idNameTwo + " .dragndropfile").change(function() {
			var fd = new FormData();
			var nbFiles = ($(idName + ' .dragndropfile')[0].files).length;
			for (var i = 0; i < nbFiles; i++) {
				var file = $(idName + ' .dragndropfile')[0].files[i];

				console.log($(idName + ' .dragndropfile')[0].files[i])
				fd.append('file', file);
				fd.append('file[]', file);

			}

			uploadData(fd, 'drag-and-drop.php', idNameTwo, readableTwo, orderTwo);
		});

	});
</script>
<?php
require '../view/_footer-bt.php';
?>