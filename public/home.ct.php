<div class="container">
	<header>
		<h1 class="header center grey-text text-darken-2"><?= $typeTitle .' '.$_SESSION['nom'] ?></h1>
	</header>
	<br><br>
	 <div class="row">
		<!-- <div class="col s2 m2 l2"></div> -->
		<div class="col s12 m12 l12 white"><br>
			<div class="col s2 m2 l2">
					<img src="img/icons/info.jpg"><br><br>
			</div>
			<div class="col s10 m10 l10">
				<p class="blue-text text-darken-2 bigger">Au vu des nombreux jours fériés sur le mois de Mai et des interdictions préfectorales de circulation des poids lourds, <strong>nous ne serons pas dans la capacité de livrer certains magasins de leur commande hebdomadaire sur les semaines 18 et 19.</strong><br>
				Nous ferons le nécessaire en amont pour les produits en catalogue pour que vous les ayez à temps.<br>
				En revanche pour vos commandes permanentes hebdomadaires, attention de bien prévoir vos réassorts sur la semaines 17 en tenant compte de la semaine 18 et 19 sans livraison.<br>
				<strong>Votre planning de commande hebdomadaire va être modifié en conséquence et vous recevrez le mail de modification habituel</strong> suite aux changements de date de livraison.<br>
				Concernant <strong>les commandes et livraisons magasins en 24H/48H, elles ne seront pas impactées hormis sur les jours fériés.</strong></p>
			</div>

		</div>
		<!-- <div class="col s2 m2 l2"></div> -->
	</div>
	<br>
	<!-- <div class="row">
		<div class="col s2 m2 l2"></div>
		<div class="col s8 m8 l8 white"><br>
			<div class="col s2 m2 l2">
					<img src="img/icons/warning.png"><br><br>
			</div>
			<div class="col s10 m10 l10">
					<p class="red-text text-darken-2 bigger"><strong>Attention : </strong><br>Lundi 2 avril, jour férié<br> Pas de commandes 24/48h disponibles

					</p>
			</div>

		</div>
		<div class="col s2 m2 l2"></div>

	</div>
	<br><br> -->

	<?php
			ob_start();
	 ?>
	<div class="w3-panel w3-light-grey"><h2 class="orange-text text-darken-2"><strong>Lancement de la livraison magasin en 24/48H</strong></h2>
		<img id="info" src="img/icons/new-orange.png">
		<p id="inside-info">Afin de vous apporter un service toujours plus performant et plus optimum, <strong>BTLEC lance la livraison magasin en 24H/48H à compter du lundi 5 mars</strong>.
		<br> A partir d'un nouveau planning de commande qui a été créé pour chaque magasin, vous avez la possibilité de passer commande sur le BT le jour même pour être livré majoritairement le lendemain. <br>Cette nouvelle livraison rapide a pour but de ne pas rater d'éventuelles ventes clients sur un produit que vous n'avez pas en stock magasin mais qui est disponible sur la Centrale. Mais elle a également pour but de vous éviter de stocker de façon trop importante des gammes de produits chers et à dépréciation rapide.</p>
		<p id="more"><a href="infos/twentyfour.php">les infos en détail</a></p>
	</div>
	<?php
	 	$noSocara=ob_get_contents();
		ob_end_clean();
		if(!isset($_SESSION['centrale']))
			{
				echo $noSocara;
			}
			else{
				if($_SESSION['centrale'] !="SOCARA")
				{
					echo $noSocara;
				}
		}
	?>


	<section>
		<h4 class="grey-text text-darken-2"><i>Quelques mots sur votre centrale d'achats Bazar Technique Leclerc:</i></h4>
		<div class="row">
			<div class="w3-content" style="max-width:1000px">
				<div class="mySlides w3-container w3-deep-orange">
					<div class="slider-height">
						<h2><b>Quelques chiffres</b></h2>
						<h2><i>380 magasins adhérents repartis dans 9 centrales</i></h2>
					</div>
				</div>
				<div class="mySlides w3-container w3-blue-grey">
					<div class="slider-height">
						<h2><b>L'entrepot</b></h2>
						<h2><i>52 000 m2 de surface de stockage avec environ 4000 références de produits différents</i></h2>
					</div>
				</div>

			</div>

		</div>
		</section>



		<div class="down"></div>
		<section>
			<!-- titre des colonnes principales -->
			<div class="row">
				<div class="col s12 m5 l5">
					<h4 class="grey-text text-darken-2">Vos actualités : </h4>
				</div>
				<div class="col l2"></div>
				<div class="col s12 m5 l5">
					<h4 class="grey-text text-darken-2">Liens utiles :</h4>
				</div>
			</div>
			<!-- cards avec les liens -->
			<div class="row">
				<div class="col s12 m5 l5">
					<div class="vm-card">
						<p class="orange-text text-darken-2">LES GAZETTES DE LA SEMAINE :</p>
						<hr>
						<ul class='links'>
							<?php foreach ($links as $link): ?>
								<?= $link ?>
							<?php endforeach ?>
						</ul>
						<p class="orange-text text-darken-2">LES GAZETTES APPROS :</p>
						<hr>
						<ul class='links'>
							<?= isset($approHtml)? $approHtml: ''?>
						</ul>
						<P class="orange-text text-darken-2">LES ALERTES PROMOS</p>
							<hr>
							<ul class='links'>
								<li><a class='stat-link' data-user-session="<?= $_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank"><i class="fa fa-angle-double-right" aria-hidden="true"></i>voir les alertes promos</a></li>
							</ul>
					</div>
				</div>
				<div class="col l2"></div>
				<div class="col s12 m5 l5">
					<div class="vm-card">
						<p class="orange-text text-darken-2">DOCUBASE :</p>
						<hr>
						<ul class='links'>
							<li>Retouvez les documents émis par BTlec : <a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="http://172.30.101.66/rheaweb/auth">factures, bon livraison, etc</a></li>
						</ul>
					</div>
						<!-- <div class="vm-card">
							<P class="orange-text text-darken-2">VOS DEMANDES :</p>
								<hr>
								<ul class='links'>
									<li>En cours de construction !
										<br>Bientôt ici le récapitulatif de vos demandes.
									</li>
									<li></li>
								</ul>
							</div>
 -->
				</div>
			</div>
		</div>
	</section>


<?php

if(isset($_POST['urlSend']))
{

	echo $descr=$_POST['urlSend'];
echo $action=$_POST['action'];
echo $page=$_POST['page'];

}
?>
</div>

