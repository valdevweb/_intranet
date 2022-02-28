<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
}

set_error_handler('exceptions_error_handler');

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesCmtDao.php';
require '../../Class/UserHelpers.php';
require_once '../../vendor/autoload.php';

include 'xl-cols/xl-cols.php';

use Mpdf\Tag\Tr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




		

$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoDAchat = $db->getPdo('doc_achats');

$cdesAchatDao = new CdesAchatDao($pdoDAchat);
$cdesCmtDao = new CdesCmtDao($pdoDAchat);


if (isset($_POST['import'])) {
	if (isset($_FILES['file_import']['tmp_name']) && !empty($_FILES['file_import']['tmp_name'])) {
		$orginalFilename = $_FILES['file_import']['name'];
		$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

		if ($ext != "xls" && $ext != "xlsx") {
			$errors[] = "fichier non authorisé";
		}

		if (empty($errors)) {
			$filename = 'import_infos_cdes' . time() . '.' . $ext;
			$uploaded = move_uploaded_file($_FILES['file_import']['tmp_name'], DIR_UPLOAD . 'cdes-encours\\' . $filename);
			if ($uploaded == false) {
				$errors[] = "Nous avons rencontré un problème avec votre fichier, impossible de l'uploader vers le serveur";
			}
		}
	} else {
		$errors[] = "Merci de sélectionner un fichier";
	}
	if (empty($errors)) {
		$idImport = $cdesAchatDao->insertImport($filename, $_SESSION['id_web_user']);
		header('Location:import-cdesinfos-step2.php?id='.$idImport);
	}
}





//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Import infos commandes en cours</h1>
		</div>
	</div>

	<div class="row">

		<div class="col">
			<div class="alert alert-secondary">
				<p>Cette page vous permet d'intégrer des infos livraison via le fichier d'export des commandes en cours. Après avoir réalisé l'export des lignes de commandes de votre sélection, vous devez saisir vos infos en respectant les régles énoncées ci dessous puis importer le fichier via le formulaire</p>
				<div class="text-center font-weight-bold">
					<p>Consignes de saisie :</p>
				</div>
				<div class="row">
					<div class="col  text-success">
						<div class="font-weight-bold">Vous devez :</div>
						- saisir vos infos dans les colonnes x, y, z<br>
						- saisir la date au format jj/mm/aaaa<br>
						- saisir la quantité en chiffre sans ajouter de texte<br>
					</div>
					<div class="col  text-success">
						<div class="row">
							<div class="col"></div>
							<div class="col-auto">
								<div class="font-weight-bold">Vous pouvez :</div>
								- masquer des colonnes <br>
								- supprimer des lignes
							</div>
							<div class="col"></div>
						</div>
					</div>
					<div class="col text-danger">
						<div class="font-weight-bold">Vous ne devez pas : </div>
						- supprimer, ajouter, déplacer des colonnes<br>
						- ajouter des lignes
					</div>
				</div>




			</div>
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
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post"  enctype="multipart/form-data">

				<div class="row">
					<div class="col mb-3 text-main-blue text-center sub-title font-weight-bold ">
						Fichier commande en cours :
					</div>
				</div>
				<div class="row">
					<div class="col  bg-blue-input rounded pt-2 align-self-end">

						<div class="row mt-3">
							<div class="col" id="form-zone"></div>
						</div>
						<div class="row mt-3">
							<div class="col" id="warning-zone"></div>
						</div>
						<div class="form-group text-right">
							<label class="btn btn-upload-primary btn-file text-center">
								<input type="file" name="file_import" class='form-control-file' id="file_import">
								Sélectionner
							</label>
						</div>
					</div>
				</div>
				<div class="row mt-2 pb-5">
					<div class="col text-right">
						<button class="btn btn-primary" name="import" type="submit" >Importer</button>
					</div>
				</div>


			</form>
		</div>
	</div>
	
</div>
<script src="../js/upload-helpers.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#file_import').change(function() {
			noRename('file_import', 'warning-zone', 'form-zone')
		});
	});
</script>

<?php
require '../view/_footer-bt.php';
?>