<?php

 // require('../../config/pdo_connect.php');
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
//			FONCTION
//------------------------------------------------------


// on va utiliser l'id pour enregistrer les produits sélectionnés sachant qu'à chaque import de la base, il changera

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
	<h1 class="text-main-blue py-5 ">Accueil - Déclaration de litige</h1>

	<!-- SOUS TITRE -->
	<div class="row mb-5">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<h3 class="text-main-blue"><span class="step step-bg-blue mr-3">1</span>Sélectionnez le cas qui correspond à votre litige :</h3>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<!-- CAS -->
	<div class="row mb-4">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col pl-5">
			<div class="btn-large bg-cyan"><a href="basic-declaration.php"><i class="fas fa-box-open fa-lg pr-5"></i>Le ou les produits incriminés SONT PRESENTS sur ma facture</a></div>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
<div class="row mb-4">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col pl-5">

		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<div class="row mb-4">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col pl-5">
			<div class="btn-large bg-red"><i class="fas fa-box-open fa-lg pr-5"></i>Le ou les produits incriminés NE SONT PAS PRESENTS sur ma facture</div>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="to-footer"></div>


	<?php
	?>


</div>
<?php

require '../view/_footer-bt.php';

?>