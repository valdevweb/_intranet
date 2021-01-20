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
require "../../functions/stats.fn.php";
$descr="salon 2019 - page presentation" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 101);




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
	<div class="row pb-5">
		<div class="col">
			<h1 class="text-main-blue pt-5 ">Présentations Salon BTLec 2020</h1>
		</div>
		<div class="col text-right">

		</div>

	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row py-5">
		<div class="col">

			<h5 class="text-main-blue text-center pb-3">Présentation de la convention</h5>
			<div class="overlay-container">
				<div id="bg-img-convention">
					<div class="overlay">
						<a href="#" class="link-overlay" target="_blank">
							A venir
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<h5 class="text-main-blue text-center pb-3">Présentation GFK multimédia</h5>

			<div class="overlay-container">
				<div id="bg-img-multimedia">
					<div class="overlay">
						<a href="pdf/gfk-multi-2020.pdf" class="link-overlay" target="_blank">
							Télécharger
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<h5 class="text-main-blue text-center pb-3">Présentation GFK maison</h5>

			<div class="overlay-container">
				<div id="bg-img-maison">
					<div class="overlay">
						<a href="pdf/gfk-maison-2020.pdf" class="link-overlay" target="_blank">
							Télécharger
						</a>
					</div>
				</div>
			</div>
		</div>





	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>