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

require '../../config/db-connect.php';


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------





if(isset($_GET['id'])){

	$copy=copyCasse($pdoCasse);
		echo "<pre>";
		print_r($copy);
		echo '</pre>';

	if($copy==1){
		$delete=deleteCasse($pdoCasse);
		if($delete==1){
			header('Location: casse-dashboard.php?deleteOk='.$_GET['id'].'');
		}
		else{
			header('Location: casse-dashboard.php?error=2');
		}
	}
	else{
	header('Location: casse-dashboard.php?error=2');

	}


}
else{
	header('Location: casse-dashboard.php?error=1');
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