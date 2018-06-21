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
// require '../../functions/upload.fn.php';
// affic
require '../../functions/global.fn.php';
//fichier d'affichage d'erreur mis en tampon plus bas
//	include ('../view/_errors.php');


function deleteGaz($pdoBt,$id){
	$where = ['id' => $id];
	$req=$pdoBt->prepare("SELECT file FROM gazette WHERE id=:id");
	$req->execute($where);
	$name=$req->fetch(PDO::FETCH_ASSOC);
	$pdoBt->prepare("DELETE FROM gazette WHERE id=:id")->execute($where);
	return $name;
}


if(isset($_GET['sup'])){
	// echo 'id a sup ' .$_GET['sup'];
	$suppressed=deleteGaz($pdoBt,$_GET['sup']);
	$suppressed=$suppressed['file'];
	// header('location:deleted.php?id='.$suppressed);

}


// liste des type de documents pour profil admin => droit admin et comm
function getAllTypeNames($pdoBt)
{
	$req=$pdoBt->query("SELECT * FROM doc_type WHERE code !=10 ORDER BY name");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
//liste des types de documents pour comm
function getNonAdminTypeNames($pdoBt)
{
	$req=$pdoBt->query("SELECT * FROM doc_type WHERE droits ='comm' AND code !=10 ORDER BY name");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

//converti code en libellé
function getTypeName($pdoBt)
{
	$req=$pdoBt->prepare("SELECT name FROM doc_type WHERE code= :code");
	$req->execute(array(
		":code"	=>$_POST['type']
	));
	return $req->fetch();

}

//ajout documents type assortiment
function insertIntoDbDoc($pdoBt,$type,$file,$descr, $docDate)
{
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO documents (type,date,file,code,date_modif, name) VALUE (:type,:date,:file,:code,:date_modif, :name)');
	$result=$insert->execute(array(
		':type'=>$type,
		':date'=>$docDate,
		':file'=>$file,
		':code'=>$_POST['type'],
		':date_modif'	=>date('Y-m-d H:i:s'),
		':name'=>$descr,
	));
	// print_r($insert->errorInfo());
	// print_r($result);

	return $result;
}


function insertIntoDbGazette($pdoBt,$file,$dateDeb,$dateFin, $category,$title)
{
	// $reply=strip_tags($_POST['reply']);
	if($dateFin=="")
	{

	}
	$insert=$pdoBt->prepare('INSERT INTO gazette (file,date,date_fin,category,code,title,date_modif) VALUE (:file,:date,:date_fin,:category,:code,:title,:date_modif)');
	$result=$insert->execute(array(
		':file'=>$file,
		':date'=>$dateDeb,
		':date_fin' => $dateFin,
		':category' =>$category,
		':code'=>$_POST['type'],
		':title'=>$title,
		':date_modif'	=>date('Y-m-d H:i:s'),

	));
	// print_r($insert->errorInfo());
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

$errors=[];
$success=[];

$types=getNonAdminTypeNames($pdoBt);
$idUser=$_SESSION['id'];
// fonction de la _navbar
if (isUserInGroup($pdoBt,$idUser,"communication"))
{
	$types=getNonAdminTypeNames($pdoBt);
}
elseif (isUserInGroup($pdoBt,$idUser,"admin"))
{
	$types=getAllTypeNames($pdoBt);
}
else
{
	$types=[];
}

if(isset($_POST['send']) )
{
	extract($_POST);
	// libelle suivant le type selectionné dans liste déroulante
	$category=getTypeName($pdoBt);
	// echo $category['name'];
	//traitement différent suivant type de fichier
	if($type==3 || $type==4 || $type==5 || $type==6 || $type==7 || $type==11 || $type==9)
	{
		if($_FILES['file']['error']===0)
		{
			$uploadDir= '..\..\..\upload\documents\\';
			$filename=new SplFileInfo($_FILES['file']['name']);
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
			{
				$success[]="le fichier ". $filename." a été uploadé avec succès  " ;

			}
			else
			{
				$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";

			}

		}
		if(count($errors)==0)
		{
			// si type 8  on récupère le libelle
			if(isset($_POST['libelle']))
			{
				$title=$_POST['libelle'];
			}
			else
			{
				$title="";
			}

			if(isset($_POST['date']))
			{
				$docDate=$_POST['date'];
			}
			else
			{
				$docDate=date('Y-m-d');
			}

			if(deleteDoc($pdoBt,$type))
			{
			}
			else
			{
				$errors[]="erreur de suppression du fichier précédant";
			}

			if(insertIntoDbDoc($pdoBt,$category['name'], $filename,$title, $docDate))
			{

				$success[]= $category['name'] . " mis à jour  ";
				$_POST=array();
				$_FILES=array();
				// unset($_POST);
				// unset($_FILES);
			// unset($success);
			}
			else
			{

				$errors[]="le fichier n'a pas pu être ajouté à la base de donnée";
				// unset($errors);
			}
		}
// include('listing-doc.php');

	}
// gazette quotidienne
	elseif($type==1 || $type==8 || $type==2)
	{
		if($_FILES['file']['error']===0)
		{
			$uploadDir= '..\..\..\upload\gazette\\';
			$filename=new SplFileInfo($_FILES['file']['name']);
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
			{
				// $success[]="le fichier ". $filename." a été enregistré avec succès  " ;

			}
			else
			{
				$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";

			}

		}
		if(count($errors)==0)
		{

			//vérifie l'existance des champs de formulaire :
			// $title=isset($_POST['libelle']) || ? $_POST['libelle'] :"";
			if(isset($_POST['libelle']))
			{
				$title=$_POST['libelle'];
			}
			elseif(isset($_POST['descriptif']))
			{
				$title=nl2br($_POST['descriptif']);
			}
			else
			{
				$title="";
			}
			// echo $_POST['dateDeb'];
			$dateDeb=isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
			$dateFin=isset($_POST['dateFin']) ?  $_POST['dateFin'] : NULL;

			if(insertIntoDbGazette($pdoBt,$filename,$dateDeb,$dateFin,$category['name'],$title))
			{
				$success[]=" le fichier ". $category['name'] ." - " .$filename . " a été enregistré avec succès  ";
				unset($_POST);
				unset($_FILES);
			// unset($success);
			}
			else
			{
				$errors[]="le fichier n'a pas pu être ajoutée. Nom du fichier : " .$filename;
				// unset($errors);
			}
		}

	}

	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur

	ob_start();
	include ('../view/_errors.php');
	$errorsDisplayOdr=ob_get_clean();



}








// ------------------------------------------------------------------------------
include 'upload-main.ct.php';
//--------------------------------------------------------------------------------



include('../view/_footer.php');