<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

include ('../view/_head-bt.php');
include ('../view/_navbar.php');

?>
<div class="container">
	<div class="row bgwhite">
		<div class="int-padding">
			<h1 class="text-main-blue">La livraison magasin en 24/48H</h1>
		</div>
	</div>
	<div class="row bgwhite">
		<div class="int-padding">
			<img id="info" src="../img/twentyfour/24orange.png">
			<br>
			<p id="inside-info">Afin de vous apporter un service toujours plus performant et plus optimum, <strong>BTLEC lance la livraison magasin en 24H/48H à compter du lundi 5 mars</strong>.
				<br> A partir d'un nouveau planning de commande qui a été créé pour chaque magasin, vous avez la possibilité de passer commande sur le BT le jour même pour être livré majoritairement le lendemain. <br>Cette nouvelle livraison rapide a pour but de ne pas rater d'éventuelles ventes clients sur un produit que vous n'avez pas en stock magasin mais qui est disponible sur la Centrale. Mais elle a également pour but de vous éviter de stocker de façon trop importante des gammes de produits chers et à dépréciation rapide.</p>

			</div>
		</div>
		<div class="row bgwhite">
			<div class="int-padding">
				<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3"></i>Voici les produits concernés dans un premier temps par ce mode de livraison:</h4>
				<ul class='browser-default'>
					<li>APPLE: iPhone - iPad - Mac</li>
					<li>SAMSUNG: Téléphone - Tablette</li>
					<li>PHOTO: Reflex - Hybride - Bridge - Objectif</li>
				</ul>
			</div>
		</div>
		<div class="row bgwhite">
			<div class="int-padding">
				<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3"></i>Informations pratiques:</h4>
				<ul class='browser-default'>
					<li>Clôture de la commande journalière: 12H00 pour livraison le lendemain</li>
					<li>Cadencier de commande: 0048 - Livraison 48h00</li>
					<li>Les produits restent commandables sur la commande permanente hebdomadaire. A vous de choisir selon l'urgence ou non de la livraison.</li>
					<li>Le conseil d'administration de BTLec a validé que dans un premier temps les cotisations resteront identiques et il n'y aura pas de facturation magasin sur la livraison. Une analyse sera ensuite faite d'ici quelques mois suivant les volumes et les fréquences de commandes.</li>
					<li>Utilisation Central BT: attention pour les magasins utilisant encore l'ancien outil Central BT pour passer leur commande hebdomadaire sur le BT, vous aurez la validation des produits saisis dans l'outil  tous les jours et ils vous seront expédiés ensuite sur votre commande hebdomadaire.<br>
						Il n'y aura donc plus de possibilité de modifier vos quantités tout au long de la semaine. C'est pour cette raison qu'il est préférable d'utiliser votre Back Office pour passer vos commandes sur le BT (Hebdomadaire et 24/48H).<br>
					La gestion informatique de la livraison 24/48H fait que nous ne pouvons pas procéder autrement.</li>
					<li>Livraison en magasin: c'est la messagerie TNT qui assurera les livraisons en magasins. Merci de bien prévenir vos Réceptions ou Point Accueil pour qu'il n'y ai pas de refus.
					Tous les colis qui vous seront livrés en 24H/48H par TNT porteront cette étiquette jaune afin d'être identifiable:</li>
				</ul>
				<div class="text-center"><img src="../img/twentyfour/sticker.gif"></div>
				<ul class='browser-default'>
					<li>Les BLI (bon de livraison informatique) seront émis par le BT à la remise des produits au transporteur le jour même. Donc attention pour les magasins qui sont en intégration automatique de ces BLI, les produits apparaîtront dans vos stocks en fin de journée.</li>
					<li> Information TNT: Vous recevrez en automatique un mail (sur la liste de diffusion des Responsables Bazar Technique) en fin de journée qui vous permettra de suivre l'acheminement du colis:</li>
				</ul>
				<div class="text-center"><img src="../img/twentyfour/tnt.gif"></div>

			</div>
		</div>

		<div class="row bg-white">
			<div class="col">
				<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3"></i>PLV service de livraison 24/48h</h4>

			<p class="text-center mb-5">PLV à placer dans les rayons concernés par la livraison 24/48h.</p>
		</div>
	</div>

	<div class="row bg-white mb-5">
		<!-- 			<div class="int-padding" id="plv"> -->

			<div class="col">
				<p class="text-center"><img src="../img/twentyfour/2448_1.jpg"></p>
			</div>
			<div class="col text-center">
				<i class="fas fa-download fa-3x text-light-grey my-5"></i><br>
				<a href="../pdf/2448_1.pdf">Télécharger le format A4</a><br>

			</div>
			<div class="col">
				<p class="text-center"><img src="../img/twentyfour/2448_2.jpg"></p>
			</div>
			<div class="col text-center">
				<i class="fas fa-download fa-3x text-light-grey my-5"></i><br>

				<a href="../pdf/2448_2.pdf">Télécharger le format A4<br>
			</div>



		</div>


	<div class="row bg-white">
		<!-- 			<div class="int-padding" id="plv"> -->

			<div class="col">
				<p class="text-center"><img src="../img/twentyfour/2448blanc.jpg"></p>
			</div>
			<div class="col text-center">
				<i class="fas fa-download fa-3x text-light-grey my-5"></i><br>
				<a href="../pdf/PLV_A4 24-48_BLANC.pdf">Télécharger le format A4</a><br>
				<a href="../pdf/PLV_A3 24-48_BLANC.pdf">Télécharger le format A3</a>
			</div>
			<div class="col">
				<p class="text-center"><img src="../img/twentyfour/2448gris.jpg"></p>
			</div>
			<div class="col text-center">
				<i class="fas fa-download fa-3x text-light-grey my-5"></i><br>

				<a href="../pdf/PLV_A4 24-48_GRIS.pdf">Télécharger le format A4<br>
				<a href="../pdf/PLV_A3 24-48_GRIS.pdf">Télécharger le format A3</a>
			</div>


			<!-- </div> -->
		</div>
	</div>

	<?php
// footer avec les scripts et fin de html
	include('../view/_footer-bt.php');
	?>