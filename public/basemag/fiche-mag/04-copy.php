<?php
require('../../../config/autoload.php');
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

include '../../../config/db-connect.php';


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




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


if(isset($_GET['id']) && isset($_GET['field']) && isset($_GET['value'])){
	$query="UPDATE sca3 LEFT JOIN mag ON sca3.btlec_sca= mag.id SET {$_GET['field']}=mag.{$_GET['value']} WHERE btlec_sca={$_GET['id']}";
	$req=$pdoMag->query($query);
	$err=$req->errorInfo();
// 2 => message d'erreur / 1 = code erreur / 0 =sql state
	if(empty($err[2])){
		header('Location:'.ROOT_PATH.'/public/basemag/fiche-mag.php?id='.$_GET['id'].'#'.$_GET['position']);
	}
}else{
	header('Location:'.ROOT_PATH.'/pûblic/basemag/fiche-mag.php?id='.$_GET['id'].'err=copy');
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