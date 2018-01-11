<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//on supprime la var qui mémorise le lien
unset($_SESSION['goto']);


include '../../functions/form.bt.fn.php';
//affichage de l'historique des réponses
include '../../functions/form.fn.php';
include '../../functions/mail.fn.php';
require "../../functions/stats.fn.php";

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
			$err="votre réponse n'a pas pu être enregistrée (err 02)";
			die;
		}

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
			$err= "Echec d'envoi de l'email";
		}
		//------------------------------------------
		//	ajout enreg ection dans stat
		//-----------------------------------------<
		if($err)
		{
			$descr="err : " . $err;
		}
		else
		{
			$descr="succès envoi réponse ";
		}
		$page=basename(__file__);
		$action="envoi réponse BT => mag";
		addRecord($pdoStat,$page,$action, $descr);

		// fin stats ------------------------------>

	}
}

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
					<p><span class="labelFor">Objet : </span><?=$oneMsg['objet'] ?></p>
					<p><span class="labelFor">Message : </span><br><?=$oneMsg['msg'] ?></p>
					<p><span class="labelFor">Pièce(s) jointe(s)</span><?=isAttached($oneMsg['inc_file']) ?></p>
				</div>
	</div>
	<p>&nbsp;</p>
	<h5 class="light-blue-text text-darken-2">Historique des réponses :</h5>

			<!-- exemple de if -->
			<?php
			if (!$replies)
			{
				echo "<div class='row box-border'><div class='col l12'><p>vous n'avez pas encore apporté de réponse au magasin</p></div></div>";
			}
			?>

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
	<p>&nbsp;</p>

	<h5 class="light-blue-text text-darken-2">Répondre au magasin :</h5>

	<div class="row">
	<div class="col l12 m12">
		<!-- <div class="padding-all"> -->
			<form action="answer.php?msg=<?=$idMsg ?>" method="post" enctype="multipart/form-data" id="answer">
				<!--MESSAGE-->
				<div class="row">
					<div class="input-field white">
						<i class="fa fa-pencil-square-o prefix" aria-hidden="true"></i>
						<label for="reply"></label>
						<textarea class="materialize-textarea" placeholder="votre réponse" name="reply" id="reply" ></textarea>
					</div>
				</div>
			<!--BOUTONS-->
				<div class="row">
					<div class='col l9'></div>
					<div class='col l3'>
						<p class="center">
							<input type="checkbox" class="filled-in" id="clos" checked="checked" name="clos" />
							<label for="clos">cloturer la demande</label>
						</p>
					</div>
					<div class='col l9'></div>
					<div class='col l3'>
						<p class="center">
						<button class="btn" type="submit" name="post-reply">Répondre</button>
					</p>
					</div>
				</div>
			</form>
		</div>
	<!-- </div> -->
</div>

<div class="row">
	<?= isset($err)?$err:false; ?>
</div>

</div>  <!--container


<?php



include('../view/_footer.php');
 ?>








