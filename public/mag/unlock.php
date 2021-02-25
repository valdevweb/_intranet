<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';
// require '../../functions/form.fn.php';

//----------------------------------------------------------------
require "../../functions/stats.fn.php";
require "../../Class/MsgManager.php";
$descr="page pour réouvrir une demande";
$page=basename(__file__);
$action="consultation";
$code=101;
addRecord($pdoStat,$page,$action, $descr,$code);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-mig.php');
include ('../view/_navbar.php');

//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------
function addReponse($pdoBt,$file)
{
	$req=$pdoBt->prepare("INSERT INTO replies(id_msg, reply,replied_by, date_reply, inc_file,type_demandeur,reopened,reopened_on) VALUES (:id_msg, :reply, :replied_by,:date_reply,:inc_file,:type_demandeur,:reopened,:reopened_on)");
	$req->execute(array(
		':id_msg'	=>$_GET['id_msg'],
		':reply'	=>$_POST['reply'],
		':replied_by' => $_SESSION['id'],
		':date_reply'	=>date('Y-m-d H:i:s'),
		':inc_file'	=>$file,
		':type_demandeur'	=> 'mag',
		':reopened'	=>1,
		':reopened_on'=>date('Y-m-d H:i:s')
	));
	return $pdoBt->lastInsertId();
}


function updateMsg($pdoBt)
{
	$update=$pdoBt->prepare('UPDATE msg SET etat= :etat  WHERE id= :id_msg');
	$result=$update->execute(array(
		':id_msg'		=> $_GET['id_msg'],
		':etat'			=>	'en cours'
	));
	return $result;
}


function formatPJ($incFileStrg){
	$href="";
	if(!empty($incFileStrg)){
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg=explode( '; ', $incFileStrg );
		for ($i=0;$i<count($incFileStrg);$i++){
			$ico="<i class='fa fa-paperclip fa-lg pl-5 pr-3 hvr-pop' aria-hidden='true'  ></i>";
			$href.= "<a class='pj' href='".URL_UPLOAD."mag/" . $incFileStrg[$i] . "' target='blank'>" .$ico ."ouvrir</a>";
		}
		$href="<p>".$href."</p>";

	}

	return $href;
}





$msgManager=new MsgManager();
$msg=$msgManager->getDemande($pdoBt,$_GET['id_msg']);
$replies=$msgManager->getListReplies($pdoBt, $_GET['id_msg']);

// include ('../view/_errors.php')


$errors=[];
$success=[];
$fileList="";
$uploadDir= DIR_UPLOAD.'mag\\';

if(isset($_POST['submit'])){

	// fichiers à uploader ??
	for($i=0;$i<count($_FILES['file']['name']) ;$i++){
		if($_FILES['file']['name'][$i]!=""){
			$filename=$_FILES['file']['name'][$i];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$filenameNoExt = basename($filename, '.'.$ext);
			$filenameNoExt=str_replace(" ","_",$filenameNoExt);
			$filenameNoExt=str_replace(";","",$filenameNoExt);
			$filenameNoExt=str_replace("'","",$filenameNoExt);

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

	//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
		//------------------------------

	if(count($errors)==0)
	{
		if($lastId=addReponse($pdoBt, $fileList))
		{
				//passe le mesg en statut en cours
			updateMsg($pdoBt);
				//créa du lien pour le mail  BT
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$_GET['id_msg']."'>ici pour voir la demande</a>";
				//------------------------------
				//			ajout enreg dans stat
				//------------------------------
			$descr="demande ".$_GET['id_msg'] ;
			$action="réouverture d'une demande";
			$code=209;
			addRecord($pdoStat,$page,$action, $descr,$code);

				//-----------------------------------------
				//				envoi des mails
				//-----------------------------------------
			$tplForBtlec="../mail/reopened_by_mag.tpl.html";
			mb_internal_encoding('UTF-8');
				// $service = mb_encode_mimeheader($infoMsg['full_name']);
			$objBt="PORTAIL BTLec - Service " .$msg['service'] ." - reouverture de la demande n° " . $_GET['id_msg'] ." par " .$msg['deno'];
			$objBt = mb_encode_mimeheader($objBt);

				// echo $objBt;

			$placeholderOne="NOMMAG";
			$placeholderTwo="NUMDDE";
			$placeholderThree="LINK";
			$placeholderFour="OBJETDDE";
			$contentOne=$_SESSION['nom'];
			$contentTwo=$_GET['id_msg'];
			$contentThree=$link;
			$contentFour=$msg['objet'];

			if(VERSION=="_"){
				$mailingList='valerie.montusclat@btlec.fr';
				$dest='valerie.montusclat@btlec.fr';

			}else{
				$mailingList= $msg['mailing'] ;
				$dest=$msg['email'];

			}


			if(sendMailVariablePlaceholder($mailingList,$objBt,$tplForBtlec,$placeholderOne,$contentOne, $placeholderTwo, $contentTwo, $placeholderThree, $contentThree, $placeholderFour, $contentFour)){
				$success[]="Email envoyé avec succès";
				$tplForMag="../mail/ar_mag_reopened.tpl.html";
				mb_internal_encoding('UTF-8');
				$objMag="PORTAIL BTLec - demande de réouverture de dossier";
				$objMag = mb_encode_mimeheader($objMag);
				$phOne="IDMSG";
				$phTwo="SERVICE";
				$ctOne=$_GET['id_msg'];
				$ctTwo=$msg['service'];

				sendMailVariablePlaceholder($dest,$objMag,$tplForMag,$phOne,$ctOne, $phTwo, $ctTwo);
			}
			else{
				$errors[]="Echec d'envoi d'email";
			}
		}else{
			$errors[]="Echec : votre demande n'a pas pu être enregistrée";
		}
	}
}












//contenu
include('unlock.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');