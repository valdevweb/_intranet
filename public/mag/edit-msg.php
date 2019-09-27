<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require '../../functions/form.fn.php';
//ajout d'un commentaire magasin - affichage de la liste des fichiers joints
require '../../functions/form.bt.fn.php';
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';


require "../../functions/stats.fn.php";
$descr="détail message côté magasin";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



$idMsg=$_GET['msg'];
$idMag=$_SESSION['id'];
$msg=showThisMsg($pdoBt, $idMag, $idMsg);
$infoService=service($pdoBt,$msg['id_service']);
$to=$infoService['mailing'];
$objet="PORTAIL BTLec - ajout d'un commentaire sur la demande du magasin " .$_SESSION['nom'];
$tplForBtlec="../mail/new_mag_msg.tpl.html";

$contentOne=$msg['who'];
$contentTwo=$_SESSION['nom'];

$replies=showReplies($pdoBt, $idMsg);
//si fichier à uploader
$isFileToUpload=isFileToUpload();
		//	if(sendMail($to,$objet,$tplForBtlec,$contentOne,$contentTwo,$link))

function formatPJ($incFileStrg)
{
	global $version;
	$href="";
	if(!empty($incFileStrg))
	{
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg=explode( '; ', $incFileStrg );
		foreach ($incFileStrg as $dbData)
		{
		$ico="<i class='fa fa-paperclip fa-lg pl-5 pr-3 hvr-pop' aria-hidden='true'  ></i>";
		$href.= "<a class='pj' href='http://172.30.92.53/".$version ."upload/mag/" . $dbData . "'>" .$ico ."ouvrir</a>";
		}
		$href="<p>".$href."</p>";

	}

	return $href;
}




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
			//créa du lien pour le mail  BT
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$idMsg."'>ici pour consulter le message</a>";
			// $link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";
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
if($msg['etat']!='clos')
	{
		$btnAnswer='<a href= "#mag-msg" ><i class="fa fa-pencil-square-o prefix fa-lg pr-2" aria-hidden="true"></i>Répondre</a>';
		$btnReopen="";
	}
	else
	{
		$btnAnswer="";
		$btnReopen='<a href="unlock.php?id_msg='.$_GET['msg']. '"><i class="fa fa-unlock-alt prefix fa-lg pr-3" aria-hidden="true"></i> Rouvrir</a>';
	}

include('../view/_head-mig.php');
include('../view/_navbar.php');
?>
<!-- <div class="container-fluid"> -->
	  <div class="floating-menu-vm">
	  	<h3>Actions</h3>
		<a href= "histo-mag.php"><i class="fa fa-chevron-circle-left fa-lg pr-3" aria-hidden="true"></i>Retour</a>
		<?= $btnAnswer ?>
		<?= $btnReopen ?>

  <!-- </div> -->
</div>
<div class="container">
	<!-- titre  -->
	<div class="row">
		<div class="col-12">

			<h1 class="blue-text text-darken-4 no-margin">Votre demande : <br><span class='sub-h1'>n° <?= $_GET['msg']?> - <?=$msg['objet']?> </span></h1>
		</div>
	</div>
	<!-- message 1  -->
	<div class="row">
		<div class="col-12">
			<div class="card-panel mag mb-5">
				<p class="text-right date"><?= date('d-m-Y', strtotime($msg['date_msg']))?></p>
				<p><?=$msg['msg']?></p>
				<?php
				if(!empty($msg['inc_file']))
				{
					echo "<p><span class='labelFor'>Pièce jointe : </span></p>";
					echo "<p>".formatPJ($msg['inc_file'])."</p>";
				}
				?>
			</div>
			<div class="center-text">
				<hr class="line">
			</div>
		</div>
	</div>
	<!-- reponses -->
	<?php foreach($replies as $reply): ?>
	<?php
	//nom de la personne qui a répondu si bt
	$by=repliedByIntoName($pdoUser,$reply['replied_by']);
	//mise en forme différente suivant réponse BT ou mag
	// on sait que c'est réponse mag si $by est vide car
	// la fonction va rechercher le nom de la personne
	// qui a répondu dans la table BT
	if(is_null($by))
	{
		$by="";
		$side='mag';;
		$logo="../img/logos/leclerc-rond-50.jpg";

	}
	else
	{
		$color="orange-text";
		$by="<p class='nom'>" .$by ."</p>";
		$side='bt';
		$logo="../img/logos/bt-rond-50.jpg";


	}
	?>
	<?= $by ?>
	<div class="row">
		<div class="col-12">
		<div class="card-panel <?= $side ?>">
			<img class="w3-circle" src="<?=$logo ?>">

			<p class="text-right date"><?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
			<p><?= $reply['reply'] ?></p>
				<?php
				if(!empty($reply['inc_file']))
				{
					echo "<p><span class='labelFor'>Pièce(s) jointe(s) :</p>";
					echo  "<p>".formatPJ($reply['inc_file'])."</p>";
				}
				 ?>
</div>
		</div>
	</div>
	<?php endforeach ?>
	<?php
		ob_start();
	?>
	<br><br>




	<div class="row">
		<div class="col">
			<div class="bg-white border px-5 py-3">
				<h4 class="blue-text text-darken-4"><i class="fa fa-pencil-square-o prefix pl-1 pr-3 fa-lg" aria-hidden="true"></i><strong>Ajouter un message :</strong></h4>
				<form action="edit-msg.php?msg=<?=$idMsg ?>" method="post" enctype="multipart/form-data" id="mag-msg">
					<div class="form-group">
						<label for="reply"></label>
						<textarea class="form-control" placeholder="Votre message" name="reply" id="reply" ></textarea>
					</div>
					<div class="pt-5 pb-2" id="file-upload">
						<p class="blue-text text-darken-4 pb-2"><i class="fa fa-download pr-3 fa-lg" aria-hidden="true"></i>Envoyer des pièces jointes</p>
						<p><input type="file" name="file_1"  class='form-control-file' ></p>
							<p class="pr-1 pt-2 blue-text text-darken-4" id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Ajouter un fichier supplémentaire</a></p>
					</div>
					<div class="input-field text-right">
						<button class="btn" type="submit" name="post-reply">Envoyer</button>
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
		</div>
	</div>
<?php
	// si le message n'est pas clos, on affiche le formulaire pour ajouter une réponse
	$newResponseForm=ob_get_clean();
	if($msg['etat']!='clos')
	{
		echo $newResponseForm;
	}




?>


</div>




<?php


include('../view/_footer.php');
 ?>

</body>
</html>







