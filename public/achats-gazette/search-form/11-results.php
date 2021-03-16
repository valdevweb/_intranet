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
	<div class="row mb-5">
		<div class="col">


<?php if (!empty($results)): ?>
	<?php foreach ($results as $key => $gazette): ?>
		<div class="row">
			<div class="col-lg-2"><?=date('d-m-Y', strtotime($gazette['date_start']))?></div>
			<div class="col-lg-1">
				<div class="badge badge-<?=($mainCat[$gazette['main_cat']])??""?>"><?=(strtoupper($mainCat[$gazette['main_cat']]))??""?></div>
			</div>
			<div class="col-lg-2">
				<?=(ucwords($listCat[$gazette['cat']]))??""?>
			</div>
			<div class="col">
				<h6 class="text-main-blue show-link" data-gazette-id="<?=$gazette['id']?>"><?=$gazette['titre']?></h6>
			</div>
		</div>
		<div class="row more" data-content-id="<?=$gazette['id']?>">
			<div class="col-lg-5"></div>
			<div class="col">
				<?=$gazette['description']?>
				<?php if (isset($resultsFiles[$gazette['id']])): ?>
					<br>Fichiers : <br>
					<?php foreach ($resultsFiles[$gazette['id']] as $key => $file): ?>
						<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><?=($file['filename'])??'<i class="fas fa-file pb-3"></i>'?></a><br>

					<?php endforeach ?>
				<?php endif ?>
				<?php if (isset($resultsLink[$gazette['id']])): ?>
					<br>Liens :<br>
					<?php foreach ($resultsLink[$gazette['id']] as $key => $link): ?>
						<a href="<?=$link['link']?>"><?=($link['linkname'])??$link['link']?></a><br>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>
	<?php endforeach ?>
	<?php else: ?>
		<div class="alert alert-danger">Aucun résultat à afficher pour votre recherche</div>
		<?php endif ?>
				</div>
	</div>