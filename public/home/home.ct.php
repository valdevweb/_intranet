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

	<div class="row pb-5 align-items-center">
		<div class="col-lg-1"></div>
		<div class="col  py-3 text-center  ">
			<img class="border border-secondary" src="../img/salon/salon2020.jpg">
		</div>
		<div class="col text-main-blue">
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


