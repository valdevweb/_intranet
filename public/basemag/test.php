<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require_once '../../Class/MagDao.php';
require_once '../../Class/Mag.php';
require_once '../../Class/Helpers.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/Uploader.php';
require_once '../../Class/UploaderMulti.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "fiche mag", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
if(isset($_POST['submit-mod-add'])){
	$uploader   =   new UploaderMulti();
	$uploader->setDir('..\..\..\upload\exploit-mag\\');
	$uploader->allowAllFormats();
	$uploader->setMaxSize(5);
    //par défaut le fichier est renommé avec date
	if($data=$uploader->uploadFile('files')){
		echo "<pre>";
		print_r($uploader);
		echo '</pre>';

					// $file =$uploader->getUploadName();
	}
	else{
		$errors[]=$uploader->getMessage();
	}
 //
	echo "<pre>";
	print_r($_FILES);
	echo '</pre>';

	// if(!empty($_POST['cmt-mod'])){
	// 	$req=$pdoMag->prepare("INSERT INTO cmt_mag (btlec, cmt, created_by, date_insert) VALUES (:btlec, :cmt, :created_by, :date_insert)");
	// 	$req->execute([
	// 		':btlec'		=>$_GET['id'],
	// 		':cmt'			=>$_POST['cmt-mod'],
	// 		':created_by'	=>$_SESSION['id_web_user'],
	// 		':date_insert'	=>date('Y-m-d H:i:s')
	// 	]);
	// 	if($req->rowCount()!=1){
	// 		$err=$req->errorInfo();
	// 		$errors[]="impossible d'ajouter l'observation : ".$err[2];
	// 	}else{
	// 		$successQ='?id='.$_GET['id'].'&success=insertnote';
	// 		unset($_POST);
	// 		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	// 	}

	// }else{
	// 	$errors[]="le champs observation ne peut être vide";
	// }


}



include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->

<div class="container">
	<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method ="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col">
				<div class="form-group">
					<label>Observations : </label>
					<textarea class="form-control" name="cmt-mod" id="cmt-mod"></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="form-group">
					<p><input type="file" name="files[]" class='form-control-file' multiple=""></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col text-right">
				<button class="btn btn-primary" name="submit-mod-add">Ajouter</button>
			</div>
		</div>
	</form>
</div>
<?php
require '../view/_footer-bt.php';
?>