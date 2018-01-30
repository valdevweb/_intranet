<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//on supprime la var qui mémorise le lien
unset($_SESSION['goto']);


require '../../functions/form.bt.fn.php';
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';
//affichage de l'historique des réponses
require '../../functions/form.fn.php';
require "../../functions/stats.fn.php";


//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";




//------------------------------
//	ajout enreg dans stat
//------------------------------<

$descr="detail d'une demande mag côté BT";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

//------------------------------>

include '../view/_head.php';
include '../view/_navbar.php';

//------------------------------------------------------------
//				affiche lien vers piec jointe si existe
//------------------------------------------------------------


// pour affichage contenu msg
$idMsg=$_GET['msg'];
$oneMsg=showOneMsg($pdoBt,$idMsg);
//nom du magasin
$panoGalec=getPanoGalec($pdoUser,$oneMsg['id_mag']);
$magInfo=getMag($pdoBt,$panoGalec['galec']);
//contenu histo des reponses
$replies=showReplies($pdoBt, $idMsg);


//template html et données pour envoi mail
$tpl="../mail/new_reply_from_bt.tpl.html";
$objet="PORTAIL BTLec - réponse à votre demande";
mb_internal_encoding('UTF-8');
$objet = mb_encode_mimeheader($objet);

$objetdde=$oneMsg['objet'];
$to = $oneMsg['email'];
$etat="";
$vide="";
$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter votre réponse</a>";
	// if(sendMail($to,$objet,$tpl,$objetdde,$vide,$link))


$err=array();
$service=service($pdoBt,$oneMsg['id_service']);
//test valeur $_FILE, si renvoi true => au moins un fichier à uploader
$isFileToUpload=isFileToUpload();

// id du message auquel bt répond donc $_GET['msg']

if(isset($_POST['post-reply']))
{
	if((empty($_POST['reply'])))
	{
		echo "merci de remplir tous les champs";
	}

	else
	{
		extract($_POST);
		//si pas de fichier joint
		if (!$isFileToUpload)
		{
			//pas de pièce jointe
			$file="";

			echo "pas de piece jointe";

		}
		else
		// fichier joint
		{
			//------------------------------
			//			upload du fichier
			//------------------------------
			$uploadDir= '..\..\..\upload\mag\\';


			//on initialise authorized à 0, si il reste à 0, tous les fichiers sont autorisés, sinon
			//on incrémente et on bloque le message si on n'est pas égal à 0
			$authorized=0;
			//on stocke les extensions de fichiers interdits pour afficher message d'erreur
			$typeInterdit="";
			foreach ($_FILES as $fileDetails)
			{
				$authorizedFile=isAllowed($fileDetails['tmp_name'], $encoding=true);
				//tableau de fichier :
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
				//------------------------------
				//			msg avec piece jointe
				//			ajoute le msg dans db et
				//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
				//------------------------------
			}
			else
			{
				array_push($err, "l'envoi de fichiers de type ". $typeInterdit ." est interdit");

			}
		}
		//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			envoi mail au mag
		//------------------------------
		if(!recordReply($pdoBt,$idMsg,$file))
		{
			array_push($err, "votre réponse n'a pas pu être enregistrée (err 01)");

		}
		else
		{
					//-----------------------------------------
					//				envoi du mail
					//-----------------------------------------
			if(sendMail($to,$objet,$tpl,$objetdde,$vide,$link))
			{
				$success=true;
				header('Location:'. ROOT_PATH. '/public/btlec/dashboard.php?success='.$success);

			}
			else
			{
				array_push($err, "Echec d'envoi de l'email");
			}
		}

		//checkbox 'clos' =>  checked or not checked => majEtat
		if(isset($_POST['clos']))
		{
			$etat="clos";
		}
		else
		{
			$etat="en cours";
		}

		if(!majEtat($pdoBt,$idMsg, $etat))
		{
			array_push($err, "votre réponse n'a pas pu être enregistrée (err 02)");
		}




		//------------------------------------------
		//	ajout enreg ection dans stat
		//-----------------------------------------<
		if(!empty($err))
		{
			$descr="succès envoi réponse ";
		}
		else
		{
			$descr="erreur de traitement";
		}
		$page=basename(__file__);
		$action="envoi réponse BT => mag";
		addRecord($pdoStat,$page,$action, $descr);

		// fin stats ------------------------------>

	}
}		//fin soumission formulaire

