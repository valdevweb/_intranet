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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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
	<h1 class="text-main-blue py-5 ">Communication - documents</h1>

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
	<div class="col"></div>
	<div class="col">
		<a href="plancom2020.php"><img src="../img/documents/plan_com.jpg" class="border"></a>
		<a href="plancom2020.php">Plan de com 2020</a>
	</div>
	<div class="col">
		<a href="kitaffiche.php"><img src="../img/documents/kit_affiche.jpg" class="border"></a>
		<a href="kitaffiche.php">Kit affiche</a>
	</div>
	<div class="col">
	<a href="twentyfour.php#plv">	<img src="../img/documents/2448_90.png" class="border"></a>
		<a href="../infos/twentyfour.php#plv">PLV 24/48h</a>
	</div>
	<div class="col"></div>
</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>