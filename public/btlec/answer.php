<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}
//on supprime la var qui mémorise le lien
unset($_SESSION['goto']);

require_once  '../../vendor/autoload.php';

include '../../config/db-connect.php';

require '../../functions/upload.fn.php';

require "../../functions/stats.fn.php";

require "../../Class/BtUserManager.php";
require "../../Class/MsgManager.php";

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page = (basename(__FILE__));
$page = explode(".php", $page);
$page = $page[0];
$cssFile = ROOT_PATH . "/public/css/" . $page . ".css";


$errors=[];

//------------------------------
//	ajout enreg dans stat
//------------------------------<

$descr = "detail d'une demande mag côté BT";
$page = basename(__file__);
$action = "consultation";
addRecord($pdoStat, $page, $action, $descr);

//------------------------------------------------------------
//				INITIALISATION
//------------------------------------------------------------

$msgManager = new MsgManager();
$oneMsg = $msgManager->getDemande($pdoBt, $_GET['msg']);
$replies = $msgManager->getListReplies($pdoBt, $_GET['msg']);

$err = array();
$filenameList = array();



// verif si le user a le droit de cloturer la demande sans répondre
function isUserInGroup($pdoBt, $idWebuser, $groupName)
{

	$req = $pdoBt->prepare("SELECT * FROM groups WHERE id_webuser= :idWebuser AND group_name= :groupName");
	$req->execute(array(
		":idWebuser" => $idWebuser,
		":groupName" => $groupName
	));

	return $req->rowCount();
}

function recordReply($pdoBt, $idMsg, $file)
{

	$reply = strip_tags($_POST['reply']);
	$reply = nl2br($reply);
	$insert = $pdoBt->prepare('INSERT INTO replies (id_msg, reply, replied_by, date_reply,inc_file) VALUES (:id_msg, :reply, :replied_by, :date_reply, :inc_file)');
	$result = $insert->execute(array(
		':reply'		=> $reply,
		':date_reply'	=> date('Y-m-d H:i:s'),
		':id_msg'		=> $idMsg,
		':replied_by'	=> $_SESSION['id'],
		':inc_file'		=> $file
	));
	return $result;
}

function majEtat($pdoBt, $idMsg, $etat)
{
	$update = $pdoBt->prepare('UPDATE msg SET etat= :etat  WHERE id= :id');
	$result = $update->execute(array(
		':etat'		=> $etat,
		':id'		=> $idMsg
	));
	return $result;
}

function recPwd($pdoUser, $idWebuser)
{
	$req = $pdoUser->prepare("UPDATE users SET nohash_pwd= :pwd WHERE id= :id");
	$req->execute(array(
		":pwd"	=> $_POST['mdp'],
		":id"	=> $idWebuser
	));
}

function formatPJ($incFileStrg)
{
	$href = "";
	if (!empty($incFileStrg)) {
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg = explode('; ', $incFileStrg);
		for ($i = 0; $i < count($incFileStrg); $i++) {
			$ico = "<i class='fa fa-paperclip fa-lg pl-5 pr-3 hvr-pop' aria-hidden='true'  ></i>";
			$href .= "<a class='pj' href='" . URL_UPLOAD . "mag/" . $incFileStrg[$i] . "' target='blank'>" . $ico . "ouvrir</a>";
		}
		$href = "<p>" . $href . "</p>";
	}
	return $href;
}



