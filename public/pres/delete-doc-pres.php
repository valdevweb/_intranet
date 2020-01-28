<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_GET['iddoc'])){
	$req=$pdoBt->prepare("DELETE FROM pres_files WHERE id= :id");
	if($req->execute([
		':id'		=>$_GET['iddoc']
	])){
		$result=$req->rowCount();
		if($result==1){
		header("Location: upload-doc-pres.php?id=".$_GET['idpres']."&success=2");
		}else{
			$errors[]="une erreur est survenue";
		}

	}else{
		$str='';
		$pdoBt->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		$a=$req->errorInfo();
		while (list ($key, $val) = each ($a)  ) {
			$str .= "<br>$key -> $val";
		}
		$msg = " impossible de supprimer la présentation. Error Message :  $str";
	}



}






//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>




<?php
require '../view/_footer-bt.php';
?>