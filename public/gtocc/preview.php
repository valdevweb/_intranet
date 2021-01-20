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
// require_once '../../vendor/autoload.php';
require_once '../../Class/OccInfoDao.php';
require_once '../../Class/DateHelpers.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------



 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$occInfoDao= new OccInfoDao($pdoOcc);
$data=$occInfoDao->getHtmlNews($_GET['file']);
if(VERSION=="_"){
	$target_dir = "D:\\www\\_intranet\\upload\\flash\\";
}else{
	$target_dir = "D:\\www\\intranet\\upload\\flash\\";
}

$pjDir=UPLOAD_DIR.'\\flash\\';










//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container-fluid">
	<div class="row bg-white">
		<div class="col-lg-1 col-xl-2"></div>

		<div class="col">
			<h1 class="text-main-blue py-5 ">Prévisualisation</h1>

		</div>
		<div class="col-lg-1 col-xl-2"></div>

	</div>
	<div class="row bg-white pb-5 mb-5">
		<div class="col">
			<?php
			include('news-inc.php')
			?>
		</div>
	</div>
<!-- 	<div class="row">
		<div class="col">&nbsp; ooo</div>
	</div> -->
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>