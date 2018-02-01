<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";




//------------------------------------------------------
//			INC
//------------------------------------------------------
require '../../functions/upload.fn.php';
require '../../functions/global.fn.php';
require '../../functions/odr.fn.php';


//------------------------------------------------------
//			FORM DATA
//------------------------------------------------------

$odrId=$_GET['odr'];

$odr=showThisOdr($pdoBt);
echo "<pre>";
var_dump($odr);
echo '</pre>';










if(isset($_POST['submit'])){
	//initialise le tableau d'erreur
	$errors=[];
	$success=[];
	//verif si tout les champ sont remplis
	if(not_empty(['operation','brand','gt','startdate','enddate']))
	{
		$isFileToUpload=isFileToUpload();
		if (!$isFileToUpload)
		{
			//recup la valeur du champ en db actuel
			$file=$odr['files'];
		}
		else
		{
			$authorized=0;
			//on stocke les extensions de fichiers interdits pour afficher message d'erreur
			$typeInterdit="";
			foreach ($_FILES as $fileDetails)
			{
				$authorizedFile=isAllowed($fileDetails['tmp_name'], $encoding=true);
				//tableau de fichier :
				if($authorizedFile[0]=='interdit')
				{
					$authorized++;
					$typeInterdit.=$authorizedFile[1];
				}
			}
			//tous les fichiers sont autorisés
			if($authorized==0)
			{
				$uploadDir= '..\..\..\upload\odr\\';
				$hashedFileName= checkUploadSameFilename($uploadDir);
				// conversion en string
				$uploadedfiles=implode("; ", $hashedFileName);
				$file=$uploadedfiles."; ".$odr['files'];

			}
			else
			{
				$errors[]="l'envoi de fichiers de type ". $typeInterdit ." est interdit";

			}
		}


	}
	else
	{
		$errors[]="merci de remplir tous les champs";
	}
	// on n'enregistre dans la base de donnée que si on a détecté aucune erreur
	if(count($errors==0))
	{
	   	if(updateOdr($pdoBt,$file,$odrId))
		{
			$success[]="l'odr a bien été modifiée " .$file;
			unset($_POST);
			unset($_FILES);
			header('Location:'. ROOT_PATH.'/upload-odr.php?success');


		}
		else
		{
			$errors[]="l'odr n'a pas pu être ajoutée" ;



		}

	}
}










include('../view/_head-mig.php');
include('../view/_navbar.php');
ob_start();
include ('../view/_errors.php');
$errorsDisplay=ob_get_clean();





// ------------------------------------------------------------------------------
include 'odr-modify.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');

?>