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
// require '../../functions/odr.fn.php';
function insertDoc($pdoBt,$type,$file)
{
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO documents (type,date,file) VALUE (:type,:date,:file)');
	$result=$insert->execute(array(
		':type'=>$type,
		':date'=>$_POST['date'],
		':file'=>$file
	));
	return $result;
}

function deleteDoc($pdoBt,$type)
{
	$delete=$pdoBt->prepare('DELETE FROM documents WHERE type= :type');
	$result=$delete->execute(array(
		':type' =>$type
	));
	return $result;
}

$errors=[];
$success=[];
//------------------------------------------------------
//			ENVOI ODR
//------------------------------------------------------
if(isset($_POST['sendOdr'])){
	//initialise le tableau d'erreur

	//verif si tout les champ sont remplis
	if(not_empty(['date']))
	{
		//vérifie si fichier à uploader
		$isFileToUpload=isFileToUpload();
		if (!$isFileToUpload)
		{
			$errors[]="aucun fichier joint";
		}
		else
		{
				$uploadDir= '..\..\..\upload\documents\\';
				$hashedFileName= uploadFileNoHash($uploadDir);
				$file=implode("; ", $hashedFileName);
		 }
	}
	else
	{
		$errors[]="merci de remplir tous les champs";
	}
	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur
	if(count($errors)==0)
    {
    	if(deleteDoc($pdoBt,"listing des ODR"))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"listing des ODR", $file))
		{
			$success[]="l'odr a bien été enregistrée " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="l'odr n'a pas pu être ajoutée" .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayOdr=ob_get_clean();
}

//------------------------------------------------------
//			ENVOI assortiment
//------------------------------------------------------

if(isset($_POST['sendAssort'])){
	//initialise le tableau d'erreur

	//verif si tout les champ sont remplis
	if(not_empty(['date']))
	{
		//vérifie si fichier à uploader
		$isFileToUpload=isFileToUpload();
		if (!$isFileToUpload)
		{
			$errors[]="aucun fichier joint";
		}
		else
		{
				$uploadDir= '..\..\..\upload\documents\\';
				$hashedFileName= uploadFileNoHash($uploadDir);
				$file=implode("; ", $hashedFileName);
		 }
	}
	else
	{
		$errors[]="merci de remplir tous les champs";
	}
	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur
	if(count($errors)==0)
    {
    	if(deleteDoc($pdoBt,"assortiment"))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"assortiment", $file))
		{
			$success[]="l'assortiment a bien été enregistré " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="l'assortiment n'a pas pu être ajouté" .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayAssort=ob_get_clean();
}

//------------------------------------------------------
//			ENVOI panier
//------------------------------------------------------

if(isset($_POST['sendPanier'])){
	//initialise le tableau d'erreur

	//verif si tout les champ sont remplis
	if(not_empty(['date']))
	{
		//vérifie si fichier à uploader
		$isFileToUpload=isFileToUpload();
		if (!$isFileToUpload)
		{
			$errors[]="aucun fichier joint";
		}
		else
		{
				$uploadDir= '..\..\..\upload\documents\\';
				$hashedFileName= uploadFileNoHash($uploadDir);
				$file=implode("; ", $hashedFileName);
		 }
	}
	else
	{
		$errors[]="merci de remplir tous les champs";
	}
	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur
	if(count($errors)==0)
    {
    	if(deleteDoc($pdoBt,"panier promo"))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"panier promo", $file))
		{
			$success[]="le panier promo a bien été enregistré " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="le panier promo n'a pas pu être ajouté" .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayPanier=ob_get_clean();
}

//------------------------------------------------------
//			ENVOI panier
//------------------------------------------------------

if(isset($_POST['sendGfk'])){
	//initialise le tableau d'erreur

	//verif si tout les champ sont remplis
	if(not_empty(['date']))
	{
		//vérifie si fichier à uploader
		$isFileToUpload=isFileToUpload();
		if (!$isFileToUpload)
		{
			$errors[]="aucun fichier joint";
		}
		else
		{
				$uploadDir= '..\..\..\upload\documents\\';
				$hashedFileName= uploadFileNoHash($uploadDir);
				$file=implode("; ", $hashedFileName);
		 }
	}
	else
	{
		$errors[]="merci de remplir tous les champs";
	}
	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur
	if(count($errors)==0)
    {
    	if(deleteDoc($pdoBt,"resultats GFK"))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"resultats GFK", $file))
		{
			$success[]="les resultats GFK ont bien été enregistrés " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="les resultats n'ont pas pu être ajoutés" .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayGfk=ob_get_clean();
}




//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-mig.php');
include('../view/_navbar.php');





// ------------------------------------------------------------------------------
include 'upload-doc.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');

?>