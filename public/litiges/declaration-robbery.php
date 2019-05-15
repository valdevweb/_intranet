<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr="saisie déclaration vol" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 208);




// require_once '../../vendor/autoload.php';

require ('../../Class/Uploader.php');

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
if(isset($_POST['submit']))
{
	$uploader   =   new Uploader();
	$uploader->setDir('..\..\..\upload\litiges\\');
	$uploader->allowAllFormats();
	$uploader->setMaxSize(.5);                          //set max file size to be allowed in MB//

	if($uploader->uploadFile('file')){   //txtFile is the filebrowse element name //
    $success[]  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//
	}
	else{//upload failed
    $errors[]=$uploader->getMessage(); //get upload error message
	}


}


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 ">Déclaration d'un vol</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col">

			<form method="post" enctype="multipart/form-data" >
			<div class="form-group">
				<label for='file'>Uploader le fichier excel : </label>
				<input type='file' class='form-control-file' id='file' name='file' >
				<button class="btn btn-primary mt-3" type="submit" name="submit">Envoyer</button>
				</div>
			</form>
		</div>
	</div>






	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>