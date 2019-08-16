			<?php
			// info nb de mdp récupéré => uniquement admin bt
			ob_start();
			?>

			<div class="container-fluid">
				<div class="row">
				 <div class="col s12 col m4">
						<p class="bg-alert bg-alert-primary">
							<strong><?= $nbRecup['recup']?></strong> mots de passes récupérés sur <?= $nbCompte['compte']?> comptes
						</p>
					</div>
					<div class="col m8"></div>

				</div>
			</div>
			<?php
			$infoExploit=ob_get_contents();
			ob_get_clean();
			if($d_exploit){
				echo $infoExploit;
			}
			?>




			<div class="container">

				<header>
					<h1 class="center grey-text text-darken-2"><?= $typeTitle .' '.$nom ?></h1>
				</header>
				<!-- encadré -->



				<?php
				include('../public/doc/flash-view.php');
				?>

				<br>

			<div class="down"></div>
			<section>
				<!-- titre des colonnes principales -->
				<div class="row">
					<div class="col s12 m5 l5">
						<h4 class="grey-text text-darken-2">Vos actualités de la semaine : </h4>
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

