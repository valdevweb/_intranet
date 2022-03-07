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
$descr="contact mag hors qlik" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 208);



//------------------------------------------------------
//			MEMO
//------------------------------------------------------
/*
etat :
0 = attente
1-= ok
2=refusé
 */

require_once  '../../vendor/autoload.php';
require('echanges.fn.php');
require_once  '../../Class/UserHelpers.php';
require "../../Class/litiges/LitigeDao.php";




function addMsg($pdoLitige, $filelist,$magRep){
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO ouv_rep (id_ouv,id_web_user,date_saisie,msg,pj, mag) VALUES (:id_ouv,:id_web_user,:date_saisie,:msg,:pj, :mag)");
	$req->execute(array(
		':id_ouv'		=>$_GET['id'],
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_saisie'		=>date('Y-m-d H:i:s'),
		':msg'				=> $msg,
		':pj'				=>$filelist,
		':mag'				=>$magRep
	));
	return $pdoLitige->lastInsertId();
	// return $req->errorInfo();
}






//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


$uploadDir= DIR_UPLOAD. 'litiges\\';

$litigeDao=new LitigeDao($pdoLitige);
$thisOuv=$litigeDao->getThisOuverture($_GET['id']);
$theseRep=$litigeDao->getOuvertureMsg($_GET['id']);


if(isset($_POST['submit']))
{
	if(empty($_POST['msg'])){
		$errors[]="Vous devez saisir un message";
		exit;
	}
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
			$lastInsertId=addMsg($pdoLitige, $filelist,1);


			if($lastInsertId<=0)
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}
		if(count($errors)==0)
		{

			if(VERSION =='_')
			{
				$mailBt=array(MYMAIL);
			}
			else
			{
				$mailBt=array(EMAIL_LITIGES);
			}
			$msg=strip_tags($_POST['msg']);
			$msg=nl2br($msg);
			$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-ouv-traitement.php?id='.$_GET['id'].'"> cliquant ici</a>';
			$btTemplate = file_get_contents('mail/mail-bt-suivi-ouverture.php');
			$btTemplate=str_replace('{MSG}',$msg,$btTemplate);
			$btTemplate=str_replace('{LINK}',$link,$btTemplate);
			$subject='Portail BTLec Est  - saisie libre - réponse du magasin ' .$_SESSION['nom'] ;
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($btTemplate, 'text/html')
			->setFrom(EMAIL_NEPASREPONDRE)
			->setTo($mailBt);

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
			<p class="text-right"><a href="mag-litige-listing.php" class="btn btn-primary">Retour</a></p>
		</div>
	</div>


<?php

include('ouv-echanges.php');


 ?>


	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Traitement / envoi de messages : </h5>
		</div>
	</div>


<?php

ob_start();
 ?>

	<div class="row">
		<div class="col">
			<div class="row bg-alert bg-yellow-light mb-5">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'] ?>" method="post" enctype="multipart/form-data">
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

<?php

$formRep=ob_get_contents();
ob_get_clean();
// si dossier non réfusé
if($thisOuv['etat']!=2)
{
echo $formRep;
}
else{
echo '<div class="row">';
	echo '<div class="col">';
		echo '<div class="row">';
			echo '<div class="col alert alert-danger">Votre demande a été réfusée, vous ne pouvez plus envoyer de message sur ce dossier</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';
}

 ?>




	<!-- ./container -->
</div>


<?php
require '../view/_footer-bt.php';
?>