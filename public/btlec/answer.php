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


//template html et données pour envoi mail
$tpl="../mail/new_reply_from_bt.tpl.html";
$objet="PORTAIL BTLec - réponse à votre demande";
mb_internal_encoding('UTF-8');
$objet = mb_encode_mimeheader($objet);
$objetdde=oneMsg['objet'];
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
			$err ="erreur à l'enregistrement de la réponse";
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

?>
<div class="container">
	<div class="row">
		<div class="col l12">

			<!-- $_SESSION['page_request'] -->
			<p><a href="dashboard.php" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
		<div class="col l12 ">
			<div class="padding-all">
				<div class="row">
					<h4 class="light-blue-text text-darken-2">Demande :</h4>
					<p><span class="boldtxt">Objet : </span><?=$oneMsg['objet'] ?></p>
					<p><span class="boldtxt">Message : </span><?=$oneMsg['msg'] ?></p>
					<p><span class="boldtxt"></span><?=isAttached($oneMsg['inc_file']) ?></p>

				</div>
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

</div>


<?php



include('../view/_footer.php');
 ?>

</body>
</html>







