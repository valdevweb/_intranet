<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
	<div class="row">
		<div class="col text-center">
			<h5 class="text-main-blue">Filtrer le tableau commandes en cours :</h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="fou">Fournisseur :</label>
				<select class="form-control form-primary" name="fou" id="fou">
					<option value="">Sélectionner</option>
					<?php foreach ($listFou as $keyFou => $fou): ?>
						<option value="<?=$listFou[$keyFou]?>" <?=isset($_SESSION['encours_filter']['fou'])?FormHelpers::restoreSelected($_SESSION['encours_filter']['fou'], $listFou[$keyFou]):""?>><?=$listFou[$keyFou]?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="marque">Marque :</label>
				<select class="form-control form-primary" name="marque" id="marque">
					<option value="">Sélectionner</option>
					<?php foreach ($listMarque as $keyMarque => $fou): ?>
						<option value="<?=$listMarque[$keyMarque]?>" <?=isset($_SESSION['encours_filter']['marque'])?FormHelpers::restoreSelected($_SESSION['encours_filter']['marque'], $listMarque[$keyMarque]):""?>><?=$listMarque[$keyMarque]?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="op">Opérations :</label>
				<select class="form-control form-primary" name="op[]" id="op" multiple>
					<option value="">Sélectionner</option>
					<?php foreach ($listOp as $keyOp => $fou): ?>
						<option value="<?=$listOp[$keyOp]?>" <?=isset($_SESSION['encours_filter']['op'])?FormHelpers::restoreSelectedArray($listOp[$keyOp],$_SESSION['encours_filter']['op']):""?>><?=$listOp[$keyOp]?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="num_cde">Commandes :</label>
				<select class="form-control form-primary" name="num_cde[]" id="num_cde" multiple>
					<option value="">Sélectionner</option>
					<?php foreach ($listNumCde as $keyCde => $fou): ?>
						<option value="<?=$listNumCde[$keyCde]?>" <?=isset($_SESSION['encours_filter']['num_cde'])?FormHelpers::restoreSelectedArray($listNumCde[$keyCde],$_SESSION['encours_filter']['num_cde']):""?>><?=$listNumCde[$keyCde]?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="1000" id="permanent" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1000)?"checked":""?>>
				<label class="form-check-label" for="permanent">Permanent</label>
			</div>
		</div>
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="1" id="op" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1)?"checked":""?>
				>
				<label class="form-check-label" for="permanent">Opérations</label>
			</div>
		</div>
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="9" id="permanent" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==9)?"checked":""?>
				>
				<label class="form-check-label" for="permanent">Permanent et opération</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3">
			<div class="form-group">
				<label for="date_start">Du :</label>
				<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=isset($_SESSION['encours_filter']['date_start'])?$_SESSION['encours_filter']['date_start']:""?>">
			</div>
		</div>
		<div class="col-lg-3">
			<div class="form-group">
				<label for="date_end">au :</label>
				<input type="date" class="form-control form-primary" name="date_end" id="date_end"  value="<?=isset($_SESSION['encours_filter']['date_end'])?$_SESSION['encours_filter']['date_end']:""?>">
			</div>
		</div>
		<div class="col-auto mt-4 pt-3">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="date_op" id="date_op" name="date_type"  <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_op")?"checked":""?>>
				<label class="form-check-label" for="date_op">dates d'opération</label>
			</div>
		</div>
		<div class="col-auto mt-4 pt-3">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="date_cde" id="date_cde" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_cde")?"checked":""?>>
				<label class="form-check-label" for="date_cde">dates de commandes</label>
			</div>
		</div>
		<div class="col-auto mt-4 pt-3">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="date_liv" id="date_liv" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_liv")?"checked":""?>>
				<label class="form-check-label" for="date_liv">dates de réception</label>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-auto">GTs :</div>
		<div class="col cols-four">
			<?php foreach ($listGt as $keyGt => $value): ?>
				<div class="form-check">
					<input class="form-check-input form-primary" type="checkbox" value="<?=$keyGt?>" id="<?=$listGt[$keyGt]?>" name="gt[]"  <?=isset($_SESSION['encours_filter']['gt'])?FormHelpers::restoreCheckedArray($keyGt,$_SESSION['encours_filter']['gt']):""?>>
					<label class="form-check-label gt-<?=$keyGt?>" for="<?=$listGt[$keyGt]?>"><?=ucfirst(strtolower($listGt[$keyGt]))?></label>
				</div>
			<?php endforeach ?>
		</div>
		<div class="col-lg-2"></div>

	</div>
	<div class="row mb-3">
		<div class="col"></div>
		<div class="col-auto text-right">
			<button class="btn btn-secondary" name="reset">Effacer les filtres</button>

		</div>
		<div class="col-auto text-right">
			<button class="btn btn-primary" name="filter">Filtrer</button>
		</div>
	</div>

</form>
