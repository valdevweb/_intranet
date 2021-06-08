<div class="row" id="results">
	<div class="col">
		<h5 class="text-center text-main-blue mb-3">Résultats de votre recherche :</h5>
	</div>
</div>
<div class="row">
	<div class="col-lg-2"></div>
	<div class="col">
		<div class="alert alert-primary">
			<i class="fas fa-lightbulb pr-3"></i>Pour voir le contenu de la gazette, cliquez sur son titre. Pour refermer, cliquez à nouveau
		</div>
	</div>
	<div class="col-lg-2"></div>
</div>

<?php if (!empty($results)): ?>
	<?php $gazetteDate="" ?>
	<?php foreach ($results as $key => $gazette): ?>

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
					<div class="col show-link">
						<h6 class="text-main-blue show-link" data-gazette-id="<?=$gazette['id']?>"><?=$gazette['titre']?>
						<br><a class="text-small" href="#<?=$gazette['id']?>">Voir l'info</a>
					</h6>
				</div>
			</div>


			<div class="more" data-content-id="<?=$gazette['id']?>" id="<?=$gazette['id']?>">
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
								<?php if (isset($resultsFiles[$gazette['id']])): ?>
									<?php foreach ($resultsFiles[$gazette['id']] as $key => $file): ?>
										<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><?=($file['filename'])?$file['filename']:'<i class="fas fa-file pb-3"></i>'?></a><br>
									<?php endforeach ?>

								<?php endif ?>
							</div>
							<div class="col">
								<div class="font-weight-boldless text-grey">Liens :</div>

								<?php if (isset($resultsLink[$gazette['id']])): ?>

									<?php foreach ($resultsLink[$gazette['id']] as $key => $link): ?>
										<a href="<?=$link['link']?>"><?=($link['linkname'])?$link['linkname']:'cliquez-ici'?></a><br>
									<?php endforeach ?>
								<?php endif ?>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
			<?php $gazetteDate=$gazette['date_start'] ?>

<?php endforeach ?>













<?php else: ?>
	<div class="alert alert-danger">Aucun résultat à afficher pour votre recherche</div>
<?php endif ?>











</div>
</div>