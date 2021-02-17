<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//----------------------------------------------------------------
require '../../vendor/autoload.php';

require '../../Class/BtUserManager.php';

require '../../functions/mail.fn.php';
require "../../functions/stats.fn.php";




//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

function addMsg($db,$id_service,$inc_file){
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$db->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat,inc_file,who,email, id_galec,code_bt,centrale)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :inc_file, :who, :email, :id_galec, :code_bt, :centrale)');
	$req->execute(array(
		':objet'		=> strip_tags($_POST['objet']),
		':msg'			=> $msg,
		':id_mag'		=> strip_tags($_SESSION['id']),
		':id_service'	=> $id_service,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "en attente de réponse",
		':inc_file'		=>$inc_file,
		':who'			=>strip_tags($_POST['name']),
		':email'		=>strip_tags($_POST['email']),
		':id_galec'		=>$_SESSION['id_galec'],
		':code_bt'		=>$_SESSION['code_bt'],
		':centrale'		=>$_SESSION['centrale']
	));

	return $db->lastInsertId();
}




//----------------------------------------------------------------
//			affichage : infos du services
//----------------------------------------------------------------
$userManager=new BtUserManager();
$service=$userManager->getService($pdoUser,$_GET['id']);
$serviceMembers=$userManager->getListUserService($pdoBt,$_GET['id']);



$uploadDir= DIR_UPLOAD. 'mag\\';

$errors=[];
$success=[];
$fileList="";
//soumission du formulaire
if(isset($_POST['post-msg'])){
	if(empty($_POST['objet']) || empty($_POST['msg']) || empty($_POST['name']) || empty($_POST['email'])){
		$errors[]= "merci de remplir tous les champs";
	}

	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[]='Veuillez indiquez une adresse email valide';
	}
	//formulaire conforme
	if(empty($errors)){
		for($i=0;$i<count($_FILES['file']['name']) ;$i++){
			if($_FILES['file']['name'][$i]!=""){
				$filename=$_FILES['file']['name'][$i];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($filename, '.'.$ext);
				$filenameNoExt=str_replace(" ","_",$filenameNoExt);
				$filenameNoExt=str_replace(";","",$filenameNoExt);

				$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;
				if($fileList==""){
					$fileList= $filenameNew;

				}else{
					$fileList= $fileList.'; '.$filenameNew;
				}
				$uploaded=move_uploaded_file($_FILES['file']['tmp_name'][$i],$uploadDir.$filenameNew );
				if($uploaded==false){
					$errors[]="Impossible d'ajouter la pièce jointe";
				}

			}
		}
	}
	if(empty($errors)){
		$lastId=addMsg($pdoBt,$_GET['id'], $fileList);
		if($lastId>0){

				//créa du lien pour le mail  BT
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$lastId."'>ici pour consulter le message</a>";
			$linkMag="Cliquez <a href='".SITE_ADDRESS."/index.php?mag/edit-msg.php?msg=".$lastId."'>ici pour revoir votre demande</a>";
			if(VERSION=="_"){
				$dest=["valerie.montusclat@btlec.fr"];
			}else{
				$dest=[$service['mailing']];
			}

			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);

			$htmlMail = file_get_contents('../mail/new_mag_msg.tpl.html');
			$htmlMail=str_replace('{DEMANDEUR}',$_POST['name'],$htmlMail);
			$htmlMail=str_replace('{MAGASIN}',$_SESSION['nom'],$htmlMail);
			$htmlMail=str_replace('{LINK}',$link,$htmlMail);
			$subject="PORTAIL BTLec - nouvelle demande : " .$_SESSION['nom'] ." pour le service " . $service['service'];
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'PORTAIL BTLec'))
			->setTo($dest);

			if (!$mailer->send($message, $failures)){
				$errors[]='impossible d\'envoyer le mail à BTlec';
				echo "erreur";

			}else{
				$success[]="mail envoyé avec succés";
			}

			$htmlMail = file_get_contents('../mail/ar_mag.tpl.html');
			$htmlMail=str_replace('{SERVICE}',$service['service'],$htmlMail);
			$htmlMail=str_replace('{LINK}',$linkMag,$htmlMail);
			$subject="PORTAIL BTLec - demande envoyée";
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'PORTAIL BTLec'))
			->setTo(array($_POST['email']));

			if (!$mailer->send($message, $failures)){
				$errors[]='impossible d\'envoyer le mail au magasin';
			}else{
				$success[]="mail envoyé avec succés";
			}

			if(empty($errors)){
				unset($_POST);
				header('Location:'. ROOT_PATH. '/public/mag/histo-mag.php');
			}

		}
		else{
			$errors[]="Echec : votre demande n'a pas pu être enregistrée";
		}

	}
}

















//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');

