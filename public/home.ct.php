<div class="container">
	<div class="row">
		<div class="w3-content" style="max-width:1000px">
			<p class="w3-panel w3-red" ><i class="fa fa-warning fa-4x" ></i><br><br>Les arrêtés préfectoraux tombant plus vite que la neige, nous subissons depuis quelques jours un nombre croissant d'interdiction de circulation notamment sur la Région Parisienne étendue depuis ce matin à l'Oise, l' Aisne et la Marne sur les grands axes.<br>
				Nous mettons tout en oeuvre avec nos partenaires transports afin de trouver des solutions pour vous livrer.<br><br>Néanmoins, un certain nombre de destination ne peuvent être expédiées mais vos commandes sont malgré tout prise en compte et traitées afin de pouvoir vous les livrer dès que la situation va se débloquer.<br>
			Les transporteurs sont en charge de vous avertir individuellement lorsqu'ils ne peuvent pas effectuer la livraison.</p>
		</div>
	</div>
	<header>
		<h1 class="header center grey-text text-darken-2"><?= $typeTitle .' '.$_SESSION['nom'] ?></h1>
	</header>


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
								<li><a class='stat-link' data-user-session="<?=$_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank"><i class="fa fa-angle-double-right" aria-hidden="true"></i>voir les alertes promos</a></li>
							</ul>
					</div>
				</div>
				<div class="col l2"></div>
				<div class="col s12 m5 l5">
					<div class="vm-card">
						<p class="orange-text text-darken-2">DOCUBASE :</p>
						<hr>
						<ul class='links'>
							<li>Retouvez les documents émis par BTlec : <a class="simple-link stat-link" data-user-session="<?=$_SESSION['user']?>"  href="http://172.30.101.66/rheaweb/auth">factures, bon livraison, etc</a></li>
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

