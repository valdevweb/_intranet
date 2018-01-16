<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

//----------------------------------------------------
// REQUIRED FUNCTIONS
//----------------------------------------------------
require '../../functions/form.fn.php';
//ajout d'un commentaire magasin - affichage de la liste des fichiers joints
require '../../functions/form.bt.fn.php';
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';
// <------------------------------------
// STATS - add rec
//--------------------------------------
require "../../functions/stats.fn.php";
$descr="détail message côté magasin";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------
$idMsg=$_GET['msg'];
$idMag=$_SESSION['id'];
$msg=showThisMsg($pdoBt, $idMag, $idMsg);
$infoService=service($pdoBt,$msg['id_service']);
$to=$infoService['mailing'];
$objet="PORTAIL BTLec - nouveau message sur la demande du magasin " .$_SESSION['nom'];
$tplForBtlec="../mail/new_mag_msg.tpl.html";

$contentOne=$msg['who'];
$contentTwo=$_SESSION['nom'];

$replies=showReplies($pdoBt, $idMsg);
//si fichier à uploader
$isFileToUpload=isFileToUpload();
		//	if(sendMail($to,$objet,$tplForBtlec,$contentOne,$contentTwo,$link))


// on supprime la var de session qui permet la redirection suite à l'ouverture du mail
unset($_SESSION['goto']);

