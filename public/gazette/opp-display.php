<?php
$listMainFiles=$oppDao->getListMainFiles($oppIds);
$listAddonsFiles=$oppDao->getListAddonsFiles($oppIds);
?>

<?php foreach ($listOpp as $key => $oneOpp): ?>

	<div class="row bg-blue rounded">
		<div class="col">
			<h2><?=mb_strtoupper($oneOpp['title'])?></h2>
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
							<p class="font-weight-bold">Salon : </p>
							<p class="font-weight-bold">Catalogue :</p>
							<p class="font-weight-bold">Date limite de remontée :</p>
							<p class="font-weight-bold">Dispo entrepôt :</p>


						</div>
						<div class="col">
							<p><?=$oneOpp['salon']?></p>
							<p><?=$oneOpp['cata']?></p>
							<p><?=date('d/m/Y', strtotime($oneOpp['date_end']))?></p>
							<p><?=isset($oneOpp['dispo']) ? $oneOpp['dispo']:''?></p>

						</div>
					</div>

				</div>
				<div class="nothing"></div>
			</div>

		</div>
		<div class="col-lg-2 col-xl-3"></div>
	</div>
	<div class="row">
		<div class="col">
			<?= isset($oneOpp['descr']) ? $oneOpp['descr'] : ""?>
		</div>
	</div>
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

	<?php endforeach ?>
