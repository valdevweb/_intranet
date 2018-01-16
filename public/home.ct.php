<div class="container">
	<header>
		<h1 class="header center grey-text text-darken-2"><?= $typeTitle .' '.$_SESSION['nom'] ?></h1>
	</header>
	<section>
		<h4 class="grey-text text-darken-2"><i>Quelques mots sur votre centrale d'achats Bazar Technique Leclerc:</i></h4>
		<div class="row">
			<div class="w3-content" style="max-width:800px">
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
		</section>



		<div class="down"></div>
		<section>
			<h4 class="grey-text text-darken-2">Vos actualités</h4>
			<div class="row">

				<div class="col s12 m5 l5">
					<div class="vm-card">
						<P class="orange-text text-darken-2">LES GAZETTES DE LA SEMAINE :</p>
							<hr>
							<ul>
								<?php foreach ($links as $link): ?>
									<?= $link ?>
								<?php endforeach ?>
							</ul>
							<P class="orange-text text-darken-2">LES OPPORTUNITES</p>
							<hr>
							<ul>
								<a class='stat-link' data-user-session="<?=$_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank">consulter</a>
							</ul>
						</div>
					</div>

					<div class="col l2"></div>
					<div class="col s12 m5 l5">
						<div class="vm-card">
							<P class="orange-text text-darken-2">VOS DEMANDES :</p>
								<hr>
								<ul>
									<li>En cours de construction !
										<br>Bientôt ici le récapitulatif de vos demandes.
									</li>
									<li></li>
								</ul>
							</div>
						</div>
					</div>
				</section>


				<div class="down"></div>
				<section>
					<h4 class="grey-text text-darken-2">Liens utiles</h4>
					<div class="row">
						<div class="col s12 m5 l5">
							<div class="vm-card">
								<a class="simple-link stat-link" data-user-session="<?=$_SESSION['user']?>" href="http://172.30.101.66/rheaweb/auth">Docubase : </a>
								<ul>
									<li><a class="simple-link stat-link" data-user-session="<?=$_SESSION['user']?>"  href="http://172.30.101.66/rheaweb/auth">Retouvez les documents émis par BTlec : factures, bon livraison, etc</a></li>
								</ul>
							</div>
						</div>
					</div>
				</section>
			</div>
<p id="test"></P>

<?php

if(isset($_POST['urlSend']))
{

	echo $descr=$_POST['urlSend'];
echo $action=$_POST['action'];
echo $page=$_POST['page'];

}
?>
</div>

