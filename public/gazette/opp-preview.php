<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}
//			css spécifique
//----------------------------------------------------------------
$pageCss='opp-display-inc';
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require_once '../../Class/OpportuniteDAO.php';



 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
define("DIR_UPLOAD_OPP",DIR_UPLOAD."opportunites\\");
define("URL_UPLOAD_OPP",URL_UPLOAD."opportunites/");
$oppDao=new OpportuniteDAO($pdoBt);


$listOpp=$oppDao->getOpp($_GET['id']);
$oppIds=[$_GET['id']];


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
	<h1 class="text-main-blue py-5 ">Prévisualisation de l'opportunité</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<?php
	include 'opp-display-inc.php'
	?>
	<div class="row my-5 pb-5">
		<div class="col">
			<a href="opp-exploit.php" class="btn btn-primary">Retour</a>
		</div>
		<div class="col text-right">
			<a href="opp-edit.php?id=<?=$_GET['id']?>" class="btn btn-primary">Modifier</a>
		</div>
	</div>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>