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
//			DATA
//------------------------------------------------------
require '../../functions/upload.fn.php';
require '../../functions/global.fn.php';
require '../../functions/odr.fn.php';


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
			$errors[]="aucun fichier joint";
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
				$file=implode("; ", $hashedFileName);

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
    	if(insertOdr($pdoBt,$file))
		{
			$success[]="l'odr a bien été enregistrée " .$file;
			unset($_POST);
			unset($_FILES);
		}
		else
		{
			$errors[]="l'odr n'a pas pu être ajoutée" .$file;

		}
    }


}

//construction du lien pour visualiser les fichiers odr
// $link="http://172.30.92.53/".$version."upload/odr/";

//---------------------------------------------------
//
//			tableau éditable avec evnoi php ajax
//https://www.phpflow.com/php/html5-inline-editing-php-mysql-jquery-ajax/
//http://www.webslesson.info/2017/05/live-table-data-edit-delete-using-tabledit-plugin-in-php.html
//http://phppot.com/php/php-crud-with-search-and-pagination-using-jquery-ajax/				avec id

include('../view/_head-mig.php');
include('../view/_navbar.php');
ob_start();
include ('../view/_errors.php');
$errorsDisplay=ob_get_clean();
// ------------------------------------------------------------------------------
include 'upload-odr.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');

?>