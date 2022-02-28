<?php
require_once '../../config/session.php';
require_once  '../../vendor/autoload.php';
require '../../Class/UserDao.php';
require '../../Class/mag/MagHelpers.php';
require '../../Class/Litiges/ActionDao.php';
require '../../Class/Litiges/LitigeDao.php';
require '../../Class/Litiges/ContrainteDao.php';
require '../../Class/Litiges/LitigeHelpers.php';

/** @var Db $db */

$pdoLitige = $db->getPdo('litige');
$pdoMag = $db->getPdo('magasin');

$userDao = new UserDao($pdoUser);
$actionDao = new ActionDao($pdoLitige);
$litigeDao = new LitigeDao($pdoLitige);
$contrainteDao = new ContrainteDao($pdoLitige);
$droitAccess = $userDao->isUserAllowed([5, 29]);

if (!$droitAccess) {
	header('Location:../home/home.php?access-denied');
}

unset($_SESSION['goto']);

if (!isset($_GET['id_contrainte'])) {
	header("Location: intervention-index.php");
}

if (isset($_GET['id'])) {
	$formAction = '?id=' . $_GET['id'] . '&id_contrainte=' . $_GET['id_contrainte'];
}else{
	$formAction = '?id_contrainte=' . $_GET['id_contrainte'];	
}


$listCorresp = LitigeHelpers::listContrainteCorresp($pdoLitige);
$infoContrainte=$contrainteDao->findContrainte($_GET['id_contrainte']);
$infoContrainte['id_contrainte_rep']= $listCorresp[$_GET['id_contrainte']];
$listIdContrainteDde=$contrainteDao->getContrainteDdeByContrainteRep($infoContrainte['id_contrainte_rep']);

$listDossier = $actionDao->getListDossierByContrainte(4);

$listReclamation = LitigeHelpers::listReclamationIncludingMasked($pdoLitige);
$listCentrales = MagHelpers::getListCentrale($pdoMag);
$listService = LitigeHelpers::listContrainteService($pdoLitige);

if (isset($_POST['id_dossier'])) {
	header('Location:intervention.php?id=' . $_POST['id_dossier'] . '&id_contrainte=' . $_GET['id_contrainte']);
}

if (isset($_GET['id'])) {
	// pour les achats, on a plusieurs id_contrainte de demande pour un id_contrainte de Réponse
	// donc on récupère les actions ayant tous ces id_contrainte de demande et tous ces id_contrainte de réponse
	$paramContrainte = 'id_contrainte='.$infoContrainte['id_contrainte_rep']. ' or '.join(' or ',array_map(function($idContrainte){
		return 'id_contrainte='.$idContrainte;
	}, $listIdContrainteDde));
	$paramContrainte='('.$paramContrainte. ')';
	$listAction = $actionDao->getActionsLitigeFiltreContrainte($_GET['id'], $paramContrainte);
	$thisLitige = $litigeDao->getLitigeDossierDetailById($_GET['id']);
}

