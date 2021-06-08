<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

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



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
// include('../view/_head-bt.php');
include('../view/_navbarfull.php');
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
	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

		<div class="row">
			<div class="col-4">
				<div class="form-group">
					<label for="model"></label>
					<input type="text" class="form-control" name="model" id="model">
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col text-right">
				<button class="btn btn-primary" name="submit">Valider</button>
			</div>
		</div>
	</form>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>