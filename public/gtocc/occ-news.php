<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

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

$activeNews=$occInfoDao->getActiveNews();
$target_dir = DIR_UPLOAD."flash\\";

$pjDir=URL_UPLOAD.'\\flash\\';







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
			<h1 class="text-main-blue pt-5 ">Vos informations - Leclerc Occasion</h1>


		</div>
		<div class="col-lg-1 col-xl-2"></div>
	</div>


	<?php foreach ($activeNews as $key => $data): ?>

		<div class="row bg-white">
			<div class="col">
				<?php include 'news-inc.php' ?>
				<!-- <h2 class="text-center">&#9672 &#9672 &#9672</h2> -->
				<!-- <h2 class="text-center">&#10059 &#10059 &#10059</h2> -->
				<!-- <h2 class="text-center">&#9999 &#9999 &#9999</h2> -->
			</div>
		</div>
	<?php endforeach ?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>