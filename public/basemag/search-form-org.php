		<div class="col-3 justify-content-end d-flex">
			<?php if (isset($searchMags) && !empty($searchMags)): ?>
			<div id="result-zone" class="justify-content-end py-3 px-5">
				<div class="text-blue font-weight-bold yanone">Cliquez sur le nom du magasin pour aller sur sa fiche :</div>
				<?php foreach ($searchMags as $searchMag): ?>
					<div class="karla">
						<a href="<?=$_SERVER['PHP_SELF']?>?galec=<?=$searchMag->getGalec()?>"><?=ucfirst(strtolower($searchMag->getDeno())) . ' - '.ucfirst(strtolower($searchMag->getVille()))?></a><br>
					</div>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>
	<div class="col-auto pt-5">
		<form method="post" action="">
			<div class="search justify-content-end">
				<input type="text" id="search-term" placeholder="Rechercher un magasin" name="search_term">
				<button type="submit" class="search-button" name="search_form">
					<i class="fa fa-search"></i>
				</button>
				<button name="clear" id="clear"><i class="fas fa-times-circle fa-2x pt-1 pl-2 text-main-blue"></i></button>
			</div>
		</form>

	</div>