if (isset($_POST['submit'])) {
	// vérifie si pièce jointes
	if (isset($_FILES['incfile']['name'][0]) && empty($_FILES['incfile']['name'][0])) {
		$allfilename = "";
	} else {
		$uploadDir = DIR_UPLOAD . 'litiges\\';

		$uploaded = false;
		$allfilename = "";
		$nbFiles = count($_FILES['incfile']['name']);
		for ($f = 0; $f < $nbFiles; $f++) {
			$filename = $_FILES['incfile']['name'][$f];
			$maxFileSize = 5 * 1024 * 1024; //5MB

			if ($_FILES['incfile']['size'][$f] > $maxFileSize) {
				$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
			} else {
				// cryptage nom fichier
				// Get the fileextension
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				// Get filename without extesion
				$filename_without_ext = basename($filename, '.' . $ext);
				// Generate new filename => ajout d'un timestamp au nom du fichier
				$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
				$uploaded = move_uploaded_file($_FILES['incfile']['tmp_name'][$f], $uploadDir . $filename);
			}
			if ($uploaded == false) {
				$errors[] = "impossible de télécharger le fichier";
			} else {

				$allfilename .= $filename . ';';
			}
		}
	}

	if (count($errors) == 0) {
		// pour stipuler que c'est une réponse et le type de réponse, on met le champ concerné (valeur field de la table contrainte_corresp)
		$add = $actionDao->addActionLitigeService($_GET['id'], $_POST['msg'], $infoContrainte['id_contrainte_rep'], $allfilename,  $infoContrainte['action_field']);

		if (VERSION == '_') {
			$dest = 'valerie.montusclat@btlec.fr';
		} else {
			$dest = ['btlecest.portailweb.litiges@btlec.fr'];
		}
		// envoi mail litigelivraison
		$htmlMail = file_get_contents('mail/mail_rep_intervention.php');
		$htmlMail = str_replace('{MAG}', $thisLitige[0]['mag'], $htmlMail);
		$htmlMail = str_replace('{DOSSIER}', $thisLitige[0]['dossier'], $htmlMail);
		$htmlMail = str_replace('{MSG}', $_POST['msg'], $htmlMail);
		$htmlMail= str_replace('{SERVICE}', $infoContrainte['service'], $htmlMail);
		$subject = 'Portail BTLec - litige - Réponse demande d\'intervention ' . $thisLitige[0]['dossier'];

		// ---------------------------------------
		// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')

			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			->setTo($dest);

		$delivered = $mailer->send($message);
		$successQ = '?success=send&id=' . $_GET['id'].'&id_contrainte=' . $_GET['id_contrainte'];
		unset($_POST);
		header("Location: " . $_SERVER['PHP_SELF'] . $successQ, true, 303);
	} else {
		$errors[] = "message non enregistré";
	}
}

if (isset($_GET['success'])) {
	$arrSuccess = [
		'send' => 'Votre message a bien été enregistré',
	];
	$success[] = $arrSuccess[$_GET['success']];
}


