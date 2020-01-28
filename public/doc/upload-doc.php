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
//			VIEW
//------------------------------------------------------
include('../view/_head-mig.php');
include('../view/_navbar.php');



//------------------------------------------------------
//			DATA
//------------------------------------------------------
require '../../functions/upload.fn.php';
require '../../functions/global.fn.php';
// require '../../functions/group.fn.php';

// require '../../functions/odr.fn.php';

$docCode=array(
	3	=>"panier promo",
	4	=>"assortiment",
	5 	=>"resultats GFK",
	6	=>"listing ODR",
	7	=>"Tickets et BRII",
	8	=>"point stock MDD"
);

function isUserInGroup($pdoBt,$idWebuser,$groupName)
{

$req=$pdoBt->prepare("SELECT * FROM groups WHERE id_webuser= :idWebuser AND group_name= :groupName");
$req->execute(array(
	":idWebuser" =>$idWebuser,
	":groupName" =>$groupName
));

return $req->rowCount();
}


function insertDoc($pdoBt,$type,$file, $code)
{
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO documents (type,date,file,code,date_modif) VALUE (:type,:date,:file,:code,:date_modif)');
	$result=$insert->execute(array(
		':type'=>$type,
		':date'=>$_POST['date'],
		':file'=>$file,
		':code'=>$code,
		':date_modif'	=>date('Y-m-d H:i:s')

	));
	return $result;
}

function deleteDoc($pdoBt,$code)
{
	$delete=$pdoBt->prepare('DELETE FROM documents WHERE code= :code');
	$result=$delete->execute(array(
		':code' =>$code
	));
	return $result;
}

function addToMajHebdo($pdoBt,$type,$path,$file,$code)
{
	$insert=$pdoBt->prepare('INSERT INTO doc_maj_hebdo (type,path,file,maj,code) VALUE (:type,:path,:file,:maj,:code)');
	$insert->execute(array(
		":type"		=>$type,
		":path"		=>$path,
		":file"		=>$file,
		":maj"		=>date('Y-m-d H:i:s'),
		":code"		=>$code
	));

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
    	if(deleteDoc($pdoBt,6))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"listing des ODR", $file,6))
		{
			$success[]="l'odr a bien été enregistrée. Nom du fichier :  " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="l'odr n'a pas pu être ajoutée. Nom du fichier : " .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayOdr=ob_get_clean();
}
//Tickets et BRII
//------------------------------------------------------
//			ENVOI tickets
//------------------------------------------------------

if(isset($_POST['sendTel'])){
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
    	if(deleteDoc($pdoBt,7))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,$docCode[7], $file, 7))
		{

			// addToMajHebdo($pdoBt,$type,$path,$file,$code);
			$success[]="les Tickets et BRII ont bien été enregistrés. Nom du fichier :  " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="les Tickets et BRII n'ont pas pu être ajoutés. Nom du fichier : " .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayTel=ob_get_clean();
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
    	if(deleteDoc($pdoBt,4))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"assortiment", $file, 4))
		{
			$success[]="l'assortiment a bien été enregistré. Nom du fichier :  " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="l'assortiment n'a pas pu être ajouté. Nom du fichier : " .$file;
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
    	if(deleteDoc($pdoBt,3))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"panier promo", $file,3))
		{
			$success[]="le panier promo a bien été enregistré. Nom du fichier :  " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="le panier promo n'a pas pu être ajouté. Nom du fichier : " .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayPanier=ob_get_clean();
}

//------------------------------------------------------
//			ENVOI MDD
//------------------------------------------------------

if(isset($_POST['sendMdd'])){
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
    	if(deleteDoc($pdoBt,8))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"point stock MDD", $file, 8))
		{
			$success[]="le point stock MDD a bien été enregistré. Nom du fichier : " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="le point stock MDD n'a pas pu être ajouté. Nom du fichier : " .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayMdd=ob_get_clean();
}


//------------------------------------------------------
//			ENVOI GFK
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
    	if(deleteDoc($pdoBt,5))
    	{
    	}
    	else
    	{
			$errors[]="erreur de suppression du fichier précédant";
    	}
    	if(insertDoc($pdoBt,"resultats GFK", $file,5))
		{
			$success[]="les resultats GFK ont bien été enregistrés. Nom du fichier :  " .$file;
			unset($_POST);
			unset($_FILES);
			// unset($success);
		}
		else
		{
			$errors[]="les resultats n'ont pas pu être ajoutés. Nom du fichier : " .$file;
			unset($errors);
		}
    }
    ob_start();
	include ('../view/_errors.php');
	$errorsDisplayGfk=ob_get_clean();
}

//------------------------------------------------------
//			GESTION DES DROITS
//------------------------------------------------------

	// echo "<pre>";
	// var_dump($_SESSION);
	// echo '</pre>';

 $idUser=$_SESSION['id'];

if (isUserInGroup($pdoBt,$idUser,"communication"))
{
	$comm=true;
}
else
{
	$comm=false;
}

if (isUserInGroup($pdoBt,$idUser,"admin"))
{
	$admin=true;
}
else
{
	$admin=false;
}







// ------------------------------------------------------------------------------
include 'upload-doc.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');

?>