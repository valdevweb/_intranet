<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';

//----------------------------------------------------------------
//			affichage : infos du services + message non clos
//----------------------------------------------------------------
$gt=$_GET['gt'];
$gtInfos=initForm($pdoBt);
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




//----------------------------------------------------------------
//		éviter mulitple soumission du form->header ap traitement
//----------------------------------------------------------------
$here = $_SERVER['PHP_SELF'] ;
if(!empty($_SERVER['QUERY_STRING']))
{
	$here .= '?' . $_SERVER['QUERY_STRING'] ;
}



function sendMail($mailingList,$subject,$tplLocation,$contentOne,$contentTwo,$link)
{
	$tpl = file_get_contents($tplLocation);
	$tpl=str_replace('{CONTENT1}',$contentOne,$tpl);
	$tpl=str_replace('{CONTENT2}',$contentTwo,$tpl);
	$tpl=str_replace('{LINK}',$link,$tpl);


	$htmlContent=$tpl;
// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
	$headers .= 'From: ne_pas_repondre@btlec.fr>' . "\r\n";
	$headers .= 'Cc: ' . "\r\n";
	$headers .= 'Bcc:' . "\r\n";

	if(mail($mailingList,$subject,$htmlContent,$headers))
	{
		return true;
	}
	else
	{
		return false;
	}

}

// templates et sujet des mails envoyés à la soumission du formulaire
// un mail informer le service qu'une demande à été posté
// un mail au mag pour lui confirmer que sa demande a été envoyé
$tplForBtlec="../mail/new_mag_msg.tpl.html";
$tplForMag="../mail/ar_mag.tpl.html";
$objBt="PORTAIL BTLec - nouvelle demande magasin";
$objMag="PORTAIL BTLec - demande envoyée";
//$objMag = utf8_decode($objMag);
//$objMag = mb_encode_mimeheader($objMag,"UTF-8");
mb_internal_encoding('UTF-8');
$objMag = mb_encode_mimeheader($objMag);

$magName=$_SESSION['nom'];

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
	// en dehors du file aucun champ ne doit être vide
	if(empty($objet) || empty($msg) || empty($name) || empty($email))
	{
		array_push($err, "merci de remplir tous les champs");
		//format mail jo
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				array_push($err, 'Indiquez un email valide');
		}


	}
	else
	{
		//formulaire corectement rempli
		//pas de piece jointe
		if (empty($_FILES['file']['name']))
		{

			$file="";
			//ajoute le msg dans db et
			//recup l'id du msg posté : lien dans le mail : index.php?$lastId
			if($lastId=addMsg($pdoBt,$idGt, $file))
			{
				array_push($success, "Demande enregistrée avec succès");
				//créa du lien pour le mail
				$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour consulter le message</a>";
				$linkMag="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour revoir votre demande</a>";


			}
			else
			{
				array_push($err,"Echec : votre demande n'a pas pu être enregistrée");
			}
		}
		else
		{
			//------------------------------
			//			upload du fichier
			//------------------------------
			$upload=$_FILES['file'];
			$uploadDir= '..\..\..\upload\mag\\';
			$md5=checkUpload($upload, $uploadDir, $pdoBt);
			//------------------------------
			//			enreg db
			//------------------------------
			if($lastId=addMsg($pdoBt,$idGt, $md5['success']))
			{
				//créa du lien pour le mail
				$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour consulter le message</a>";
				$linkMag="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour revoir votre demande</a>";

			}
			else
			{
				array_push($err,"Echec : votre demande n'a pas pu être enregistrée");
			}

		}
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
}















//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');

