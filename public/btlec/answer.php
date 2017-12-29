<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//on supprime la var qui mémorise le lien
unset($_SESSION['goto']);

include '../view/_head.php';
include '../view/_navbar.php';
include '../../functions/form.bt.fn.php';
//afichage de l'historique des réponses
include '../../functions/form.fn.php';

//------------------------------------------------------------
//				affiche lien vers piec jointe si existe
//------------------------------------------------------------
function isAttached($dbData)
{
	global $version;
	$href="";
	if(!empty($dbData))
	{
		$ico="<i class='fa fa-paperclip fa-lg' aria-hidden='true'></i>";
		$href= "Pièce jointe : &nbsp; &nbsp; &nbsp; &nbsp; <a href='http://172.30.92.53/".$version ."upload/mag/" . $dbData . "'>" .$ico ."&nbsp; &nbsp; ouvrir</a>";
	}
	return $href;
}
//-----------------------------------------
//				fn pour envoi mail
//-----------------------------------------
function sendMail($to,$subject,$tplLocation,$objetdde,$link)
{
	$tpl = file_get_contents($tplLocation);
	$tpl=str_replace('{OBJETDDE}',$objetdde,$tpl);
	$tpl=str_replace('{LINK}',$link,$tpl);


	$htmlContent=$tpl;
// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
	$headers .= 'From: ne_pas_repondre@btlec.fr>' . "\r\n";
	$headers .= 'Cc: ' . "\r\n";
	$headers .= 'Bcc:' . "\r\n";

	if(mail($to,$subject,$htmlContent,$headers))
	{
		return true;
	}
	else
	{
		return false;
	}

}

// pour affichage contenu msg
$idMsg=$_GET['msg'];
$oneMsg=showOneMsg($pdoBt,$idMsg);




//contenu histo des reponses
$replies=showReplies($pdoBt, $idMsg);


//template html et données pour envoi mail
$tpl="../mail/new_reply_from_bt.tpl.html";
$objet="PORTAIL BTLec - réponse à votre demande";
mb_internal_encoding('UTF-8');
$objet = mb_encode_mimeheader($objet);
$objetdde=$oneMsg['objet'];
$to = $oneMsg['email'];


// listId récupéré qd insert données dans db

$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter votre réponse</a>";



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
		// rec db
		if(!recordReply($pdoBt,$idMsg)){
			$err ="votre réponse n'a pas pu être enregistrée (err 01)";
			die;
		}
		$etat="en cours";
		if(!majEtat($pdoBt,$idMsg, $etat))
		{
			$err="votre réponse n'a pas pu être enregistrée (err 02)";
			die;
		}
		//-----------------------------------------
		//				envoi du mail
		//-----------------------------------------
		if(sendMail($to,$objet,$tpl,$objetdde,$link))
		{
			$success=true;
			header('Location:'. ROOT_PATH. '/public/btlec/dashboard.php?success='.$success);

		}
		else
		{
			$err= "Echec d'envoi de l'email";
		}
	}
}
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
	<!--la demande	 -->
	<div class="row">
		<div class="col l12">
			<p><a href="dashboard.php" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
	</div>
	<h1 class="blue-text text-darken-2">Répondre / cloturer un dossier</h1>

	<h5 class="light-blue-text text-darken-2">La demande :</h5>
	<div class="row box-border">
		<div class="col l12 ">
					<p><span class="boldtxt">Objet : </span><?=$oneMsg['objet'] ?></p>
					<p><span class="boldtxt">Message : </span><?=$oneMsg['msg'] ?></p>
					<p><span class="boldtxt"></span><?=isAttached($oneMsg['inc_file']) ?></p>
		</div>
	</div>
	<p>&nbsp;</p>
	<h5 class="light-blue-text text-darken-2">Historique des réponses :</h5>


	<!-- histo des réponses	 -->

	<?php foreach($replies as $reply): ?>
	<div class="row box-border">
		<div class="col l6">
			<p class="orange-text text-darken-2 boldtxt">Réponse du : <?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
		</div>
		<div class="col l6">
			<p class="orange-text text-darken-2 boldtxt">Par : <?= repliedByIntoName($pdoBt,$reply['replied_by'])?></p>
		</div>
		<div class="col l12">
			<p><?= $reply['reply'] ?></p>
		</div>
	</div>
	<?php endforeach ?>
	<h5 class="light-blue-text text-darken-2">Traitement du dossier :</h5>
	<div class="down"></div>
	<div class="row">
		<div class="col l12 grey lighten-2">
			<div class="padding-all">
				<form action="answer.php?msg=<?=$idMsg ?>" method="post">
					<div class="row align-right">
						<button class="btn" type="submit" name="close">cloturer le dossier</button>
					</div>
				</form>
			</div>
		</div>
	</div>


<div class="row">
	<div class="col l12 grey lighten-4">
		<div class="padding-all">



			<form action="answer.php?msg=<?=$idMsg ?>" method="post" enctype="multipart/form-data">
				<!--MESSAGE-->
				<div class="row">
					<div class="input-field">
						<label for="reply"></label>
						<textarea class="materialize-textarea" placeholder="votre réponse" name="reply" id="reply" ></textarea>
					</div>
				</div>

				<!--BOUTONS-->

				<div class="row align-right">
					<button class="btn" type="submit" name="post-reply">Répondre</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row">
	<?= isset($err)?$err:false; ?>
</div>

</div>  <!--container


<?php



include('../view/_footer.php');
 ?>








