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

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>



	<?php
	include('../doc/flash-view.php');
	?>


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
					<P class="text-orange subtitle">LES OFFRES SPECIALES</p>

						<ul class='links'>
							<?php foreach ($listActiveOpp as $activeOpp): ?>

								<li>
									<a class='stat-link' href="../gazette/opp-encours.php#<?=$activeOpp['id']?>"><?=$activeOpp['title'] ?></a>
									<?=($activeOpp['date_start']==date('Y-m-d') ||  $activeOpp['date_start']==(new DateTime('yesterday'))->format('Y-m-d')) ? "<span class='badge badge-warning ml-3'>Nouveau</span>" :""?>

									<div class="text-dark-grey"><i class="fas fa-hourglass-half px-3"></i>fin de l'offre le  <?=date('d/m/Y', strtotime($activeOpp['date_end']))?></div>

								</li>
							<?php endforeach ?>

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
