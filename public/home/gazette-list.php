<?php foreach ($listGazette as $key => $gazette): ?>
	<div class="row">
		<div class="col">
			<?php if ($gazette['date_start']!=$gazetteDate): ?>
				<div class="row ">
					<div class="col">
						<h6 class="text-center text-main-blue"><?= ucfirst(FR_DAYS[date('w', strtotime($gazette['date_start']))]).' '.date('j', strtotime($gazette['date_start'])).' '.FR_MONTHS[date('n', strtotime($gazette['date_start']))].' '.date('Y',strtotime($gazette['date_start']))?></h6>
					</div>
				</div>
			<?php endif ?>
			<div class="row">
				<div class="col show-link">
					<div class="text-dark show-link" >- <?=$gazette['titre']?></div>
				</div>
				<div class="col-auto pr-5 mr-5 text-dark show-link" data-gazette-id="<?=$gazette['id']?>" ><u>ouvrir/fermer</u></div>
			</div>

			<div class="more"  data-content-id="<?=$gazette['id']?>">
				<div class="row">
					<div class="col">
						<?=$gazette['description']?>
					</div>
				</div>
				<div class="row mt-2">
					<div class="col">
						<div class="row border mx-1 py-3">
							<div class="col">
								<div class="font-weight-boldless text-grey">Fichiers :</div>

								<?php if (isset($listFiles[$gazette['id']])): ?>
									<?php foreach ($listFiles[$gazette['id']] as $key => $file): ?>
										<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><i class="fas fa-file pb-3"></i></a><br>

									<?php endforeach ?>

								<?php endif ?>
							</div>
							<div class="col">
								<div class="font-weight-boldless text-grey">Liens :</div>

								<?php if (isset($listLinks[$gazette['id']])): ?>
									<?php foreach ($listLinks[$gazette['id']] as $key => $link): ?>
										<a href="<?=$link['link']?>"><?=($link['linkname'])?$link['linkname']:'cliquez-ici'?></a><br>
									<?php endforeach ?>
								<?php endif ?>
							</div>

						</div>
					</div>
				</div>
			</div>
			<?php $gazetteDate=$gazette['date_start'] ?>
		</div>
	</div>
	<?php endforeach ?>