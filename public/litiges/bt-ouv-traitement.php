<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

//------------------------------------------------------
//			MEMO
//------------------------------------------------------
/*
etat :
0 = attente
1-= ok
2=refusé
 */
unset($_SESSION['goto']);

require_once  '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisOuverture($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT ouv.id, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg, pj, mag, btlec, ouv.galec FROM ouv LEFT JOIN btlec.sca3 ON ouv.galec=btlec.sca3.galec WHERE ouv.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getRep($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id, id_web_user, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg,pj, mag FROM ouv_rep WHERE id_ouv= :id ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getBtName($pdoBt, $idwu)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as fullname FROM btlec WHERE id_webuser= :id_webuser");
	$req->execute(array(
		':id_webuser'	=>$idwu
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-grey"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}


function addMsg($pdoLitige, $filelist)
{
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO ouv_rep (id_ouv,id_web_user,date_saisie,msg,pj) VALUES (:id_ouv,:id_web_user,:date_saisie,:msg,:pj)");
	$req->execute(array(
		':id_ouv'		=>$_GET['id'],
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_saisie'		=>date('Y-m-d H:i:s'),
		':msg'				=> $msg,
		':pj'				=>$filelist,
	));
	return $pdoLitige->lastInsertId();
	// return $req->errorInfo();
}

function updateStatut($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE ouv SET etat= :etat WHERE id= :id");
	$req->execute(array(
		':etat'	=>$_POST['action'],
		':id'	=>$_GET['id'],
	));
	return $req->rowCount();
}



function getInfoMag($pdoBt, $galec)
{
	$req=$pdoBt->prepare("SELECT btlec FROM sca3 WHERE galec = :galec");
	$req->execute(array(
		':galec'		=>$galec,
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$uploadDir= '..\..\..\upload\litiges\\';

$thisOuv=getThisOuverture($pdoLitige);
$theseRep=getRep($pdoLitige);


if(isset($_POST['submit']))
{
	if(empty($_FILES['form_file']['name'][0]))
	{
		$filelist="";
	}
	else
	{
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
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$filename_without_ext = basename($filename, '.'.$ext);
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;

				}

				if(!move_uploaded_file($_FILES['form_file']['tmp_name'][$f],$uploadDir.$filename ))
				{

					$errors[]="impossible d'enregistrer votre fichier";
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
			$lastInsertId=addMsg($pdoLitige, $filelist);


			if($lastInsertId<=0)
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}
		if(count($errors)==0)
		{
			// ---------------------------------------
			// si case cochée = refus ou  validation mise à jour de l'état dans ouverture

			if($_POST['action'] !="autre")
			{
				$majStatut=updateStatut($pdoLitige);
				if($majStatut!=1)
				{
					$errors[]="Impossible de mettre à jour le statut du dossier";
				}
			}
		}
		if(count($errors)==0)
		{

			if(VERSION =='_')
			{
				$mailMag=array('valerie.montusclat@btlec.fr');
			}
			else
			{
				$btlec=getInfoMag($pdoBt,$thisOuv);
				if($_SESSION['code_bt']!='4201')
				{
					// $mailMag=array($btlec['btlec'].'-rbt@btlec.fr');
					$mailMag=array($thisOuv['btlec'].'-rbt@btlec.fr');
				}
				else
				{
					$mailMag=array('valerie.montusclat@btlec.fr');
				}
			}
			$msg=strip_tags($_POST['msg']);
			$msg=nl2br($msg);
			$btTemplate = file_get_contents('mail-mag-suivi-ouverture.php');
			$btTemplate=str_replace('{MSG}',$msg,$btTemplate);
			$subject='Portail BTLec Est  - votre demande d\'ouverture de dossier litige' ;
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($btTemplate, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			->setTo($mailMag)
			->setBcc(['valerie.montusclat@btlec.fr', 'nathalie.pazik@btlec.fr', 'jonathan.domange@btlec.fr']);
			$delivered=$mailer->send($message);
			if($delivered >0)
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&success=ok';
				header($loc);
			}
			else
			{
				$errors[]='Le mail n\'a pas pu être envoyé à notre service livraison';
			}
		}


	}

	if(!empty($thisOuv['pj']))
	{
		$pjtemp=createFileLink($thisOuv['pj']);
		$pj='Pièce jointe : <span class="pr-3">'.$pjtemp .'</span>';



	}else{
		$pj='';

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
			<p class="text-right"><a href="bt-ouvertures.php" class="btn btn-primary">Retour</a></p>
		</div>
	</div>

	<h1 class="text-main-blue pb-5 ">Traitement de la demande n° <?= $_GET['id'] ?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Rappel de la demande initiale: </h5>
		</div>
	</div>
	<div class="row">
		<div class="col alert alert-primary">
			<div class="row">
				<div class="col">
					<?= $thisOuv['btlec'].'-'.$thisOuv['mag']?>
				</div>
				<div class="col text-right">
					date de la demande : <?= $thisOuv['datesaisie']?>
				</div>
			</div>
			<div class="row">
				<div class="col border-top-blue">
					<?= $thisOuv['msg']?>
				</div>
			</div>
			<div class="row pt-3">
				<div class="col">
					<?=$pj?>
				</div>
			</div>
		</div>
	</div>

	<?php
// si échange de msg
	if(!empty($theseRep))
	{
		echo '<div class="bg-separation"></div>';
		echo '<div class="row py-3">';
		echo '<div class="col">';
		echo '<h5 class="khand text-main-blue">Echanges avec le magasin : </h5>';
		echo '</div></div>';
		foreach ($theseRep as $rep)
		{
			$pj='';
			if($rep['mag']==0)
			{
				$alertColor='alert-warning';
				$from=getBtName($pdoBt, $rep['id_web_user']);
				$from=$from['fullname'];
			}
			else
			{
				$alertColor='alert-primary';
				$from=$thisOuv['mag'];
			}
			if(!empty($rep['pj']))
			{
				$pjtemp=createFileLink($rep['pj']);
				$pj='<br>Pièce jointe : '. $pjtemp ;
			}
			echo '<div class="row">';
			echo '<div class="col alert '.$alertColor.'">';
			echo $rep['msg'];
			echo $pj;
			echo '<br><br>';
			echo '<i class="fas fa-user-circle pr-3"></i>' .$from .' - le ' .$rep['datesaisie'];
			echo '</div>';
			echo '</div>';
		}
	}
	?>
	<div class="bg-separation"></div>
	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Traitement / envoi de messages : </h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row bg-alert bg-yellow-light mb-5">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'] ?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col heavy">
								Action :

							</div>
						</div>
						<div class="row py-3">
							<div class="col">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio1" value="2">
									<label class="form-check-label" for="inlineRadio1">Refuser le dossier</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio2" value="1">
									<label class="form-check-label" for="inlineRadio2">Accepter le dossier</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio3" value="autre">
									<label class="form-check-label" for="inlineRadio3">Demander un complément d'information</label>
								</div>

							</div>
						</div>



						<div class="row pt-3">
							<div class="col">
								<div class="form-group">
									<label for="action" class="heavy pb-3">Message : </label>
									<textarea type="text" class="form-control" row="6" name="msg" id="msg" required></textarea>
								</div>

							</div>
						</div>
						<div class="row align-items-end">
							<div class="col">
								<div id="file-upload">
									<fieldset>
										<p class="heavy pt-2">Pièces jointes :</p>
										<div class="form-group">
											<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
										</div>
									</fieldset>
								</div>
								<div id="filelist"></div>
							</div>
							<div class="col-auto">
								<p class="text-right "><button type="submit" id="submit" class="btn btn-black" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button></p>
							</div>
						</div>

					</form>
				</div>
			</div>

		</div>

	</div>



	<!-- ./container -->
</div>

<script type="text/javascript">

	$(document).ready(function (){

		var textarea="";
		$('input[type=radio][name=action]').change(function() {
			if (this.value == '1') {
				textarea="Bonjour, \nNous vous informons que nous allons ouvrir un dossier litige dont le numéro vous sera communiqué très prochainement";
			}
			else if (this.value == '2') {
				textarea="Bonjour, \nNous clôturons votre dossier sans suite car ";
			}
			else if (this.value == 'autre') {
				textarea="Bonjour,";
			}
			$('#msg').val(textarea);


		});
	});





</script>

<?php
require '../view/_footer-bt.php';
?>