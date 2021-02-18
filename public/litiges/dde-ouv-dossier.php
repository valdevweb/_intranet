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


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr="saisie déclaration mag hors qlik" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 208);

require_once  '../../vendor/autoload.php';

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function addMsg($pdoLitige,$pj)
{
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);

	$req=$pdoLitige->prepare("INSERT INTO ouv (id_web_user, galec, msg, date_saisie, pj) VALUES (:id_web_user, :galec, :msg, :date_saisie, :pj)");
	$req->execute(array(
		':id_web_user'	=>$_SESSION['id_web_user'],
		 ':galec'		=>$_SESSION['id_galec'],
		 ':msg'			=>$msg,
		 ':date_saisie'	=>date('Y-m-d H:i:s'),
		 ':pj'			=>$pj
	));
	return $req->rowCount();
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$uploadDir= DIR_UPLOAD. 'litiges\\';


//------------------------------------------------------
//			TRAITEMNT
//------------------------------------------------------

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
					$uploaded=move_uploaded_file($_FILES['form_file']['tmp_name'][$f],$uploadDir.$filename );
				}
				if($uploaded==false)
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
			$newMsg=addMsg($pdoLitige, $filelist);
			if($newMsg!=1)
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}
		if(count($errors)==0)
		{
			// ---------------------------------------
			if(VERSION =='_'){
				$mailBt=array('valerie.montusclat@btlec.fr');
			}
			else{
				if($_SESSION['code_bt']!='4201'){
					$mailBt=array('btlecest.portailweb.litiges@btlec.fr');
				}else{
					$mailBt=array('valerie.montusclat@btlec.fr');
				}
			}
			$msg=strip_tags($_POST['msg']);
			$msg=nl2br($msg);
			$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-ouvertures.php">Voir sa demande sur le portail</a>';

			$btTemplate = file_get_contents('mail/mail-bt-dde-ouverture.php');
			$btTemplate=str_replace('{MAG}',$_SESSION['nom'],$btTemplate);
			$btTemplate=str_replace('{BTLEC}',$_SESSION['code_bt'],$btTemplate);
			$btTemplate=str_replace('{MSG}',$msg,$btTemplate);
			$btTemplate=str_replace('{LINK}',$link,$btTemplate);
			$subject='Portail BTLec Est  - demande d\'ouverture de dossier litige - ' . $_SESSION['nom'].'-'. $_SESSION['code_bt'];
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($btTemplate, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			// ->setTo(array('valerie.montusclat@btlec.fr'))
			->setTo($mailBt);
			$delivered=$mailer->send($message);
			if($delivered >0)
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
				header($loc);
			}
			else
			{
				$errors[]='Le mail n\'a pas pu être envoyé à notre service livraison';
			}
		}

	}


	if(isset($_GET['success']))
	{
		$success[]="Votre demande a été envoyée au service litige livraison qui reviendra vers vous dès que possible";
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
	<h1 class="text-main-blue py-5 ">Déclarer un litige</h1>
	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<!-- start row -->
	<div class="row no-gutters">
		<div class="col-lg-1"></div>
		<div class="col">
			<div class="alert alert-info">Merci de nous fournir un maximum d'informations (numéro de palette, EAN, quantités, etc) afin que nous puissions ouvrir un dossier<br>Dans le cas contraire, nous pourrions être amenés à refuser votre demande.
			</div>
		</div>
		<div class="col-lg-1"></div>

	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<div class="row bg-alert-primary rounded mb-5">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="action" class="heavy">Informations :</label>
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
								<p class="text-right "><button type="submit" id="submit" class="btn btn-primary" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button></p>
							</div>
						</div>

					</form>
				</div>
			</div>

		</div>
		<div class="col-lg-1"></div>

	</div>





</div>

<?php

require '../view/_footer-bt.php';

?>