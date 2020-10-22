<?php
$listMainFiles=$oppDao->getListMainFiles($oppIds);
$listAddonsFiles=$oppDao->getListAddonsFiles($oppIds);


$listIcons=$oppDao->getListIcons($oppIds);
$nbDoc=1;
?>

<?php foreach ($listOpp as $key => $oneOpp): ?>
	<div class="row border mb-5">
		<div class="col">
			<div class="row bg-grey rounded">
				<div class="col">
					<h2 id="<?=$oneOpp['id']?>"><?=mb_strtoupper($oneOpp['title'])?></h2>
				</div>
				<div class="col-2 text-right">
					<?=($oneOpp['gt']==1 ? 'MULTIMEDIA' : 'BLANC')?>

				</div>
			</div>
			<div class="row mt-5">
				<div class="col-lg-2 col-xl-3"></div>
				<div class="col">
					<div class="ribbon">
						<span class="ribbon2">I<br>N<br>F<br>O<br>S</span>
						<div class="nothing"><p>&nbsp;</p></div>
						<div class="detail">
							<div class="row">
								<div class="col-auto">
									<p class="font-weight-bold text-orange">Salon : </p>
									<p class="font-weight-bold text-orange">Catalogue :</p>
									<p class="font-weight-bold text-orange">Date limite de remontée :</p>
									<p class="font-weight-bold text-orange">Dispo entrepôt :</p>


								</div>
								<div class="col">
									<p class="text-secondary"><?=$oneOpp['salon']?></p>
									<p class="text-secondary"><?=$oneOpp['cata']?></p>
									<p class="text-secondary"><?=date('d/m/Y', strtotime($oneOpp['date_end']))?></p>
									<p class="text-secondary"><?=isset($oneOpp['dispo']) ? $oneOpp['dispo']:''?></p>

								</div>
							</div>

						</div>
						<div class="nothing"></div>
					</div>
				</div>


				<div class="col-lg-2 col-xl-3"></div>
			</div>

			<?php if (isset($oneOpp['descr']) ||isset($listIcons[$oneOpp['id']])): ?>

			<div class="row mt-5">
				<div class="col">
						<h5 class="font-weight-bold text-center text-descr"><i class="fas fa-info-circle pr-2"></i>Informations</h5>
				</div>
			</div>
			<?php endif ?>

			<?php if (isset($oneOpp['descr']) && !empty($oneOpp['descr'])): ?>
				<div class="row">
					<div class="col-xl-1"></div>
					<div class="col">
						<div class="descr ">
							<?= nl2br($oneOpp['descr'])?>
						</div>
					</div>
					<div class="col-xl-1"></div>

				</div>
			<?php endif ?>
			<?php if (isset($listIcons[$oneOpp['id']])): ?>

				<!-- <div class="col-xl"> -->
					<div class="row justify-content-center mt-1 pb-3">
						<!-- <div class="col"></div> -->
						<?php if (in_array(0,$listIcons[$oneOpp['id']])): ?>
							<div class="col-auto text-center">
								<img src="../img/opp/new-orange-100.png">
							</div>
						<?php endif ?>
						<?php if (in_array(1,$listIcons[$oneOpp['id']])): ?>
							<div class="col-auto text-center">
								<img src="../img/opp/tel-120.png">
							</div>

						<?php endif ?>
						<?php if (in_array(2,$listIcons[$oneOpp['id']])): ?>
							<div class="col-auto text-center">
								<img src="../img/opp/brii-120.png">
							</div>
						<?php endif ?>
						<?php if (in_array(3,$listIcons[$oneOpp['id']])): ?>
							<div class="col-auto text-center">
								<img src="../img/opp/odr-120.png">
							</div>
						<?php endif ?>
						<!-- <div class="col"></div> -->

					</div>
					<!-- </div> -->

				<?php endif ?>

				<div class="row mt-5">
					<div class="col">
						<?php if (isset($listMainFiles[$oneOpp['id']])): ?>
							<?php foreach ($listMainFiles[$oneOpp['id']] as $key => $mainFile): ?>
								<?php if ($mainFile['image']==1): ?>
									<div class="row">
										<div class="col">
											<p><img class="shadow img-fluid" src="<?=URL_UPLOAD_OPP.$mainFile['filename']?>"></p>
										</div>
									</div>
									<?php else: ?>
										<div class="row">
											<div class="col">
												<p><a href="<?=URL_UPLOAD_OPP.$mainFile['filename']?>">Ouvrir / télécharger le fichier de l'opportunité</a></p>
											</div>
										</div>
									<?php endif ?>
								<?php endforeach ?>
							<?php endif ?>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col">
							<?php if (!empty($listAddonsFiles[$oneOpp['id']])): ?>
								<p>Documents techniques :</p>
								<ul class="doc-tech">
									<?php foreach ($listAddonsFiles[$oneOpp['id']] as $key => $addons): ?>
										<li>
											<a href="<?=URL_UPLOAD_OPP.$addons['filename']?>" target="_blank">
												<?= (!empty($addons['name']))? $addons['name'] : 'documentation technique '.$nbDoc?>
											</a>
										</li>
										<?php $nbDoc++ ?>
									<?php endforeach ?>
								</ul>
							<?php endif ?>
						</div>
					</div>
					<div class="row">
						<div class="col text-right">
							<a class="bg-grey px-1" href="#cssmenu" ><i class="fas fa-arrow-up"></i></a>
						</div>
					</div>
				</div>
			</div>

		<?php endforeach ?>