if(isset($_POST['post-reply']))
{
	if((empty($_POST['reply'])))
	{
		echo "merci de remplir tous les champs";
	}

	else
	{

		$err="";
		extract($_POST);
		if (!$isFileToUpload)
		{			//pas de pièce jointe
			$file="";
		}
		else		//avec pièce jointe
		{
			$uploadDir= '..\..\..\upload\mag\\';
			//on initialise authorized à 0, si il reste à 0, tous les fichiers sont autorisés, sinon
			//au moins un des fichiers n'est pas authorisé
			$authorized=0;
			//on stocke les extensions de fichiers interdits pour afficher message d'erreur
			$typeInterdit="";
			foreach ($_FILES as $fileDetails)
			{
				$authorizedFile=isAllowed($fileDetails['tmp_name'], $encoding=true);
				//tableau de fichier interdits :
				if($authorizedFile[0]=='interdit')
				{
					$authorized++;
					$typeInterdit.=$authorizedFile[1];
				}

			}

			//tous les fichiers sont autorisés
			if($authorized==0)
			{
				$hashedFileName=checkUploadNew($uploadDir, $pdoBt);
				// conversion en string
				$file= implode("; ", $hashedFileName);
			}
			else
			{
				array_push($err, "l'envoi de fichiers de type ". $typeInterdit ." est interdit");

			}
		}

		if(!recordReply($pdoBt,$idMsg,$file))
		{
			array_push($err, "votre réponse n'a pas pu être enregistrée (err 01)");

		}

		else
		{
		//-----------------------------------------
		//				envoi du mail
		//-----------------------------------------
			$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";
			if(sendMail($to,$objet,$tplForBtlec,$contentOne,$contentTwo,$link))
			{
				$success=true;
			}
			else
			{
				array_push($err, "Echec d'envoi de l'email");

			}
			header('Location:'. $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
		}

		// ------------------------------------------
		// 	ajout enreg ection dans stat
		// -----------------------------------------<
		if($err)
		{
			$descr="err : " . $err;
		}
		else
		{
			$descr="succès envoi message mag";
		}
		$page=basename(__file__);
		$action="ajout message mag à une demande existante";
		addRecord($pdoStat,$page,$action, $descr);

		// fin stats ------------------------------>

	}
}
//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
include('../view/_head.php');
include('../view/_navbar.php');
?>


<div class="container">
	<!-- mini nav -->


	<!-- titre  -->
	<div class="row">
		<div class="col s12">
			<h1 class="blue-text text-darken-2 center">Suivi de votre demande</h1>
		</div>

	</div>
	<div class="row">
		<!-- nav -->
		 <div class="col l2 m2 s2">
		 	<p>Service</p>
		 </div>
		  <div class="col l10 m10 s10">

		 </div>
	</div>

	<div class="row">
		<div class="main-msg">
				<div class="myrow">
					<h4 class="center">DEMANDE N°<?= $msg['id']?> DU <?= date('d-m-Y', strtotime($msg['date_msg']))?> </h4>
				</div>
				<div class="myrow">
					<div class="box-header">
						<p>Objet : </p>
					</div>
					<div class="main-content">
						<p><?=$msg['objet']?></p>
					</div>
				</div>
				<div class="myrow">
					<div class="box-header">
						<p>Message : </p>
					</div>
					<div class="main-content">
						<p><?=$msg['msg']?></p>
					</div>
				</div>
				<div class="myrow">
					<div class="box-header">
						<p>Pièce jointe : </p>
					</div>
					<div class="main-content">
						<p>&nbsp;<?=isAttached($msg['inc_file'])?></p>
					</div>
				</div>
			</div>
		</div>

	<div class="row">
		<div class="col l6 m6 s6">
			<p><a href= "<?= ROOT_PATH?>/public/mag/histo.php" class="blue-text text-darken-4"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
		<div class="col l6 m6 s6 align-right">
			<p><a href= "#mag-msg" class="blue-text text-darken-4">Ajouter un message &nbsp; &nbsp;<i class="fa fa-chevron-circle-down fa-2x" aria-hidden="true"></i></a></p>

		</div>
	</div>
	<!-- reponses -->
	<?php foreach($replies as $reply): ?>
	<?php
	//si correspondance replied_by dans table bt => reponse bt sion réponse mag
	$name=repliedByIntoName($pdoBt,$reply['replied_by']);
	if(is_null($name))
	{
		// $color="blue-text";
		$name= $_SESSION['nom'];
		$badge="magasin";
		$dialBox='dial-mag';
	}
	else
	{
		$badge="BTLEC";
		$dialBox='dial-bt';
	}
	?>
	<div class="row">
		<div class="dial <?= $dialBox ?>">
			<div class="myrow">
				<div class="box-header">
					<p>Date du message :</p>
				</div>
				<div class="box-content">
					 <p><?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
				</div>
			</div>
			<div class="myrow">
				<div class="box-header">
					<p>Par :</p>
				</div>
				<div class="box-content">
					<p>
					<?= $name ?>
					<span class="badge blue darken-3" ><?= $badge?></span>
					</p>

				</div>
			</div>
			<div class="myrow">
				<div class="box-header">
					<p>Message :</p>
				</div>
				<div class="box-content">
					<p><?= $reply['reply'] ?></p>
				</div>
			</div>
			<div class="myrow">
				<div class="box-header">
					<p>Pièces jointes : </p>
				</div>
				<div class="box-content">
					<p>&nbsp;	<?= isAttached($reply['inc_file'])?>
					</p>
				</div>
			</div>

		</div>
	</div>
	<?php endforeach ?>


	<div class="row">
		<h5 class="light-blue-text text-darken-2 center">Ajouter un message :</h5>
	</div>
	<div class="row">
		<div class="col l12 m12 s12">
			<form action="edit-msg.php?msg=<?=$idMsg ?>" method="post" enctype="multipart/form-data" id="mag-msg">
			<!--MESSAGE-->
				<div class="row">
					<div class="input-field white">
						<i class="fa fa-pencil-square-o prefix" aria-hidden="true"></i>
						<label for="reply"></label>
						<textarea class="materialize-textarea" placeholder="Votre message" name="reply" id="reply" ></textarea>
					</div>
				</div>
				<div class="row" id="file-upload">
					<fieldset>
						<legend>ajouter des pièces jointes</legend>
						<div class="col l6">
							<p><input type="file" name="file_1" class='input-file'></p>
							<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Envoyer d'autres fichiers</a></p>
						</div>
					</fieldset>
				</div>
				<div class="row align-right">
					<div class="input-field white">
							<button class="btn" type="submit" name="post-reply">Ajouter</button>
					</div>
				</div>
			<!-- zone affichage erreurs -->
						<?php
						if(!empty($err)){
							echo "<div class='row'><div class='col l12'><p class='warning-msg'>";
							foreach ($err as $error) {
								echo  $error ."</p></div>";
							}
						}
						?>
					</p>
			</form>
		</div>

			<!-- </div> -->
	</div>

	</div>




<?php


include('../view/_footer.php');
 ?>

</body>
</html>







