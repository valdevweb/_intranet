<div class="container">
	<header>
		<h1 class="header center grey-text text-darken-2"><?= $typeTitle .' '.$nom ?></h1>
	</header>
	<!-- encadré -->
	<?php
	include('../public/doc/flash-view.php');

	 ?>

	<br>
	<?php
	ob_start();
	?>
	<div class="row bg-white">
		<div class="col">
			<h5 class="center boldtxt">A compter du <span class="orange-text text-darken-2"> 1er Avril 2019</span>, les retours SAV BTLEC magasin transiteront par la <span class="orange-text text-darken-2">Centrale SOCAMIL</span></h5>
			<p class="blue-text text-darken-2 boldtxt">EN MAGASINS</p>

			<p class="bg-alert bg-alert-primary">Les Boxs Sav Btlec en retours, seront récupérés à vos réceptions magasins par nos transporteurs, tous les  <span class="boldtxt"> MARDI JEUDI ET  SAMEDI </span> de chaque semain e(cette fréquence de retour est impérative pour un suivi et surtout un retour SAV régulier).<br>
				Le Chauffeur devra impérativement récupérer les boxs avec 2 plombs par box.<br>
				Aucun ordre de retour ne sera émis par la Socamil puisque la reprise est impérative sur les trois jours prévus.<br>
			Les premiers boxs Sav avec les kits de démarrage, vous seront livrés mardi matin.</p>

			<p class="blue-text text-darken-2 boldtxt">EN CENTRALE</p>

			<div class="bg-alert bg-alert-primary"><p>Les boxs SAV seront retournés et déchargés <span class="boldtxt">OBLIGATOIREMENT en Centrale sur l'entrepôt SEC</span><br>
				<span class="boldtxt">INTERDICTION ABSOLUE DE DÉCHARGER AUX ENTREPÔTS FRAIS ET GRISOLLES.</span><br>
			Les personnes chargées de transférer les boxs sur la zone de contrôle du technicien BTLEC SAV  en Socamil sont :</p>
			<ul class="browser-default">
				<li>Eric LACOMBE / Christophe JEANNET tel fixe 0567691867 et poste 1960 /  tel port 06.89.53.17.33   DU LUNDI AU VENDREDI DE 6h00 à 15h00</li>
				<li>Alain HERNANDEZ / Michel RODRIGUEZ tel port 06.37.68.16.74   DU LUNDI AU VENDREDI  DE 15h00 à 20h00 et le SAMEDI DE 06h00 à 14h00</li>
			</ul>
		</div>
		<p>Le cheminement de vos boxs SAV:</p>
		<img src="../public/img/socamil/shema.gif">
		<p>Votre interlocuteur Sav Btlec en Socamil est Mr Frédéric KOCIALKOWSKI /  tel port 0683232434 / tel poste fixe 1799<br>
			<!-- Vous pouvez également contacter M. Alain Bénazet au 0607127103 si vous souhaitez des renseignements complémentaires. -->
		</p>

	</div>

</div>

<?php
$onlySocamil=ob_get_contents();
ob_end_clean();


ob_start();

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
<?php
$normalContent=ob_get_contents();
ob_end_clean();

if($d_Socamil)
{
	echo $onlySocamil;
}
else
{
	echo $normalContent;
}

?>
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
			<div class="vm-card white">
				<p class="orange-text text-darken-2">LES GAZETTES DE LA SEMAINE :</p>
				<hr>
				<ul class='links'>
					<?php foreach ($links as $link): ?>
						<?= $link ?>
					<?php endforeach ?>
					<?= isset($speHtml)? $speHtml: ''?>
				</ul>
				<p class="orange-text text-darken-2">LES GAZETTES SUIVI LIVRAISON CATALOGUE :</p>
				<hr>
				<ul class='links'>
					<?= isset($approHtml)? $approHtml: ''?>
				</ul>
				<P class="orange-text text-darken-2">LES ALERTES PROMOS</p>
					<hr>
					<ul class='links'>
						<li>
							<a class='stat-link' data-user-session="<?= $_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank"><i class="fa fa-external-link pr-3" aria-hidden="true"></i>voir les alertes promos</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col l2"></div>
			<div class="col s12 m5 l5">
				<div class="vm-card white">
					<p class="orange-text text-darken-2">DOCUBASE :</p>
					<hr>
					<ul class='links'>
						<li>Retrouvez vos factures, reversement, bon livraison, etc<br>
						<a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="http://172.30.101.66/rheaweb/auth"><i class="fa fa-external-link pr-3" aria-hidden="true"></i>Docubase</a></li>
						<?php echo isset($infoRev) ? $infoRev:"";?>
					</ul>
					<p class="orange-text text-darken-2">Le portail EXTRALEC :</p>
					<hr>
					<ul class='links'>
						<li>Prenez en main rapidement et facilement les applications de l'univers Extralec grâce au portail (guide d'installation , formation, assistance technique) et administrez vos comptes utilisateurs.<br><a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="https://www.extralecbtlec.fr/"><i class="fa fa-external-link pr-3" aria-hidden="true"></i>Portail Extralec</a><br><br></li>
						<li>Si vous ne connaissez pas bien Extralec, n'hésitez pas à consulter <a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="<?= ROOT_PATH. '/public/doc/extralec.php'?>">la plaquette commerciale </a></li>
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

