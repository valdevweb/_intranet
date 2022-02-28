<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once  '../../vendor/autoload.php';
require_once  '../../Class/UserHelpers.php';
require_once  '../../Class/mag/MagHelpers.php';
require('../../Class/litiges/LitigeDialDao.php');


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dossiers WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$infoLitige=getLitige($pdoLitige);



function getPreTxt($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dial_help ORDER BY nom,pretxt");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$allPreTxt=getPreTxt($pdoLitige);


function addMsg($pdoLitige, $filelist)
{
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO dial(id_dossier,date_saisie,msg,id_web_user,filename,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:filename,:mag)");
	$req->execute(array(
		':id_dossier'		=>$_GET['id'],
		':date_saisie'		=>date('Y-m-d H:i:s'),
		':msg'				=>$msg,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':filename'		=>	$filelist,
		':mag'		=>	0,

	));
	return $req->rowCount();
	// return $req->errorInfo();
}



function getFirstDialog($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id_dossier,DATE_FORMAT(date_saisie, '%d-%m-%Y') as dateFr,msg,id_web_user,filename,mag FROM dial WHERE id_dossier= :id AND mag=3 ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}
$firstDial=getFirstDialog($pdoLitige);
require 'echanges.fn.php';

$errors=[];
$success=[];
$dialDao=new LitigeDialDao($pdoLitige);


$defaultTxt='Bonjour,&#13;&#10;&#13;&#10;&#13;&#10;Cordialement,&#13;&#10;'.$_SESSION['nom'];
$uploadDir= DIR_UPLOAD. 'litiges\\';

$btlec=MagHelpers::btlec($pdoMag,$infoLitige['galec']);



if(isset($_POST['submit']) ||isset($_POST['submit_mail']))
{
	if(empty($_FILES['form_file']['name'][0])){
	// pas de fichier
		$filelist="";
	}
	else
	{
	//présence de fichier
		$filelist="";
		$nbFiles=count($_FILES['form_file']['name']);
		for ($f=0; $f <$nbFiles ; $f++)
		{
			$filename=$_FILES['form_file']['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB

				if($_FILES['form_file']['size'][$f] > $maxFileSize)
				{
					$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
				}
				else
				{
					// cryptage nom fichier
			 		// Get the fileextension
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
    				  // Get filename without extesion
					$filename_without_ext = basename($filename, '.'.$ext);
  					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$uploaded=move_uploaded_file($_FILES['form_file']['tmp_name'][$f],$uploadDir.$filename );
				}
				if($uploaded==false)
				{
					$errors[]="impossible de télécharger le fichier";
				}
				else
				{
					$filelist.=$filename .';';
				}
			}
		}
		// fin présence fichier

		if(count($errors)==0)
		{
			$newMsg=addMsg($pdoLitige, $filelist);
			if($newMsg !=1)
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}

		if(count($errors)==0)
		{
			if(isset($_POST['submit_mail']))
			{
				if(VERSION =='_'){
					$mailMag=array('valerie.montusclat@btlec.fr');
				}else{
					$mailMag=array($btlec.'-rbt@btlec.fr');
				}

				$magTemplate = file_get_contents('mail/mail-mag-msgbt.php');
				$magTemplate=str_replace('{DOSSIER}',$infoLitige['dossier'],$magTemplate);
				$subject='Portail BTLec Est  - nouveau message sur le dossier litige ' . $infoLitige['dossier'];
			// ---------------------------------------
				$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
				$mailer = new Swift_Mailer($transport);
				$message = (new Swift_Message($subject))
				->setBody($magTemplate, 'text/html')
				->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
				->setTo($mailMag);

				$delivered=$mailer->send($message);
				if($delivered >0)
				{
					$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&success=ok';
					header($loc);
				}
				else
				{
					$errors[]='Le mail n\'a pas pu être envoyé';
				}
			}
			else
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&success=okenreg';
				header($loc);
			}


		}

	}
	if(isset($_POST['not_read'])){
		if (UserHelpers::isUserAllowed($pdoUser,['94']) || $_SESSION['id_web_user']==1402){
			$dialDao->updateRead($_POST['id_dial'],0);
			header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_dial']);
		}else{
			$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}
	if(isset($_POST['read'])){
		if (UserHelpers::isUserAllowed($pdoUser,['94']) || $_SESSION['id_web_user']==1402){
			$dialDao->updateRead($_POST['id_dial'],1);
			header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_dial']);
		}else{
			$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
		}
	}



	if(isset($_GET['success']) && $_GET['success']=='ok'){
		$success[]="message envoyé avec succés";
	}elseif(isset($_GET['success']) && $_GET['success']=='okenreg'){
		$success[]="votre message a été enregistré sans envoi";
	}

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
	include('../view/_head-bt.php');
	include('../view/_navbar.php');
	?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div class="row py-3">
		<div class="col">
			<p class="text-right"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>
		</div>
	</div>
	<h1 class="text-main-blue pb-5 ">Dossier N° <?= $infoLitige['dossier']?></h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<?php
	if(!empty($firstDial))
	{
		echo '<div class="row alert bg-kaki-light mb-5">';
		echo '<div class="col">';

		echo '<div class="row ">';
		echo '<div class="col heavy">Commentaire d\'origine :';
		echo '</div>';
		echo '<div class="col">';
		echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>'.$firstDial['dateFr'] .'</div>';
		echo '</div>';
		echo '</div>';

		echo '<div class="row ">';
		echo '<div class="col">';
		echo $firstDial['msg'];
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';

	}



	?>


	<div class="row">
		<div class="col">
			<h2 class="khand text-main-blue">Echanges avec le magasin</h2>
		</div>
	</div>
	<div class="row  mb-5">
		<div class="col">
			<?php
			if(isset($dials) && count($dials)>0)
			{
				include 'echanges.php';
			}
			?>
		</div>
	</div>






	<div class="row">
		<div class="col">
			<h2 class="khand text-main-blue">Envoyer un nouveau message</h2>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col bg-kaki-light rounded p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
						<p class="heavy">Réponses préparées :</p>
						<div class="form-group">
							<select name="pretxt" id="pretxt" class="form-control dropdown">
								<option value="">Sélectionnez une réponse préparée</option>
								<?php foreach ($allPreTxt as $key => $pretxt): ?>
									<option class="font-weight-bold" value="<?=$pretxt['id']?>" data-title="<?=$pretxt['id']?>"><?=$pretxt['nom']?></option>
									<option class="text-italic pl-3" value="<?=$pretxt['id']?>" data-sentence="<?=$pretxt['id']?>"><?=$pretxt['pretxt']?></option>

								<?php endforeach ?>

							</select>

						</div>

						<div class="form-group">
							<label for="action" class="heavy">Votre message :</label>
							<textarea type="text" class="form-control" row="6" name="msg" placeholder="Message" id="msg" required><?=$defaultTxt?></textarea>
						</div>
						<div id="file-upload">
							<fieldset>
								<p class="heavy pt-2">Pièces jointes :</p>
								<div class="form-group">
									<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
								</div>
							</fieldset>
						</div>
						<div id="filelist"></div>

						<div class="text-right"><button type="submit" id="submit_t" class="btn btn-kaki" name="submit"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							<button type="submit" id="submit_mail" class="btn btn-red" name="submit_mail"><i class="fas fa-envelope pr-3"></i>Envoyer</button></div>

						</form>
					</div>
				</div>
			</div>
		</div>


		<!-- RETOUR -->
		<div class="row my-5">
			<div class="col-lg-1 col-xxl-2"></div>
			<div class="col">


			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>


	</div>
	<script type="text/javascript">

		$(document).ready(function (){

			$('#pretxt').on('change',function(){
				var attr=$(this).find(':selected').attr('data-title');
				if(attr){
					var nextOption=$('option[data-sentence="'+attr+'"]');
					var pretxt=nextOption.text();
				}else{
					var pretxt=$('#pretxt option:selected').text();
				}

				var bjr="Bonjour,\n\n";
				var cdlt="\n\n"+"Cordialement,\n";
				var name='<?php echo $_SESSION['nom'];?>';
 						// console.log(name);
 						$('#msg').val(bjr + pretxt + cdlt + name);
 					});
			var fileName='';
			var fileList='';
			$('input[type="file"]').change(function(e){
				var nbFiles=e.target.files.length;
				for (var i = 0; i < nbFiles; i++)
				{
					fileName=e.target.files[i].name;
					fileList += fileName + ' - ';
				}
				titre='<p><span class="heavy">Fichier(s) : </span>';
				end='</p>';
				all=titre+fileList+end;
				$('#filelist').append(all);
				fileList="";
			});


		});

	</script>





	<?php

	require '../view/_footer-bt.php';

	?>