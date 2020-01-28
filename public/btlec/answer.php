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
$tplpwd="../mail/identifiant.tpl.html";
$objet="PORTAIL BTLec - réponse à votre demande";
mb_internal_encoding('UTF-8');
$objet = mb_encode_mimeheader($objet);

$objetdde=$oneMsg['objet'];
$to = $oneMsg['email'];
$etat="";
$vide="";
$link="Cliquez <a href='".SITE_ADDRESS."/index.php?mag/edit-msg.php?msg=".$idMsg."'>ici pour revoir votre demande</a>";

// $link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter votre réponse</a>";
	// if(sendMail($to,$objet,$tpl,$objetdde,$vide,$link))


$err=array();
$service=service($pdoBt,$oneMsg['id_service']);
$filenameList=array();


//test valeur $_FILE, si renvoi true => au moins un fichier à uploader
$isFileToUpload=isFileToUpload();

function isUserInGroup($pdoBt,$idWebuser,$groupName){

	$req=$pdoBt->prepare("SELECT * FROM groups WHERE id_webuser= :idWebuser AND group_name= :groupName");
	$req->execute(array(
		":idWebuser" =>$idWebuser,
		":groupName" =>$groupName
	));

	return $req->rowCount();
}





function recPwd($pdoUser, $idWebuser)
{
	$req=$pdoUser->prepare("UPDATE users SET nohash_pwd= :pwd WHERE id= :id");
	$req->execute(array(
		":pwd"	=>$_POST['mdp'],
		":id"	=>$idWebuser
	));
}

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
			$allfilename="";
			// ajout mdp dans webuser
			if(isset($mdp))
			{
				recPwd($pdoUser, $oneMsg['id_mag']);
			}


		}
		else
		// fichier joint
		{
			// ajout mdp dans webuser
			if(isset($mdp))
			{
				recPwd($pdoUser, $oneMsg['id_mag']);
			}

			//------------------------------
			//			upload du fichier
			//------------------------------
			$uploadDir= '..\..\..\upload\mag\\';
			//on initialise authorized à 0, si il reste à 0, tous les fichiers sont autorisés, sinon
			//on incrémente et on bloque le message si on n'est pas égal à 0
			$authorized=0;
			//on stocke les extensions de fichiers interdits pour afficher message d'erreur
			$typeInterdit="";


			$nbFiles=count($_FILES['incfile']['name']);
			$totalFileSize=0;
			for ($f=0; $f <$nbFiles ; $f++){
				$filename=$_FILES['incfile']['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB
				$totalFileSize+=$_FILES['incfile']['size'][$f];

				if($_FILES['incfile']['size'][$f] > $maxFileSize){
					$err[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
				}
			}
			if($totalFileSize>$maxFileSize){
					$err[] = 'Attention le poids total des pièces jointes dépasse la taille autorisée de 5 Mo';

			}
			if(count($err)==0){
				for ($f=0; $f <$nbFiles ; $f++){
					$authorizedFile=isAllowed($_FILES['incfile']['tmp_name'][$f], $encoding=true);
					if($authorizedFile[0]=='interdit'){
						$authorized++;
						$typeInterdit.=$authorizedFile[1];
					}
				}
			}

			//au moins un fihcier n'est pas autorisé
			if($authorized!=0){
				array_push($err, "l'envoi de fichiers de type ". $typeInterdit ." est interdit, la réponse n'a pas pu être envoyée");
			}
			//  si tout va bien, on upload
			if($authorized==0 && count($err)==0){
				for ($f=0; $f <$nbFiles ; $f++){
					$filename=$_FILES['incfile']['name'][$f];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
				  // Get filename without extesion
					$filename_without_ext = basename($filename, '.'.$ext);
					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$uploaded=move_uploaded_file($_FILES['incfile']['tmp_name'][$f],$uploadDir.$filename );
					if($uploaded==false)
					{
						$errors[]="impossible de télécharger le fichier";
					}
					else{
						$filenameList[]=$filename;
					}
				}
				$allfilename= implode("; ", $filenameList);

			}













			// foreach ($_FILES as $fileDetails)
			// {
			// 	$authorizedFile=isAllowed($fileDetails['tmp_name'], $encoding=true);
			// 	//tableau de fichier :
			// 	if($authorizedFile[0]=='interdit')
			// 	{
			// 		$authorized++;
			// 		$typeInterdit.=$authorizedFile[1];

			// 	}
			// }
			// //tous les fichiers sont autorisés
			// if($authorized==0)
			// {
			// 	$hashedFileName=checkUploadNew($uploadDir, $pdoBt);
			// 	// conversion en string
			// 	$file= implode("; ", $hashedFileName);
			// 	//------------------------------
			// 	//			msg avec piece jointe
			// 	//			ajoute le msg dans db et
			// 	//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
			// 	//------------------------------
			// }
			// else
			// {
			// 	array_push($err, "l'envoi de fichiers de type ". $typeInterdit ." est interdit, la réponse n'a pas pu être envoyée");


			// }
		}
		//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			envoi mail au mag
		//------------------------------

		if(count($err)==0)
		{
			if(!recordReply($pdoBt,$idMsg,$allfilename)){
				array_push($err, "votre réponse n'a pas pu être enregistrée (err 01)");
			}
			else
			{
						//-----------------------------------------
						//				envoi du mail
						//-----------------------------------------
				if(!empty($_POST['mdp']))
				{
					$mail=sendMail($to,$objet,$tplpwd,$panoGalec['login'],$mdp,$link);
				}
				else
				{
					$mail=sendMail($to,$objet,$tpl,$objetdde,$vide,$link);
				}


				if($mail==1)
				{
					header('Location:'. ROOT_PATH.'/public/btlec/dashboard.php?success='.$mail);
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

		}


		//------------------------------------------
		//	ajout enreg ection dans stat
		//-----------------------------------------<
		if(count($err)==0)
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
//suppression le 22/08/2018 - inutile
// if (isset($_POST['close']))
// {
// 	$etat="cloturé par BTlec";
// 	if(!majEtat($pdoBt,$idMsg, $etat)){
// 		$err="impossible de clore le dossier";
// 		die;
// 	}
// }

// ajout du 22/08/2018 : btn pour cloturer les tickets sans envoyer de réponse ni de mail
// accès seulement au groupe admin
if(isset($_POST['closing'])){
	if(isset($_POST['close-no-msg']))
	{
		$etat="clos";
		if(!majEtat($pdoBt,$idMsg, $etat))
		{
			$err="impossible de clore le dossier";
			die;
		}
		else
		{

			header('Location:'. ROOT_PATH.'/public/btlec/dashboard.php?success=2');

		}
	}

}
include '../view/_head.php';
include '../view/_navbar.php';

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
	<div class='row' id='erreur'>
		<p class="red">
			<?php
			if(!empty($err)){

				foreach ($err as $error) {
					echo  $error ."</br>";
				}
			}
			?>
		</p>
	</div>

	<div class="row mag">
		<div class="col l12 reply">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Demande n° <?= $oneMsg['id']?> :</h4>
			<hr>
			<br><br><br>
			<div class="inside-mag">
				<h5>Magasin : <?= $magInfo['mag'] .' - ' .$magInfo['galec']  ?></h5>
				<p><span class="labelFor">Objet : </span><?=$oneMsg['objet'] ?></p>
				<p><span class="labelFor">Interlocuteur : </span><?=$oneMsg['who'] ?></p>
				<p><span class="labelFor">Date : </span><?= date('d-m-Y à H:i', strtotime($oneMsg['date_msg'])); ?></p>
				<p><span class="labelFor">Message : </span><br><?=$oneMsg['msg'] ?></p>

				<?php
				if(isAttached($oneMsg['inc_file']))
				{
					echo '<p class="pj"><span class="labelFor">Pièce(s) jointe(s)</span>'.isAttached($oneMsg['inc_file']) .'</p>';
				}

				?>
			</div>
		</div>
	</div>
	<p>&nbsp;</p>
	<?php foreach($replies as $reply): ?>
		<?php
	//reponse mag ou bt
		if($who=repliedByIntoName($pdoUser,$reply['replied_by']))
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
				<?php
				ob_start();
				?>
				<br>
				<div class="row">
					<div class='col l12'>
						<label for="mdp">Mot de passe du magasin :</label><br><br>
						<input class="browser-default" name="mdp" type="text" id="mdp">
					</div>
				</div>
				<?php
				// ajout champ mdp quand demande d'identifiants
				$identif=ob_get_contents();
				ob_end_clean();
				if($oneMsg['objet']=="demande d'identifiants")
				{
					echo $identif;
				}
				?>
				<br><br>
				<div id="file-upload">
					<p>Joindre un document à votre réponse:
						<br><i>(pour ajouter plusieurs fichiers, maintenez la touche ctrl pendant que vous sélectionnez les fichiers)</i>
					</p>
					<div class="col l12">
						<div id="upload-zone">

							<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="" >

							<div id="filelist"></div>
						</div>
					</div>
				</div>




				<div class="row">
					<div class='col l3'></div>
					<div class='col l3' id="wait">

					</div>

					<div class='col l3'>
						<p class="center">
							<input type="checkbox" class="filled-in" id="clos" checked="checked" name="clos" />
							<label for="clos">cloturer la demande</label>
						</p>
					</div>

					<div class='col l3'>
						<p class="center">
							<button class="btn" type="submit" name="post-reply" >Répondre</button>
						</p>
					</div>

				</div>
			</form>
		</div>
	</div>
</div>
<br><br>
<?php
ob_start();
?>
<div class="row mag">
	<div class="col l12 reply">
		<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Clôturer la demande sans envoyer de réponse :</h4>
		<hr>
		<br><br>
		<form action="answer.php?msg=<?=$idMsg ?>" method="post" >
			<div class="row">
				<div class='col l9'>
					<p>
						<input type="checkbox" class="filled-in"  checked="checked" name="close-no-msg" />
						<label for="close-no-msg">cloturer la demande</label>
					</p>
				</div>
				<div class='col l3'>
					<p class="center">
						<button class="btn" type="submit" name="closing">Cloturer</button>
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
<br><br>
<?php
$formCloture=ob_get_contents();
ob_end_clean();
$idUser=$_SESSION['id'];
if(isUserInGroup($pdoBt,$idUser,"admin"))
{
	echo $formCloture;
}




?>



<div class="row mag">
	<div class="col l12 reply">
		<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Réaffecter la demande :</h4>
		<hr>
		<br><br>
		<?php if ( $_SESSION['id_service']== 5 || $_SESSION['id_service']== 16 || $_SESSION['id_service'] == 6 ||  $oneMsg['id_service'] == $_SESSION['id_service']): ?>

			<p>La demande ne concerne pas votre service ? <a href="chg.php?msg=<?=$idMsg?>">Cliquez ici pour réaffecter la demande</a></p>

			<?php else: ?>
				<p>Cette demande ne concerne pas ou plus votre service, vous ne pouvez pas la réaffecter</p>
			<?php endif ?>


		</div>
	</div>



	<!-- affichage des messages d'erreur -->


</div>

<script type="text/javascript">
	$(document).ready(function(){

		$("#answer").submit(function( event )
		{
			$("#wait" ).append('<i class="fa fa-spinner" aria-hidden="true"></i>&nbsp;&nbsp;<span class="pl-3">Merci de patienter</span>')
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

		var fileName='';
		var fileList='';
		var fileSize="";
		var totalFileSize=0;
		var fileSizeReadable="";
		var warning="";
		var resume="";
		var warningTotal="";
		$('input[type="file"]').change(function(e){

			$('#filelist').empty();
			var nbFiles=e.target.files.length;
			for (var i = 0; i < nbFiles; i++)
			{
        		    // var fileName = e.target.files[0].name;
        		    //5120000 = 5Mo
        		    fileSizeReadable=getReadableFileSizeString(e.target.files[i].size);
        		    fileSize=e.target.files[i].size;
        		    totalFileSize+=e.target.files[i].size;
        		    if(fileSize>5120000){
        		    	warning="<span class='warning-msg'>attention ce fichier pèse plus de 5Mo ! vous ne pourrez pas envoyer votre réponse</span>";
        		    }
        		    fileName=e.target.files[i].name +" ( " +fileSizeReadable+ ") "+ warning +"<br>";
        		    fileList += fileName ;
        		}
        		if(totalFileSize>5120000){
        			resume="Poids total des fichiers : "+ getReadableFileSizeString(totalFileSize);
        			warningTotal="<br><span class='warning-msg'>Attention le poids total des fichiers excède 5Mo, votre réponse ne pourra pas être envoyée</span><br>";
        		}
     		   // console.log(fileList);
     		   titre='<p><span class="boldtxt">Fichier(s) : </span><br>'
     		   end='</p>';
     		   all=titre+fileList+warningTotal+resume+end;
     		   $('#filelist').append(all);
     		   fileList="";
     		});




	});


</script>
<?php
include('../view/_footer.php');
?>








