<div class="container-fluid bg-white">
	<?php if ($d_exploit): ?>
		<div class="row">
			<div class="col-s-12 col-m-4">
				<p class="bg-alert bg-alert-primary">
					<strong><?= $nbRecup['recup']?></strong> mots de passes récupérés sur <?= $nbCompte['compte']?> comptes
				</p>
			</div>
			<div class="col m8"></div>
		</div>
	<?php endif ?>
	<header>
		<h1 class="text-center text-main-blue pt-3"><?= $typeTitle .' '.$nom ?></h1>
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
	<?php if (UserHelpers::isUserAllowed($pdoUser,['94'])): ?>
		<div class="row">
			<div class="col-md-3 col-lg-4"></div>
			<div class="col light-shadow p-3 rounded-box">
				<p class="text-orange subtitle"><i class="fas fa-comment pr-3"></i>NOUVEAUX MESSAGES LITIGES :</p>
				<?php if (empty($newDialLitige)): ?>
					<p>Pas de nouveau message</p>
					<?php else: ?>
						<ul>
							<?php foreach ($newDialLitige as $key => $dial): ?>
								<li><?=$dial['nb'] .' nouveau(x) message(s) sur le dossier <a href="../litiges/bt-detail-litige.php?id='.$dial['id_dossier'].'">'.$dial['dossier']?></a></li>
							<?php endforeach ?>
						</ul>
					<?php endif ?>
				</div>
				<div class="col-md-3 col-lg-4"></div>
			</div>
		<?php endif ?>





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

							<ul class='links leaders'>
								<?php foreach ($listActiveOpp as $activeOpp): ?>

									<li>
										<span>
											<a class='stat-link' href="../gazette/opp-encours.php#<?=$activeOpp['id']?>"><?=$activeOpp['title'] ?></a>
											<?=($activeOpp['date_start']==date('Y-m-d') ||  $activeOpp['date_start']==(new DateTime('yesterday'))->format('Y-m-d')) ? "<span class='badge badge-warning ml-3'>Nouveau</span>" :""?>
										</span>

										<span class="text-dark-grey font-italic">fin de l'offre le  <?=date('d/m/Y', strtotime($activeOpp['date_end']))?></span>

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
									<?php if (!empty($revRes)): ?>
										<div class="info"><li class="orange-text text-darken-2"><i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Nouveaux documents déposés : </li></div><div class="detail orange-text text-darken-2">
											<?php foreach ($revRes as $key => $thisRev): ?>
												<?php if ($thisRev['id_type']==18): ?>
													<br> >> <?=$thisRev['divers'] . ' du ' .$thisRev['date_display']?>
													<?php else: ?>
														<br> >> <?=$thisRev['name'] . ' du ' .$thisRev['date_display']?>
													<?php endif ?>
												<?php endforeach ?>
											<?php endif ?>
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


