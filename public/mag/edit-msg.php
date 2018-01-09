<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');
include '../../functions/form.fn.php';
//ajout d'un commentaire magasin
include '../../functions/form.bt.fn.php';
include "../../functions/stats.fn.php";
$descr="détail message côté magasin";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

$idMsg=$_GET['msg'];
$idMag=$_SESSION['id'];
$msg=showThisMsg($pdoBt, $idMag, $idMsg);
$infoService=service($pdoBt,$msg['id_service']);
$to=$infoService['mailing'];
$objet="PORTAIL BTLec - nouveau message sur la demande du magasin " .$_SESSION['nom'];
$tplForBtlec="../mail/new_mag_msg.tpl.html";
$contentOne=$msg['who'];
$contentTwo=$_SESSION['id'];
$replies=showReplies($pdoBt, $idMsg);
// function sendMailEditMsg($mailingList,$subject,$tplLocation,$contentOne,$contentTwo,$link)

	// on supprime la var de session qui permet la redirection suite à l'ouverture du mail
	unset($_SESSION['goto']);


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
		//

		if(!recordReply($pdoBt,$idMsg)){
			$err ="votre réponse n'a pas pu être enregistrée (err 01)";
			die;
		}

		else
		{
				header('Location:'. $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
		}
		//-----------------------------------------
		//				envoi du mail
		//-----------------------------------------
		$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";
		if(sendMailEditMsg($to,$objet,$tplForBtlec,$contentOne,$contentTwo,$link))
		{
			$success=true;

			// header('Location:'. ROOT_PATH. '/public/btlec/dashboard.php?success='.$success);

		}
		else
		{
			$err= "Echec d'envoi de l'email";
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

?>


<div class="container">
	<!-- mini nav -->

	<!-- titre  -->
	<div class="row">
		<div class="col s12">
			<h1 class="blue-text text-darken-2 center">Suivi de votre demande</h1>
		</div>

	</div>



	<!--historique-->
	<!--dde d'origine-->

	<div class="row">
		<div class="card-panel">
				<h5 class="orange-text text-darken-2 boldtxt center">Votre demande du <?= date('d-m-Y', strtotime($msg['date_msg']))?> </h5>
				<p><span class="labelFor">Objet : </span><?=$msg['objet']?></p>
				<p><span class="labelFor">Message : </span><?=$msg['msg']?></p>
				<p><span class="labelFor">Pièce : </span><?=isAttached($msg['inc_file'])?></p>
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
	//nom de la personne qui a répondu si bt
	$by=repliedByIntoName($pdoBt,$reply['replied_by']);
	//mise en forme différente suivant réponse BT ou mag
	// on sait que c'est réponse mag si $by est vide car
	// la fonction va rechercher le nom de la personne
	// qui a répondu dans la table BT
	if(is_null($by))
	{
		// $color="blue-text";
		$by="Magasin ". $_SESSION['nom'];
		$by="<p><span class='labelFor'>Par :</span> " .$by ."</p>";
		$side='moveToRight';

	}
	else
	{
		$color="orange-text";
		$by="<p><span class='labelFor'>Par :</span> BTLEC - " .$by ."</p>";
		$side='moveToLeft';

	}
	?>
	<div class="row">
		<div class="card-panel <?= $side ?>">
			<p><span class="labelFor">Date du message :</span> <?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
			<?= $by ?>
			<p><span class="labelFor">Message :</span><?= $reply['reply'] ?></p>
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
						<button class="btn" type="submit" name="post-reply">Ajouter</button>
					</div>
				</div>

			</form>
		</div>

			<!-- </div> -->
	</div>

	</div>



</div>

<?php


include('../view/_footer.php');
 ?>

</body>
</html>







