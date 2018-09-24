<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';

//----------------------------------------------------------------
require "../../functions/stats.fn.php";
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
function reopen($pdoBt,$file)
{
	$req=$pdoBt->prepare("INSERT INTO replies(id_msg, reply,replied_by, date_reply,inc_file,type_demandeur,reopened,reopened_on) VALUES (:id_msg, :reply, :replied_by,:date_reply,:inc_file,:_demandeur,:reopened,:reopened_on)");
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

function getMsgAndServiceDetails($pdoBt)
{
	//
	$req=$pdoBt->prepare("SELECT msg.id as id_msg, objet, msg, id_mag,id_service, date_msg, who, email, id_galec,code_bt,services.id as id_service_from_service,services.full_name,mailing FROM `msg` LEFT JOIN services ON msg.id_service= services.id WHERE msg.id= :id_msg");
	$req->execute(array(
		':id_msg'	=>$_GET['id_msg']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



// include ('../view/_errors.php')

if(isset($_GET['id_msg']))
{
	// $req=$pdoBt->prepare("SELECT * FROM msg WHERE id= :id_msg");
	// $req->execute(array(
	// 	':id_msg'	=>$_GET['id_msg']
	// ));
	$infoMsg=getMsgAndServiceDetails($pdoBt);
		// echo "<pre>";
		// var_dump($infoMsg);
		// echo '</pre>';

}

if(isset($_POST['submit']))
{

	$errors=[];
	$success=[];
	$isFileToUpload=isFileToUpload();

	// fichiers à uploader ??
	if (!$isFileToUpload)
		{
			//pas de pièce jointe
			$file="";
		}
		else
		//avec pièce jointe
		{
			echo "fichier";

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
				for($i=0;$i<sizeof($authorizedFile);$i++)
				{
					if($authorizedFile[$i]=='interdit')
					{
						$typeInterdit.=$authorizedFile[1]. ' - ' ;
						//incrémente le nb de fichiers interdits
						$authorized++;

					}

								# code...
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
				array_push($errors, "l'envoi de fichiers de type : ". $typeInterdit ." est interdit");

			}
	}
	//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
		//------------------------------

		if(isset($errors) && count($errors)==0)
		{
			if($lastId=reopen($pdoBt, $file))
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
				$objBt="PORTAIL BTLec - Service " .$infoMsg['full_name'] ." - reouverture de la demande n° " . $_GET['id_msg'] ." par " .$_SESSION['nom'];
				$objBt = mb_encode_mimeheader($objBt);

				// echo $objBt;

				$placeholderOne="NOMMAG";
				$placeholderTwo="NUMDDE";
				$placeholderThree="LINK";
				$placeholderFour="OBJETDDE";
				$contentOne=$_SESSION['nom'];
				$contentTwo=$_GET['id_msg'];
				$contentThree=$link;
				$contentFour=$infoMsg['objet'];
				$mailingList=$infoMsg['email'];
				// if(sendMail($mailingList,$objBt,$tplForBtlec,$name,$magName, $link))
				if(sendMailVariablePlaceholder($mailingList,$objBt,$tplForBtlec,$placeholderOne,$contentOne, $placeholderTwo, $contentTwo, $placeholderThree, $contentThree, $placeholderFour, $contentFour))

				{
					$success[]="Email envoyé avec succès";
					$tplForMag="../mail/ar_mag_reopened.tpl.html";
					mb_internal_encoding('UTF-8');
					$objMag="PORTAIL BTLec - demande de réouverture de dossier";
					$objMag = mb_encode_mimeheader($objMag);
					$phOne="IDMSG";
					$phTwo="SERVICE";
					$ctOne=$_GET['id_msg'];
					$ctTwo=$infoMsg['full_name'];
					sendMailVariablePlaceholder($infoMsg['email'],$objMag,$tplForMag,$phOne,$ctOne, $phTwo, $ctTwo);
				}
				else
				{
					$errors[]="Echec d'envoi d'email";
				}
			}
			else
			//erreur insertion en db
			{
				$errors[]="Echec : votre demande n'a pas pu être enregistrée";
			}
		}
}












//contenu
include('unlock.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');