if (isset($_POST['post-reply'])) {
	if ((empty($_POST['reply']))) {
		$errors[] = "merci de remplir tous les champs";
	} else {
		extract($_POST);
		//si pas de fichier joint
		if (isset($_FILES['incfile']['name'][0]) && empty($_FILES['incfile']['name'][0])) {
			$allfilename = "";
			// ajout mdp dans webuser
			if (isset($mdp)) {
				recPwd($pdoUser, $oneMsg['id_mag']);
			}
		} else {
			// fichier joint
			// ajout mdp dans webuser
			if (isset($mdp)) {
				recPwd($pdoUser, $oneMsg['id_mag']);
			}
			$uploadDir = DIR_UPLOAD . 'mag\\';
			//on initialise authorized à 0, si il reste à 0, tous les fichiers sont autorisés, sinon
			//on incrémente et on bloque le message si on n'est pas égal à 0
			$authorized = 0;
			//on stocke les extensions de fichiers interdits pour afficher message d'erreur
			$typeInterdit = "";


			$nbFiles = count($_FILES['incfile']['name']);
			$totalFileSize = 0;
			for ($f = 0; $f < $nbFiles; $f++) {
				$filename = $_FILES['incfile']['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB
				$totalFileSize += $_FILES['incfile']['size'][$f];

				if ($_FILES['incfile']['size'][$f] > $maxFileSize) {
					$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
				}
			}
			if ($totalFileSize > $maxFileSize) {
				$errors[] = 'Attention le poids total des pièces jointes dépasse la taille autorisée de 5 Mo';
			}



			//  si tout va bien, on upload
			if (count($errors) == 0) {
				for ($f = 0; $f < $nbFiles; $f++) {
					$filename = $_FILES['incfile']['name'][$f];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					// Get filename without extesion
					$filename_without_ext = basename($filename, '.' . $ext);
					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$uploaded = move_uploaded_file($_FILES['incfile']['tmp_name'][$f], $uploadDir . $filename);
					if ($uploaded == false) {
						$errors[] = "impossible de télécharger le fichier";
					} else {
						$filenameList[] = $filename;
					}
				}
				$allfilename = implode("; ", $filenameList);
			}
		}


		if (count($errors) == 0) {
			if (!recordReply($pdoBt, $_GET['msg'], $allfilename)) {
				array_push($errors, "votre réponse n'a pas pu être enregistrée (err 01)");
			} else {
				//-----------------------------------------
				//				envoi du mail
				//-----------------------------------------
				$tpl = "../mail/new_reply_from_bt.tpl.html";
				$tplpwd = "../mail/identifiant.tpl.html";


				if (VERSION != "_") {
					$dest[] = $oneMsg['email'];
				} else {
					$dest[] = "valerie.montusclat@btlec.fr";
				}
				$etat = "";
				$vide = "";
				$link = "Cliquez <a href='" . SITE_ADDRESS . "/index.php?mag/edit-msg.php?msg=" . $_GET['msg'] . "'>ici pour revoir votre demande</a>";

				if (!empty($_POST['mdp'])) {

					$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
					$mailer = new Swift_Mailer($transport);
					// $mail=sendMail($to,$objet,$tplpwd,$oneMsg['deno'],$mdp,$link);

					$htmlMail = file_get_contents("../mail/identifiant.tpl.html");
					$htmlMail = str_replace('{LINK}', $link, $htmlMail);
					$htmlMail = str_replace('{CONTENT1}', $oneMsg['deno'], $htmlMail);
					$htmlMail = str_replace('{CONTENT2}', $mdp, $htmlMail);
					$subject = "PORTAIL BTLec - réponse à votre demande";
					$message = (new Swift_Message($subject))
						->setBody($htmlMail, 'text/html')
						->setFrom(EXPEDITEUR_MAIL)
						->setTo($dest);
					if (!$mailer->send($message, $failures)) {
						print_r($failures);
					} else {
						header('Location:' . ROOT_PATH . '/public/btlec/dashboard.php?success=1');
					}
				} else {
					$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
					$mailer = new Swift_Mailer($transport);
					$htmlMail = file_get_contents("../mail/new_reply_from_bt.tpl.html");
					$htmlMail = str_replace('{LINK}', $link, $htmlMail);
					$htmlMail = str_replace('{CONTENT1}', $oneMsg['objet'], $htmlMail);

					$subject = "PORTAIL BTLec - réponse à votre demande " . $oneMsg['objet'];
					$message = (new Swift_Message($subject))
						->setBody($htmlMail, 'text/html')
						->setFrom(EXPEDITEUR_MAIL)
						->setTo($dest);
					if (!$mailer->send($message, $failures)) {
						print_r($failures);
					}
				}
			}
			if (empty($errors)) {
				//checkbox 'clos' =>  checked or not checked => majEtat
				if (isset($_POST['clos'])) {
					$etat = "clos";
				} else {
					$etat = "en cours";
				}

				if (!majEtat($pdoBt, $_GET['msg'], $etat)) {
					array_push($err, "votre réponse n'a pas pu être enregistrée (err 02)");
				}
			}
			if(empty($errors)){
				header('Location:'. ROOT_PATH.'/public/btlec/dashboard.php?success=1');
			}
		}
	}
}



// ajout du 22/08/2018 : btn pour cloturer les tickets sans envoyer de réponse ni de mail
// accès seulement au groupe admin
if (isset($_POST['closing'])) {
	if (isset($_POST['close-no-msg'])) {
		$etat = "clos";
		if (!majEtat($pdoBt, $_GET['msg'], $etat)) {
			$err = "impossible de clore le dossier";
			die;
		} else {

			header('Location:' . ROOT_PATH . '/public/btlec/dashboard.php?success=2');
		}
	}
}
include '../view/_head-bt.php';
include '../view/_navbar.php';

?>
<div class="container">

	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5">Demande n° <?= $oneMsg['idMsg'] ?></h1>
		</div>
		<div class="col-auto pt-2">
			<a href="dashboard.php">
				<div class="btn btn-primary">Retour</div>
			</a>

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



	<div class="row border rounded p-3">
		<div class="col">
			<div class="row text-grey">
				<div class="col">
					<span class="font-weight-bold">Magasin : </span><?= $oneMsg['deno'] . ' - ' . $oneMsg['code_bt'] . '/' . $oneMsg['galec'] ?><i class="fas fa-user pl-5 pr-2"></i><?= $oneMsg['who'] ?>
				</div>
				<div class="col-lg-3">

					<i class="fas fa-calendar pr-2"></i><?= date('d-m-Y', strtotime($oneMsg['date_msg'])); ?>
					<i class="fas fa-clock pr-2 pl-5"></i><?= date('H:i', strtotime($oneMsg['date_msg'])); ?>

				</div>
			</div>
			<div class="row text-grey border-bottom pb-2">
				<div class="col">
					<span class="font-weight-bold">Objet : </span><?= $oneMsg['objet'] ?>
				</div>
				<div class="col-lg-3">
					<span class="font-weight-bold">Service : </span><?= $oneMsg['service'] ?>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col">
					<?= $oneMsg['msg'] ?>
				</div>
			</div>
			<?php if (!empty($oneMsg['inc_file'])) : ?>
				<?php $filesArr = explode('; ', $oneMsg['inc_file']); ?>
				<div class="row pt-3">
					<div class="col">
						<?php foreach ($filesArr as $keyfile => $value) : ?>
							<a class="link-main-blue pr-5" href="<?= URL_UPLOAD . "mag/" . $filesArr[$keyfile] ?>"><i class="fas fa-paperclip pr-2"></i>document</a>
						<?php endforeach ?>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h4 class="text-main-blue text-center">Echanges</h4>
		</div>
	</div>
	<?php if (!empty($replies)) : ?>
		<div class="row  border rounded">
			<div class="col">
				<?php foreach ($replies as $reply) : ?>
					<?php
					if (empty($reply['fullname'])) {
						$magOrBt = 'mag';
						$who = $oneMsg['deno'] . ' - ' . $oneMsg['who'];
						$bg = 'alert-orange';
					} else {
						$who = "BTLec Est - " . $reply['fullname'];
						$magOrBt = 'bt';
						$bg = 'alert-primary';
					}
					?>
					<div class="row font-italic <?= $magOrBt ?>">
						<div class="col">
							<?= $who ?>
						</div>
						<div class="col-auto">
							<i class="fas fa-calendar pr-2"></i><?= date('d-m-Y', strtotime($reply['date_reply'])) ?>
						</div>
					</div>
					<div class="row">
						<div class="col mx-3 alert <?= $bg ?>">
							<?= $reply['reply'] ?>
							<?php if (!empty($reply['inc_file'])) : ?>
								<?php $filesArr = explode('; ', $reply['inc_file']); ?>
								<div class="row">
									<div class="col">
										<?php foreach ($filesArr as $keyfile => $value) : ?>
											<a class="link-main-blue pr-5" href="<?= URL_UPLOAD . "mag/" . $filesArr[$keyfile] ?>"><i class="fas fa-paperclip pr-2"></i>document</a>
										<?php endforeach ?>
									</div>
								</div>
							<?php endif ?>
						</div>
					</div>


				<?php endforeach ?>
			</div>
		</div>
	<?php endif ?>

	<!-- formulaire de réponse BT -->
	<div class="row mt-5">
		<div class="col reply">
			<h4 class="text-main-blue">Envoyer une réponse :</h4>
			<div class="inside-mag">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?msg=' . $_GET['msg'] ?>" method="post" enctype="multipart/form-data" id="answer">
					<div class="input-field white">
						<div class="form-group">

							<label for="reply">Message</label>
							<textarea class="form-control" name="reply" id="reply"><?= isset($_POST['reply']) ? $_POST['reply'] : false ?></textarea>
						</div>
						<?php if ($oneMsg['objet'] == "demande d'identifiants") : ?>
							<div class="row">
								<div class='col'>
									<label for="mdp">Mot de passe du magasin :</label><br><br>
									<input class="browser-default" name="mdp" type="text" id="mdp">
								</div>
							</div>
						<?php endif ?>

						<div class="row">
							<div class="col">
								Joindre un document à votre réponse:
								<br><i>(pour ajouter plusieurs fichiers, maintenez la touche ctrl pendant que vous sélectionnez les fichiers)</i>
							</div>
						</div>
						<div class="row" id="file-upload">
							<div class="col">
								<div id="upload-zone">
									<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="">
									<div id="filelist"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class='col' id="wait"></div>
							<div class='col-auto'>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="clos" checked="checked" name="clos">
									<label class="form-check-label" for="clos">Clôturer la demande</label>
								</div>
							</div>
							<div class='col-auto'>
								<button class="btn btn-primary" type="submit" name="post-reply">Répondre</button>
							</div>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>

	<?php if (isUserInGroup($pdoBt, $_SESSION['id'], "admin")) : ?>

		<div class="row mt-5">
			<div class="col reply">
				<h4 class="text-main-blue">Clôturer la demande sans envoyer de réponse :</h4>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?msg=' . $_GET['msg'] ?>" method="post">
					<div class="row">
						<div class='col'>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" checked="checked" name="close-no-msg">
								<label class="form-check-label" for="close-no-msg">clôturer la demande</label>
							</div>
						</div>
						<div class='col'>
							<p class="center">
								<button class="btn btn-primary" type="submit" name="closing">Cloturer</button>
							</p>
						</div>
					</div>
				</form>
			</div>
		</div>

	<?php endif ?>



	<div class="row mt-5">
		<div class="col reply">
			<h4 class="text-main-blue">Réaffecter la demande :</h4>

			<?php if ($_SESSION['id_service'] == 5 || $_SESSION['id_service'] == 16 || $_SESSION['id_service'] == 6 ||  $oneMsg['id_service'] == $_SESSION['id_service'] || ($_SESSION['id_service'] == 4 && $oneMsg['id_service'] == 14)) : ?>

				<p>La demande ne concerne pas votre service ? <a href="chg.php?msg=<?= $_GET['msg'] ?>">Cliquez ici pour réaffecter la demande</a></p>

			<?php else : ?>
				<p>Cette demande ne concerne pas ou plus votre service, vous ne pouvez pas la réaffecter</p>
			<?php endif ?>


		</div>
	</div>
	<div class="row pb-5">
		<div class="col"></div>
	</div>





</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("#answer").submit(function(event) {
			$("#wait").append('<i class="fa fa-spinner" aria-hidden="true"></i>&nbsp;&nbsp;<span class="pl-3">Merci de patienter</span>')
		});

		function getReadableFileSizeString(fileSizeInBytes) {
			var i = -1;
			var byteUnits = [' ko', ' Mo', ' Go'];
			do {
				fileSizeInBytes = fileSizeInBytes / 1024;
				i++;
			} while (fileSizeInBytes > 1024);

			return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
		};

		var fileName = '';
		var fileList = '';
		var fileSize = "";
		var totalFileSize = 0;
		var fileSizeReadable = "";
		var warning = "";
		var resume = "";
		var warningTotal = "";
		$('input[type="file"]').change(function(e) {

			$('#filelist').empty();
			var nbFiles = e.target.files.length;
			for (var i = 0; i < nbFiles; i++) {
				// var fileName = e.target.files[0].name;
				//5120000 = 5Mo
				fileSizeReadable = getReadableFileSizeString(e.target.files[i].size);
				fileSize = e.target.files[i].size;
				totalFileSize += e.target.files[i].size;
				if (fileSize > 5120000) {
					warning = "<span class='warning-msg'>attention ce fichier pèse plus de 5Mo ! vous ne pourrez pas envoyer votre réponse</span>";
				}
				fileName = e.target.files[i].name + " ( " + fileSizeReadable + ") " + warning + "<br>";
				fileList += fileName;
			}
			if (totalFileSize > 5120000) {
				resume = "Poids total des fichiers : " + getReadableFileSizeString(totalFileSize);
				warningTotal = "<br><span class='warning-msg'>Attention le poids total des fichiers excède 5Mo, votre réponse ne pourra pas être envoyée</span><br>";
			}
			// console.log(fileList);
			titre = '<p><span class="boldtxt">Fichier(s) : </span><br>'
			end = '</p>';
			all = titre + fileList + warningTotal + resume + end;
			$('#filelist').append(all);
			fileList = "";
		});

	});
</script>
<?php
include('../view/_footer-bt.php');
?>