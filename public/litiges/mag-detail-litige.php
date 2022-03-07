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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr = "detail litige côté magasin";
$page = basename(__file__);
$action = "consultation";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat, $page, $action, $descr, 101, $_GET['id']);

require_once  '../../vendor/autoload.php';
require_once  '../../Class/Helpers.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisLitige($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT galec, dossiers.dossier, mt_mag,fac_mag, inv_article,inv_fournisseur,inv_tarif,inv_descr,nom, valo, flag_valo, id_reclamation,inv_palette,inv_qte,
		 details.ean,details.id_dossier,	details.palette,details.article,details.tarif,details.qte_cde, details.qte_litige,details.dossier_gessica,details.descr,details.fournisseur,details.pj,details.inversion,
		reclamation
		FROM dossiers
		LEFT JOIN details ON dossiers.id= details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=> $_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function createFileLink($filelist)
{
	$rValue = '';
	$filelist = explode(';', $filelist);

	for ($i = 0; $i < count($filelist); $i++) {
		if ($filelist[$i] != "") {
			$rValue .= '<a href="' . URL_UPLOAD . '/litiges/' . $filelist[$i] . '" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';
		}
	}
	return $rValue;
}

function getDial($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT * FROM dial WHERE id_dossier= :id");
	$req->execute(array(
		':id'		=> $_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function addMsg($pdoLitige, $filelist)
{
	$msg = strip_tags($_POST['msg']);
	$msg = nl2br($msg);
	$req = $pdoLitige->prepare("INSERT INTO dial(id_dossier,date_saisie,msg,id_web_user,filename,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:filename,:mag)");
	$req->execute(array(
		':id_dossier'		=> $_GET['id'],
		':date_saisie'		=> date('Y-m-d H:i:s'),
		':msg'				=> $msg,
		':id_web_user'		=> $_SESSION['id_web_user'],
		':filename'		=>	$filelist,
		':mag'		=>	1,

	));
	return $req->rowCount();
	// return $req->errorInfo();
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
function getInvPaletteDetail($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT * FROM palette_inv WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=> $_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$thisLitige = getThisLitige($pdoLitige);
$dials = getDial($pdoLitige);

$errors = [];
$success = [];


// redirige su un mag essaye de regarde un litige qui n'est ^pas le sien
if ($thisLitige[0]['galec'] != $_SESSION['id_galec']) {
	header('Location:notyours.php');
}
// si litige sur une palette
if ($thisLitige[0]['id_reclamation'] == 7) {
	$detailInv = false;
	$detailCde = false;

	$invPal = sommeInvPalette($pdoLitige);
	$cdePal = sommePaletteCde($pdoLitige);
	if (isset($_GET['inv'])) {
		$invPalette = getInvPaletteDetail($pdoLitige);
		$detailInv = true;
	}
	if (isset($_GET['cde'])) {
		//on réutilise fLitige
		$detailCde = true;
	}
	if ($cdePal['pj'] != '') {
		$pj = createFileLink($cdePal['pj']);
	} else {
		$pj = '';
	}
}




$uploadDir = DIR_UPLOAD . 'litiges\\';
if (isset($_POST['submit'])) {


	if (empty($_FILES['form_file']['name'][0])) {
		$filelist = "";
	} else {
		$filelist = "";
		$nbFiles = count($_FILES['form_file']['name']);
		for ($f = 0; $f < $nbFiles; $f++) {
			$filename = $_FILES['form_file']['name'][$f];
			$maxFileSize = 5 * 1024 * 1024; //5MB

			if ($_FILES['form_file']['size'][$f] > $maxFileSize) {
				$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo, votre message n\'a pas été envoyé';
			} else {
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filename_without_ext = basename($filename, '.' . $ext);
				$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
				$uploaded = move_uploaded_file($_FILES['form_file']['tmp_name'][$f], $uploadDir . $filename);
			}
			if ($uploaded == false) {
				$errors[] = "impossible de télécharger le fichier";
			} else {
				$filelist .= $filename . ';';
			}
		}
	}
	// fin présence fichier

	if (count($errors) == 0) {
		$newMsg = addMsg($pdoLitige, $filelist);
		if ($newMsg != 1) {
			$errors[] = "impossible d'ajouter le message dans la base de donnée";
		}
	}
	if (count($errors) == 0) {
		// ---------------------------------------
		if (VERSION == '_') {
			$mailBt = array(MYMAIL);
		} else {
			if ($_SESSION['code_bt'] != '4201') {
				$mailBt = array(EMAIL_LITIGES);
			} else {
				$mailBt = array(MYMAIL);
			}
		}
		$link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/bt-detail-litige.php?id=' . $_GET['id'] . '&id_contrainte=' . $idContrainteDde . '"> cliquant ici</a>';

		$btTemplate = file_get_contents('mail/mail-bt-msgmag.php');
		$btTemplate = str_replace('{DOSSIER}', $thisLitige[0]['dossier'], $btTemplate);
		$btTemplate = str_replace('{MAG}', $_SESSION['nom'], $btTemplate);
		$btTemplate = str_replace('{LINK}', $link, $btTemplate);
		$subject = 'Portail BTLec Est  - nouveau message sur le dossier litige ' . $thisLitige[0]['dossier'];
		// ---------------------------------------
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
			->setBody($btTemplate, 'text/html')
			->setFrom(EMAIL_NEPASREPONDRE)
			->setTo($mailBt)
			->addBcc(MYMAIL);
		$delivered = $mailer->send($message);
		if ($delivered > 0) {
			$loc = 'Location:' . htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $_GET['id'] . '&success=ok';
			header($loc);
		} else {
			$errors[] = 'Le mail n\'a pas pu être envoyé à notre service livraison';
		}
	}
}


if (isset($_GET['success'])) {
	$success[] = "message envoyé avec succés";
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
	<?= Helpers::returnBtn('mag-litige-listing.php'); ?>
	<h1 class="text-main-blue pb-5 ">Dossier litige n°<?= $thisLitige[0]['dossier'] ?></h1>

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
			<?php
			// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
			if ($thisLitige[0]['id_reclamation'] == 7) {
				include('dt-mag-palette.php');
			} else {
				include('dt-mag-prod.php');
			}
			?>
		</div>
	</div>
	<?php

	if (!empty($thisLitige[0]['fac_mag'])) {
		include('dt-facturation.php');
	}

	?>


	<div class="bg-separation"></div>
	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Contacter le service litige : </h5>
		</div>
	</div>
	<div class="row my-3">
		<div class="col">
			<div class="row  bg-alert-primary rounded">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $_GET['id'] ?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="action" class="heavy">Votre message :</label>
									<textarea type="text" class="form-control" row="6" name="msg" placeholder="Message" id="msg" required></textarea>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-lg">
								<div class="label-like">Joindre un fichier :</div>
								<div class="form-group">
									<label class="btn btn-upload-primary btn-file text-center">
										<input type="file" name="form_file[]" class='form-control-file' multiple>
										<i class="fas fa-file pr-3"></i>Sélectionner
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col" id="filelist">
								
							</div>
						</div>
						<div class="row">
							<div class="col">
								<p class="text-right"><button type="submit" id="submit_t" class="btn btn-primary" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button></p>
							</div>
						</div>
				

				</form>
			</div>
		</div>
	</div>
</div>
<div class="bg-separation"></div>

<div class="row mt-5">
	<div class="col">
		<h5 class="khand text-main-blue pb-3">Echanges avec BTLec : </h5>
	</div>
</div>
<div class="row">
	<div class="col">



		<?php
		if (empty($dials)) {
			echo '<p class="text-center">Aucun message n\'a été échangé avec BTLec</p>';
		} else {
			foreach ($dials as $dial) {
				if ($dial['mag'] == 1 || $dial['mag'] == 3) {
					$bgColor = 'alert-primary';
				} else {
					$bgColor = 'alert-warning';
				}
				$pj = '';
				if ($dial['filename'] != '') {
					$pj = createFileLink($dial['filename']);
				}
				echo '<div class="row alert ' . $bgColor . '">';
				echo '<div class="col">';
				echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>' . $dial['date_saisie'] . '</div>';
				echo $dial['msg'];
				echo '<div class="text-right">' . $pj . '</div>';

				echo '</div>';
				echo '</div>';
			}
		}
		?>


	</div>
</div>
<div class="row">
	<div class="col text-center my-3">
		<a href="mag-litige-listing.php" class="btn btn-primary">Retour</a>
	</div>
</div>
<!-- ./container -->
</div>
<script type="text/javascript">
	$(document).ready(function() {
		function getReadableFileSizeString(fileSizeInBytes) {
			var i = -1;
			var byteUnits = [' ko', ' Mo', ' Go'];
			do {
				fileSizeInBytes = fileSizeInBytes / 1024;
				i++;
			} while (fileSizeInBytes > 1024);

			return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
		};
		var fileList = '';
		$('input[type="file"]').change(function(e) {
			$('#filelist').empty();

			console.log(e.target.files);
			let classText = "";
			let annotation = "";
			let nbFiles = e.target.files.length;
			totalSize = 0;
			tooHeavy = false;
			for (var i = 0; i < nbFiles; i++) {
				fileName = e.target.files[i].name;
				console.log("nom " + fileName);
		
				var fileSize = e.target.files[i].size;
				totalSize = totalSize + fileSize;
				if (fileSize >= 5242880) {
					classText = "text-danger";
					tooHeavy = true;
					annotation = " trop lourd";
				} else {
					classText = "text-success";
					annotation = " ";
				}
				fileList ='<div class="' + classText + '">' + fileName + " : " + getReadableFileSizeString(fileSize) + ' ' + annotation + '<br>';
				$('#filelist').append(fileList);
				console.log(fileList);
			}
			if (totalSize >= 5242880) {
				titre = '<p><span class="text-danger">Il ne sera pas possible de joindre vos fichiers,  la taille totale de <span class="font-weight-bold">' + getReadableFileSizeString(totalSize) + ' </span>dépasse les 5Mo autorisée </span</p>';
				$("#submit_t").attr("disabled", true);

			} else {
				titre = '<p class="text-success">Poids total : <span class="font-weight-bold">' + getReadableFileSizeString(totalSize) + ' </span></p>';
				$("#submit_t").attr("disabled", false);

			}
			$('#filelist').append(titre);
			fileList = "";

		});


	});
</script>


<?php
require '../view/_footer-bt.php';
?>