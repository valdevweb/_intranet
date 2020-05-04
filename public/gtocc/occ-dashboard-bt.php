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
	<h1 class="text-main-blue py-5 ">GT Occasion - accueil</h1>

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
			<h2 class="text-main-blue"> Demandes magasins en cours</h2>
		</div>
	</div>
	<div class="row">
			<div class="col">

					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th>Magasin</th>
								<th>Objet</th>
								<th>Date</th>
								<th>Nb de messages</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
							</tr>
						</tbody>
					</table>
			</div>
		</div>
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>