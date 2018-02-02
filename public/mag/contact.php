<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require '../../functions/form.fn.php';
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';
require "../../functions/stats.fn.php";


//recup slug service
$gt=$_GET['gt'];
//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

//----------------------------------------------------------------
//			stats
//----------------------------------------------------------------

$descr="page demande mag au service ".$gt ;
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);


//----------------------------------------------------------------
//			affichage : infos du services
//----------------------------------------------------------------

$gtInfos=infoService($pdoBt);
foreach($gtInfos as $data)
{
	$full_name= $data['full_name'];
	$descr= $data['description'];
 	//numero du service
	$idGt=$data['id'];
	$mailingList=$data['mailing'];
}

$serviceName=getNames($pdoBt, $idGt);
$nbName=sizeof($serviceName);


// templates et sujet des mails envoyés à la soumission du formulaire
// un mail informer le service qu'une demande à été posté
// un mail au mag pour lui confirmer que sa demande a été envoyé
$tplForBtlec="../mail/new_mag_msg.tpl.html";
$tplForMag="../mail/ar_mag.tpl.html";
$objBt="PORTAIL BTLec - nouvelle demande : " .$_SESSION['nom'] ." pour le service " . $full_name;
$objMag="PORTAIL BTLec - demande envoyée";
mb_internal_encoding('UTF-8');
$objMag = mb_encode_mimeheader($objMag);

$magName=$_SESSION['nom'];


//test valeur $_FILE, si renvoi true => au moins un fichier à uploader
$isFileToUpload=isFileToUpload();

//----------------------------------------------------------------
//			traitement formulaire : ajout à db et upload si fichier
//----------------------------------------------------------------
//initialisation des tableau de message d'erreur de succès
$err=array();
$success=array();

//soumission du formulaire
if(!empty($_POST))
{
	extract($_POST);
	//------------------------------------------------
	//				tests prétraitement
	//-----------------------------------------------
	// en dehors du file aucun champ ne doit être vide
	if(empty($objet) || empty($msg) || empty($name) || empty($email))
	{
		array_push($err, "merci de remplir tous les champs");
	}
	//si adresse mail non conforme
	elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
				array_push($err, 'Indiquez un email valide');
	}
	//formulaire conforme
	else
	{
		if (!$isFileToUpload)
		{
			//pas de pièce jointe
			$file="";
		}
		else
		//avec pièce jointe
		{
			//------------------------------
			//			upload du fichier
			//------------------------------
			$uploadDir= '..\..\..\upload\mag\\';
			//$newFileArray=formatArray($upload);

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
		//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
		//------------------------------
		if($lastId=addMsg($pdoBt,$idGt, $file))
		{
			//créa du lien pour le mail
			$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour consulter le message</a>";
			$linkMag="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour revoir votre demande</a>";
			//------------------------------
			//			ajout enreg dans stat
			//------------------------------
			$descr="demande mag au service ".$gt ;
			$page=basename(__file__);
			$action="envoi d'une demande";
			addRecord($pdoStat,$page,$action, $descr);
			//-----------------------------------------
			//				envoi des mails
			//-----------------------------------------
			if(sendMail($mailingList,$objBt,$tplForBtlec,$name,$magName, $link))
			{
				array_push($success,"Email envoyé avec succès");
				$contentTwo="";
				sendMail($email,$objMag,$tplForMag,$full_name,$contentTwo,$linkMag);
				//on vide le formulaire et on redirige sur la page histo demande mag
				unset($objet,$msg,$name,$email);
				header('Location:'. ROOT_PATH. '/public/mag/histo.php');

			}
			else
			{
				array_push($err, "Echec d'envoi d'email");
			}
		}
		else
		//erreur insertion en db
		{
			array_push($err,"Echec : votre demande n'a pas pu être enregistrée");
		}
	}//-------------------------------------> formulaire non vide
}	//-------------------------------------> soumission formulaire
















//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');

