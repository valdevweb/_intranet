<div class="container-fluid bg-white">



	<?php ob_start();?>
	<div class="row">
		<div class="col-s-12 col-m-4">
			<p class="bg-alert bg-alert-primary">
				<strong><?= $nbRecup['recup']?></strong> mots de passes récupérés sur <?= $nbCompte['compte']?> comptes
			</p>
		</div>
		<div class="col m8"></div>
	</div>
	<?php
	// info nb de mdp récupéré => uniquement admin bt
	$infoExploit=ob_get_contents();
	ob_get_clean();
	if($d_exploit){
		echo $infoExploit;
		$marge="";
	}
	else{
		$marge='pt-3';
	}
	?>

	<header>
		<h1 class="text-center text-main-blue <?= $marge ?>"><?= $typeTitle .' '.$nom ?></h1>
	</header>
	<?php
	include('../doc/flash-view.php');
	?>
	<div class="row">

		<div class="col  py-3">
			<h4 class="text-center text-danger font-weight-bold">BTLec EST continue de travailler et d'assurer les livraisons</h4>
			<br><br>
			<h4 class="text-center text-danger font-weight-bold">Restrictions livraisons 48h TNT - Région Grand Est</h4>

				<div class="row">
					<div class="col-lg-2"></div>
					<div class="col border p-3">
						Aux vues des circonstances actuelles exceptionnelles, TNT est contraint de restreindre ses services, avec effet immédiat, dans la région grand Est.<br>

						En conséquence, à compter du 19 mars 2020 et jusqu’à nouvel ordre, <strong>TNT suspend ses services de ramassage et livraison, dans les sites et départements suivants, pour tous clients n’appartenant pas aux secteurs Santé et Alimentaire :</strong><br>
						<span class="pl-5 ml-5">- Bas-Rhin (67)<br></span>
						<span class="pl-5 ml-5">- Côte-d’Or (21)<br></span>
						<span class="pl-5 ml-5">- Doubs (25)<br></span>
						<span class="pl-5 ml-5">- Jura (39)<br></span>
						<span class="pl-5 ml-5">- Haute-Marne (52)<br></span>
						<span class="pl-5 ml-5">- Haut-Rhin (68)<br></span>
						<span class="pl-5 ml-5">- Haute-Saône (70)<br></span>
						<span class="pl-5 ml-5">- Meuse (55)<br></span>
						<span class="pl-5 ml-5">- Moselle (57)<br></span>
						<span class="pl-5 ml-5">- Saône-et-Loire (71)<br></span>
						<span class="pl-5 ml-5">- Territoire de Belfort (90)<br></span>

						Par conséquent toutes les livraisons 48h00 sur les départements ci-dessus sont suspendues.<br>

						Si toutefois nous recevons une commande 48h00 celle-ci sera rajoutée à votre prochaine livraison de permanent.<br>

						Nous vous informons également pour les autres départements que si un colis n'est pas réceptionné à la 1ière présentation de TNT, celui-ci revient désormais d'office sur BTLec et sera rajouté à votre prochaine livraison de permanent. Il n'y a plus aucun 2ième présentation.<br>
					</div>
					<div class="col-lg-2"></div>

				</div>




		</div>
	</div>
	<div class="row">
		<div class="col">
		</div>
	</div>

	<div class="row pb-5 align-items-center">
		<div class="col-lg-1"></div>
		<div class="col  py-3 text-center  ">
			<img class="border border-secondary" src="../img/salon/salon2020.jpg">
		</div>
		<div class="col text-main-blue">
			<?php if($_SESSION['id_web_user']==1102): ?>
				<h1 class="text-main-blue">
				SALON 2020</h1>
				<h4>Le salon bazar Technique BTLec Est aura lieu les 9 et 10 juin 2020.</h4>
				<h4 class="text-center">Les inscriptions sont ouvertes !!</h4>
				<div class="text-center" ><a href="../salon/inscription-2020.php"><button class="btn btn-orange" >S'INSCRIRE AU SALON</button></a></div>
				<?php else: ?>

					<h1 class="jump">
						<span>S</span>
						<span>A</span>
						<span>L</span>
						<span>O</span>
						<span>N</span>
						<span>&nbsp;</span>
						<span>2</span>
						<span>0</span>
						<span>2</span>
						<span>0</span>
					</h1>
					<h4>Le salon bazar Technique BTLec Est aura lieu les 9 et 10 juin 2020.</h4>
					<h4 class="text-center">Les inscriptions sont ouvertes !!</h4>
					<div class="text-center" ><a href="../salon/inscription-2020.php"><button class="glow-on-hover" >S'INSCRIRE AU SALON</button></a></div>
				<?php endif ?>


			</div>
			<div class="col-lg-1"></div>

		</div>

		<div class="row pb-5">
			<div class="col-lg-1"></div>

			<div class="col">
				<div class="row p-3">
					<div class="col-sm-12 col-lg light-shadow p-3 rounded-box mr-3">
						<div class="text-center pb-3">
							<img src="../img/home/home-gazette-o.png">
						</div>
						<p class="text-orange subtitle">LES GAZETTES DE LA SEMAINE :</p>
						<ul class='links'>
							<?php foreach ($links as $link): ?>
								<?= $link ?>
							<?php endforeach ?>
						</ul>
						<?php if(isset($speHtml)): ?>
							<p class="text-orange subtitle">LA GAZETTE SPECIALE :</p>
							<ul class='links'>
								<?= $speHtml?>
							</ul>
						<?php endif	?>
						<p class="text-orange subtitle">LES GAZETTES SUIVI LIVRAISON CATALOGUE :</p>
						<ul class='links'>
							<?= isset($approHtml)? $approHtml: ''?>
						</ul>
						<P class="text-orange subtitle">LES ALERTES PROMOS</p>

							<ul class='links'>
								<li>
									<a class='stat-link' data-user-session="<?= $_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank"><i class="fas fa-external-link-alt pr-3"></i>voir les alertes promos</a>
								</li>
							</ul>
						</div>
						<div class="col-sm-12 col-lg light-shadow rounded-box p-3">
							<div class="text-center pb-3">
								<img src="../img/home/home-links-o.png">
							</div>
							<div class="vm-card white">
								<p class="text-orange subtitle">DOCUBASE :</p>
								<ul class='links'>
									<li>Retrouvez vos factures, reversement, bon livraison, etc<br>
										<a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="http://172.30.101.66/rheaweb/auth"><i class="fas fa-external-link-alt pr-3"></i>Docubase</a>
									</li>
									<?php echo isset($infoRev) ? $infoRev:"";?>
								</ul>
								<p class="text-orange subtitle">Le portail EXTRALEC :</p>
								<ul class='links'>
									<li>Prenez en main rapidement et facilement les applications de l'univers Extralec grâce au portail (guide d'installation , formation, assistance technique) et administrez vos comptes utilisateurs.<br><a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="https://www.extralecbtlec.fr/"><i class="fas fa-external-link-alt pr-3"></i>Portail Extralec</a><br><br></li>
									<li>Si vous ne connaissez pas bien Extralec, n'hésitez pas à consulter <a class="simple-link stat-link" data-user-session="<?= $_SESSION['user']?>"  href="<?= ROOT_PATH. '/public/doc/extralec.php'?>">la plaquette commerciale </a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-1"></div>
			</div>
		</div>