//affichage
if (isset($_POST['close']))
{
	$etat="cloturé par BTlec";
	if(!majEtat($pdoBt,$idMsg, $etat)){
		$err="impossible de clore le dossier";
		die;
	}
}



?>
<div class="container">
	<!--nav -->
	<div class="row">
		<div class="col l12">
			<p><a href="dashboard.php" onClick="javascript:document.location.reload(true)" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
	</div>


	<h1 class="blue-text text-darken-4">Service <?=$service['full_name']?></h1>
	<br><br>

	<div class="row mag">
		<div class="col l12 reply">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>La demande du magasin :</h4>
			<hr>
			<br><br><br>
			<div class="inside-mag">
				<h5>Magasin : <?= $magInfo['mag'] .' - ' .$magInfo['galec']  ?></h5>
				<p><span class="labelFor">Objet : </span><?=$oneMsg['objet'] ?></p>
				<p><span class="labelFor">Message : </span><br><?=$oneMsg['msg'] ?></p>

				<?php
				if(isAttached($oneMsg['inc_file']))
				{
					echo '<p><span class="labelFor">Pièce(s) jointe(s)</span>'.isAttached($oneMsg['inc_file']) .'</p>';
				}

				?>
			</div>
		</div>
	</div>
	<p>&nbsp;</p>
	<?php foreach($replies as $reply): ?>
		<?php
	//reponse mag ou bt
		if($who=repliedByIntoName($pdoBt,$reply['replied_by']))
		{

			$magOrBt='mag';
		}
		else
		{
			$who=$oneMsg['who'];
			$magOrBt='bt';
		}
		$when = ' le '. date('d-m-Y à H:i', strtotime($reply['date_reply']));

		?>
		<!-- affichage des échanges -->
		<div class="row <?=$magOrBt?>">
			<div class="col l12">
				<h5 class="white-text">Réponse de <?= $who .' '.$when ?></h5>
			</div>
		</div>
		<div class="row reply">
			<div class="col l12">
				<p><?= $reply['reply'] ?></p>
			</div>
			<?php
		// pièces jointes
			if(isAttached($reply['inc_file']))
			{
				echo '<div class="col l12">';
				echo '<p><span class="labelFor">Pièce(s) jointe(s)</span>'.isAttached($reply['inc_file']) .'</p>';
				echo '</div>';
			}
			?>
		</div>
		<br>
	<?php endforeach ?>
	<br><br>
	<!-- formulaire de réponse BT -->
	<div class="row mag">
		<div class="col l12 reply">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Envoyer une réponse :</h4>
			<hr>
			<br><br>
<!-- 	<div class="row">
	<div class="col l12 m12 frm"> -->

		<div class="inside-mag">
			<form action="answer.php?msg=<?=$idMsg ?>" method="post" enctype="multipart/form-data" id="answer">
				<!--MESSAGE-->

				<div class="input-field white">
					<p><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i> Message :</p>

					<label for="reply"></label>
					<textarea class="browser-default" name="reply" id="reply" ><?=isset($_POST['reply'])? $_POST['reply']: false?></textarea>
				</div>
				<br><br>

				<div id="file-upload">

					<p>Joindre un document à votre réponse: </p>
						<div class="col l12">
							<p><input type="file" name="file_1" class='input-file'></p>
							<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Ajouter d'autres fichiers</a></p>
						</div>

					</div>
					<!--BOUTONS-->
					<div class="row">
						<div class='col l9'>
							<ul id="file-name"></ul>
						</div>

					</div>
					<div class="row">
						<div class='col l6'></div>

						<div class='col l3'>
							<p class="center">
								<input type="checkbox" class="filled-in" id="clos" checked="checked" name="clos" />
								<label for="clos">cloturer la demande</label>
							</p>
						</div>

						<div class='col l3'>
							<p class="center">
								<button class="btn" type="submit" name="post-reply">Répondre</button>
							</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<br><br>
	<div class="row mag">
		<div class="col l12 reply">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Réaffecter la demande :</h4>
			<hr>
			<br><br>
		<p>La demande ne concerne pas votre service ? <a href="chg.php?msg=<?=$idMsg?>">Cliquez ici pour réaffecter la demande</a></p>

		</div>
	</div>



	<!-- affichage des messages d'erreur -->
	<div class='row' id='erreur'>

		<?php
		if(!empty($err)){

			foreach ($err as $error) {
				echo  $error ."</br>";
			}
		}
		?>
	</div>

</div>  <!--container


<?php



include('../view/_footer.php');
 ?>