include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pt-5 ">Intervention <?= $listService[$_GET['id_contrainte']] ?></h1>
			<h4 class="text-secondary">Litige <?= $title = (isset($thisLitige[0]['dossier'])) ? $thisLitige[0]['dossier'] : '(pas de litige sélectionné)' ?></h4>
		</div>
	</div>

	<div class="row">
		<div class="col"></div>
		<div class="col-2">

			<form method="post" action="<?= $formAction ?>">
				<div class="form-group">
					<label>Changer de dossier :</label>
					<select name="id_dossier" class="form-control" onchange='this.form.submit()'>
						<option value="">Selectionnez</option>
						<?php foreach ($listDossier as $dossier) {
							echo '<option value="' . $dossier['id'] . '">' . $dossier['dossier'] . '</option>';
						}
						?>
					</select>
				</div>
			</form>
		</div>
		<div class="col"></div>
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
	<div class="bg-separation"></div>
	<?php if (isset($_GET['id']) && !empty($thisLitige)) : ?>

		<div class="row mb-3 pt-3">
			<div class="col text-yellow-dark heavy">Détail du litige :</div>
		</div>
		<div class="row pb-3">
			<div class="col-auto"><span class="heavy text-yellow-dark"> Magasin : </span><?= $thisLitige[0]['deno'] . ' - ' . $thisLitige[0]['btlec'] ?></div>
			<div class="col-auto"><span class="heavy text-yellow-dark"> Centrale : </span><?= $listCentrales[$thisLitige[0]['centrale']] ?></div>
			<div class="col"></div>
			<div class="col-auto"><span class="heavy text-yellow-dark">Etat :</span> <?= ($thisLitige[0]['etat_dossier'] == 1) ? 'Dossier clôturé' : 'Dossier en cours' ?></div>
			<div class="col-auto text-right"><i class="fas fa-calendar-alt pr-3 text-yellow-dark"></i><?= date('d-m-Y', strtotime($thisLitige[0]['date_crea'])) ?></div>
		</div>
		<div class="row">
			<div class="col">
				<table class="table table-bordered ">
					<tr class="table-warning">
						<th>CODE ARTICLE</th>
						<th>DESIGNATION</th>
						<th>QUANTITE</th>
						<th>VALORISATION</th>
						<th>RECLAMATION</th>
					</tr>

					<?php foreach ($thisLitige as $prod) : ?>
						<tr>
							<td><?= $prod['article'] ?></td>
							<td><?= $prod['descr'] ?></td>
							<td class="text-right"><?= $prod['qte_litige'] ?></td>
							<td class="text-right"><?= number_format((float)$prod['valo_line'], 2, '.', '') ?>&euro;</td>
							<td><?= $listReclamation[$prod['id_reclamation']] ?></td>
						</tr>
						<?php if ($prod['inversion'] != '') : ?>
							<?php $valoInv = round($prod['qte_cde'] * $prod['inv_tarif'], 2); ?>
							<tr>
								<td colspan="5" class="text-center text-prim heavy">Produit reçu à la place de la référence ci-dessus :</td>
							</tr>
							<tr>
								<td class="text-prim heavy"><?= $prod['inv_article'] ?></td>
								<td class="text-prim heavy"><?= $prod['inv_descr'] ?></td>
								<td class="text-prim heavy text-right"><?= $prod['qte_litige'] ?></td>
								<td class="text-prim heavy text-right"><?= number_format((float)$valoInv, 2, '.', '') ?>&euro;</td>
								<td class="text-prim heavy"></td>
							</tr>
						<?php endif ?>

					<?php endforeach ?>

				</table>

			</div>
		</div>
		<!-- <div class="bg-separation"></div> -->
		<div class="row mb-3 pt-3">
			<div class="col heavy text-main-blue">Echanges sur le dossier :</div>
		</div>

		<?php foreach ($listAction as $action) : ?>

			<div class="row alert bg-alert-primary mb-5">
				<div class="col">
					<div class="row heavy">
						<div class="col"></div>
						<div class="col">
							<div class="text-right"><i class="far fa-calendar-alt pr-3"></i><?= date('d-m-Y', strtotime($action['date_action'])) ?>
							</div>
						</div>
					</div>
					<div class="row ">
						<div class="col">
							<?= $action['libelle'] ?>
						</div>
						<?php $files = ($action['pj'] != "") ? explode(";", $action['pj']) : ""; ?>
						<div class="col-auto">
							<?php if (!empty($files)) : ?>
								<?php for ($i = 0; $i < count($files); $i++) : ?>
									<a href="<?= URL_UPLOAD ?>/litiges/<?= $file[$i] ?>"><?= substr($files[$i], 0, -15) ?></a><br>
								<?php endfor ?>
							<?php endif ?>
						</div>
					</div>

				</div>
			</div>

		<?php endforeach ?>

		<div class="bg-separation"></div>
		<div class="row mb-3 py-3 ">
			<div class="col">
				<div class="text-main-blue heavy">Répondre à BTLec :</div>
			</div>
		</div>
		<div class="row pb-5">
			<div class="col-2"></div>							
			<div class="col border p-3">
				<form action="<?=$formAction?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Votre message :</label>
						<textarea class="form-control" name="msg" required></textarea>
					</div>
					<div id="upload-zone">
						<label for='incfile'>Ajouter des pièces jointes :
							<br><i> (pour ajouter plusieurs fichiers, maintenez la touche ctrl pendant que vous sélectionnez les fichiers)</i> </label>
						<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="">

						<div id="filelist"></div>
					</div>
					<div class="text-right"><button class="btn btn-primary" name="submit"><i class="far fa-envelope pr-3"></i>Envoyer</button></div>
				</form>
			</div>
			<div class="col-2"></div>
		</div>
	<?php endif ?>



	<!-- ./container -->
</div>
<script type="text/javascript">
	$(document).ready(function() {


		var fileName = '';
		var fileList = '';
		$('input[type="file"]').change(function(e) {
			$('#filelist').empty();
			var nbFiles = e.target.files.length;
			for (var i = 0; i < nbFiles; i++) {
				// var fileName = e.target.files[0].name;
				fileName = e.target.files[i].name;
				fileList += fileName + ' - ';
			}
			// console.log(fileList);
			titre = '<p><span class="heavy">Fichier(s) : </span>'
			end = '</p>';
			all = titre + fileList + end;
			$('#filelist').append(all);
			fileList = "";
		});


	});
</script>
<?php
require '../view/_footer-bt.php';
?>