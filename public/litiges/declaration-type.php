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
		<div class="col-xl-1"></div>
		<div class="col">
			<h3 class="text-main-blue">Sélectionnez le cas qui correspond à votre litige :</h3>
		</div>
		<div class="col-xl-1"></div>
	</div>

	<!-- CAS -->
	<div class="row mb-4">
		<div class="col-xl-1"></div>
		<div class="col pl-5">
			<a href="declaration-stepone.php" class="no-decoration"><div class="btn-large bg-red"><i class="fas fa-box-open fa-lg pr-3"></i>J'ai une réclamation sur une de mes palettes ou liée à des produits présents sur une de mes palettes</div></a>
			<div class="pt-3 pl-5"><i>Exemples : inversion de palette, inversion de produit, produits abîmés, produits manquants, etc</i></div>
		</div>
		<div class="col-xl-1"></div>
	</div>
	<div class="row mb-4">
		<div class="col-xl-1"></div>
		<div class="col pl-5">
			<a href="dde-ouv-dossier.php" class="no-decoration"><div class="btn-large bg-cyan"><i class="fas fa-box-open fa-lg pr-3"></i>J'ai reçu des produits/palettes en plus et je n'ai pas de produit/palette manquant</div></a>
			<div class="pt-3 pl-5"><i>Exemples : palette en excédent sans inversion avec une de mes palettes, produits en excédent sans manquant</i></div>
		</div>
		<div class="col-xl-1"></div>
	</div>
	<div class="to-footer"></div>


	<?php
	?>


</div>
<?php

require '../view/_footer-bt.php';

?>