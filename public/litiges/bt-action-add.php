<?php
require_once '../../config/session.php';
unset($_SESSION['goto']);

require_once '../../Class/litiges/LitigeDao.php';
require_once '../../Class/litiges/ActionDao.php';
require_once '../../Class/litiges/LitigeHelpers.php';
// require_once '../../vendor/autoload.php';


$pdoSav=$db->getPdo('sav');
$pdoLitige = $db->getPdo('litige');

$litigeDao = new LitigeDao($pdoLitige);
$actionDao = new ActionDao($pdoLitige);



$litiges = $litigeDao->findDossier($_GET['id']);


$actionsLitige = $actionDao->findActionsLitige($_GET['id']);




$listActions = LitigeHelpers::listActions($pdoLitige);


function getHelpInfo($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT action_help.id,action_help.contrainte, nom, pretxt,contrainte_libelle, id_contrainte 
	FROM action_help 
	LEFT JOIN action_contrainte ON id_contrainte=action_contrainte.id WHERE action_help.id=:id");
	$req->execute(array(
		':id'		=> $_POST['pretxt']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// recupère le pale sav du mag pour action 4 : demande intervention sav
function getMagSav($pdoSav, $galec)
{
	$req = $pdoSav->prepare("SELECT sav FROM mag WHERE galec = :galec");
	$req->execute([
		':galec'		=> $galec
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}




if (isset($_POST['submit'])) {
	// recup fichier uploadés
	if (isset($_FILES['incfile']['name'][0]) && empty($_FILES['incfile']['name'][0])) {
		$filenames = "";
	} else {
		$uploadDir = DIR_UPLOAD . 'litiges\\';
		$uploaded = false;
		$filenames = "";
		$nbFiles = count($_FILES['incfile']['name']);
		for ($f = 0; $f < $nbFiles; $f++) {
			$filename = $_FILES['incfile']['name'][$f];
			$maxFileSize = 5 * 1024 * 1024; //5MB
			if ($_FILES['incfile']['size'][$f] > $maxFileSize) {
				$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
			} else {
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filename_without_ext = basename($filename, '.' . $ext);
				$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
				$uploaded = move_uploaded_file($_FILES['incfile']['tmp_name'][$f], $uploadDir . $filename);
			}
			if ($uploaded == false) {
				$errors[] = "impossible de télécharger le fichier";
			} else {
				$filenames .= $filename . ';';
			}
		}
	}
	// si on a une contrainte, on redirige sur la page de traitement des contraintes avec l'id contrainte,
	// sinon on reste sur cette page
	$idContrainte = ($_POST['id_contrainte'] == '') ? null : $_POST['id_contrainte'];
	// si le sav est connecté, on force la contrainte à réponse sav
	if ($_SESSION['id_type'] == 3) {
		$idContrainte = 5;
	}

	$newAction=$actionDao->addActionLitige($_GET['id'], $_POST['action'], $idContrainte, $filenames);
	// si contrainte
	if ($idContrainte != null) {
	
		// => pour la contrainte 4 demande d'inter sav, on a besoin de vérifier que le mag a bien un pôle SAV, si ce n'est pas le cas, on bloque le traitement
		// si pas de bloquage, on ajoute l'action avec son numéro de contrainte et on redirige vers la page contrainte qui fait le traitement approprié
		if ($idContrainte == 4) {
			$galec = $litiges['galec'];
			$sav = getMagSav($pdoSav, $galec);
			if (empty($sav)) {
				$errors[] = "Vous ne pouvez pas ajouter cette action, aucun pôle SAV n'a été renseigné pour ce magasin";
			}
		}

		if (empty($errors)) {
			header('Location:contrainte.php?contrainte=' . $idContrainte . '&id=' . $_GET['id'] . '&action=' . $newAction);
		}
	} else {
		// echo "pas de contrainte";

		// si l'action n'a pas de contrainte, on redirige sur la page actuelle
		header('Location:bt-action-add.php?id=' . $_GET['id']);
	}
}





if (isset($_GET['success'])) {
	$success[] = 'action effectuée avec succès';
}

include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">

	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue ">Dossier N° <?= $litiges['dossier'] ?></h1>
		</div>
		<div class="col-auto">
			<p class="text-right"><a href="bt-detail-litige.php?id=<?= $_GET['id'] ?>" class="btn btn-primary">Retour</a></p>
		</div>
	</div>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<h2 class="khand">Dernières actions menées</h2>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table">
				<thead class="bg-kaki">
					<tr>
						<th>date</th>
						<th>Par</th>
						<th>Action</th>
						<th><i class="fas fa-link"></i></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($actionsLitige)) : ?>
						<?php foreach ($actionsLitige as  $action) : ?>
							<tr>
								<td class="nowrap"><?= date('d-m-Y', strtotime($action['date_action'])) ?></td>
								<td><?= $action['fullname'] ?></td>
								<td><?= $action['libelle'] ?></td>
								<?php
								$files = ($action['pj'] != "") ? explode(";", $action['pj']) : "";
								?>
								<td>
									<?php if (!empty($files)) : ?>
										<?php for ($i = 0; $i < count($files); $i++) : ?>
											<a href="<?= URL_UPLOAD ?>/litiges/<?= $file[$i] ?>"><?= substr($files[$i], 0, -15) ?></a><br>
										<?php endfor ?>
									<?php endif ?>
								</td>
							</tr>

						<?php endforeach ?>
					<?php else : ?>
						<tr>
							<td colspan="4">Aucune Action</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row mt-4">
		<div class="col">
			<h2 class="khand">Ajouter une action</h2>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col bg-kaki-light">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $_GET['id'] ?>" method="post" enctype="multipart/form-data">
						<div class="row align-items-end p-3">
							<div class="col">
								<p class="heavy">Action existante :</p>
								<div class="form-group">
									<select name="action_list" id="action_list" class="form-control">
										<option value="">Sélectionnez une réponse préparée</option>
										<?php foreach ($listActions as $keyAction => $value) : ?>
											<option value="<?= $keyAction ?>"><?= $listActions[$keyAction] ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="form-group">
									<label for="action">Description de l'action :</label>
									<textarea type="text" class="form-control" row="10" name="action" id="action"></textarea>
								</div>

								<div id="upload-zone">
									<label for='incfile'>Ajouter une ou des pièces jointes* : </label>
									<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="">

									<div id="filelist"></div>
								</div>
								<div class="row">
									<div class="col">
										<i>* Maintenir la touche CTRL appuyée pour sélectionner plusieurs fichiers</i>
									</div>
								</div>
							</div>
							<input type="hidden" name="id_contrainte" id="id_contrainte">
							<div class="col-auto">
								<button type="submit" id="submit_t" class="btn btn-kaki" name="submit"><i class="fas fa-plus-square pr-3"></i>Enregistrer</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#action_list').on('change', function(e) {
			// var id_action = $('#action option:selected').val();
			// console.log(id_action);
			$.ajax({
				url: "bt-action-add/ajax-get-action.php",
				method: "POST",
				data: {
					id_action: e.target.value
				},
				success: function(action) {
					console.log(action);
					action = JSON.parse(action);
					$('#action').empty();
					$('#action').append(action.default_text);
					$('#id_contrainte').val(action.id_contrainte);
				}
			});

		});
		var fileName = '';
		var fileList = '';
		var fileSizeMo = 0;

		$('input[type="file"]').change(function(e) {
			var totalFileSize = 0;

			$('#filelist').empty();
			var titre = warning = fileList = end = "";
			var nbFiles = e.target.files.length;
			for (var i = 0; i < nbFiles; i++) {
				// var fileName = e.target.files[0].name;
				fileName = e.target.files[i].name;
				fileSize = e.target.files[i].size;
				totalFileSize = totalFileSize + fileSize;
				fileSizeMo = Math.round(fileSize / 1000000);
				// 5120
				if (fileSize > 10000000) {
					fileList += '<div class="text-red">Attention le fichier "' + fileName + '" est trop lourd (' + fileSizeMo + 'Mo au lieu du 10Mo maximum)</div>';

				} else {
					fileList += fileName + ' - ';
				}

			}
			// console.log(fileList);
			titre = '<p><span class="heavy">Fichier(s) : </span>'
			end = '</p>';
			if (totalFileSize > 10000000) {
				totalFileSizeMo = Math.round(totalFileSize / 1000000);
				warning = '<div class="text-red">Attention la taille totale des fichiers dépasse la taille autorisée de 10Mo (Poids total de vos fichiers : ' + totalFileSizeMo + 'Mo)<br></div>';
			} else {
				warning = ''
			}
			all = titre + warning + fileList + end;
			$('#filelist').append(all);
			fileList = "";
		});

	});
</script>

<?php

require '../view/_footer-bt.php';

?>