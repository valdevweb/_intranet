<?php foreach ($listGazette as $key => $gazette): ?>
	<div class="row mb-3">
		<div class="col">
			<?php if ($gazette['date_start']!=$gazetteDate): ?>
				<div class="bg-separation-thin"></div>
				<div class="row my-3">
					<div class="col">
						<h5 class="text-center text-main-blue">la gazette du <?=FR_DAYS[date('w', strtotime($gazette['date_start']))].' '.date('j', strtotime($gazette['date_start'])).' '.FR_MONTHS[date('n', strtotime($gazette['date_start']))]?></h5>
					</div>
				</div>
			<?php endif ?>
			<div class="row">
				<div class="col-lg-1">
					<div class="badge badge-<?=($mainCat[$gazette['main_cat']])??""?>">
						<?=(strtoupper($mainCat[$gazette['main_cat']]))??""?>

					</div>
				</div>
				<div class="col-lg-2">
					<?=(ucwords($listCat[$gazette['cat']]))??""?>
				</div>
				<div class="col">
					<h6 class="text-main-blue"><?=$gazette['titre']?></h6>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3"></div>
				<div class="col">
					<?=$gazette['description']?>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-lg-3"></div>
				<div class="col">
					<div class="row border mx-1 py-3">
						<div class="col">
							<div class="font-weight-boldless text-grey">Fichiers :</div>

							<?php if (isset($listFiles[$gazette['id']])): ?>
								<?php foreach ($listFiles[$gazette['id']] as $key => $file): ?>
									<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><?=($file['filename'])??'<i class="fas fa-file pb-3"></i>'?></a><br>

								<?php endforeach ?>

							<?php endif ?>
						</div>
						<div class="col">
							<div class="font-weight-boldless text-grey">Liens :</div>

							<?php if (isset($listLinks[$gazette['id']])): ?>
								<?php foreach ($listLinks[$gazette['id']] as $key => $link): ?>
									<a href="<?=$link['link']?>"><?=($link['linkname'])??$link['link']?></a><br>
								<?php endforeach ?>
							<?php endif ?>
						</div>

					</div>
				</div>
			</div>

			<?php $gazetteDate=$gazette['date_start'] ?>
		</div>
	</div>
	<?php endforeach ?>