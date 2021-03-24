<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');







//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue py-5 ">Les achats vous informent</h1>
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


			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">La gazette</div>
				 <img src="../img/divers/newspaper2.png" class="card-img-top" alt="...">

				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">Les GESAP</div>
				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">Les ODR</div>
				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">Les offres produits</div>
				 <img src="../img/divers/tel-120.png" class="card-img-top" alt="...">

				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">Les offres spéciales</div>
				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">le suivi livraison</div>
				 <img src="../img/divers/delivery.jpg" class="card-img-top" alt="...">

				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">Assortiment et panier promo</div>
				 <img src="../img/divers/promo.png" class="card-img-top" alt="...">
				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">MDD</div>
				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow mb-3" style="max-width: 18rem;">
				<div class="card-header text-white bg-dark-blue">GFK</div>
				 <img src="../img/divers/gfk.png" class="card-img-top" alt="...">


				<div class="card-body ">
					<p class="card-text">Texte de présentation Texte de présentation Texte de présentation</p>
				</div>
			</div>
		</div>

	</div>




</div>

<?php
require '../view/_footer-bt.php';
?>