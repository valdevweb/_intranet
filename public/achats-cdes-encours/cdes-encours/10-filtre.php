<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
	<div class="row">
		<div class="col text-center">
			<h5 class="text-main-blue">Filtrer le tableau commandes en cours :</h5>
		</div>
	</div>
	<!-- line 1 -->
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
	<?php if (isset($listCdesByFou) && !empty($listCdesByFou)): ?>
		<div class="row">
			<div class="col"></div>
			<div class="col-3">
				<div class="form-group">
					<label for="other_num_cde">Autres commandes <?=$_SESSION['encours_filter']['fou']?> :</label>
					<select class="form-control form-primary" name="other_num_cde[]" id="other_num_cde" multiple>
						<option value="">Sélectionner</option>
						<?php foreach ($listCdesByFou as $keyCde => $fou): ?>
							<option value="<?=$fou['id_cde']?>" <?=isset($_SESSION['encours_filter']['num_cde'])?FormHelpers::restoreSelectedArray($listCdesByFou[$keyCde],$_SESSION['encours_filter']['num_cde']):""?>><?=$fou['id_cde']?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
	<?php endif ?>

	<!-- line 3 -->
	<div class="row">
		<div class="col-6 text-main-blue">
			<div class="row">
				<div class="col">
					Rechercher un  :
					<div class="row">
						<div class="col-auto">
							<div class="form-check">
								<input class="form-check-input" type="radio" value="id_cde" id="num_cde_str" name="type_of_strg" <?=(isset($_SESSION['encours_filter']['type_of_strg']) && $_SESSION['encours_filter']['type_of_strg']=="id_cde")?"checked":""?>>
								<label class="form-check-label" for="num_cde_str">Numéro de commande</label>
							</div>
						</div>
						<div class="col-auto">
							<div class="form-check">
								<input class="form-check-input" type="radio" value="ref" id="ref" name="type_of_strg" <?=(isset($_SESSION['encours_filter']['type_of_strg']) && $_SESSION['encours_filter']['type_of_strg']=="ref")?"checked":""?>>
								<label class="form-check-label" for="ref">Référence</label>
							</div>
						</div>
						<div class="col-auto">
							<div class="form-check">
								<input class="form-check-input" type="radio" value="dossier" id="dossier" name="type_of_strg" <?=(isset($_SESSION['encours_filter']['type_of_strg']) && $_SESSION['encours_filter']['type_of_strg']=="dossier")?"checked":""?>>
								<label class="form-check-label" for="dossier">Dossier</label>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<div class="form-group">
								<input type="text" class="form-control form-primary" name="search_strg" id="search_strg" placeholder="texte recherché" value="<?=isset($_SESSION['encours_filter']['search_strg'])?FormHelpers::restoreValue($_SESSION['encours_filter']['search_strg']):""?>">
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col text-main-blue">
			<div class="row">
				<div class="col">
					Filtrer par dates :
				</div>
			</div>
			<div class="row">
				<div class="col-auto">
					<div class="form-check">
						<input class="form-check-input" type="radio" value="date_op" id="date_op" name="date_type"  <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_op")?"checked":""?>>
						<label class="form-check-label" for="date_op">d'opération</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check">
						<input class="form-check-input" type="radio" value="date_cde" id="date_cde" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_cde")?"checked":""?>>
						<label class="form-check-label" for="date_cde">de commandes</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check">
						<input class="form-check-input" type="radio" value="date_liv" id="date_liv" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_liv")?"checked":""?>>
						<label class="form-check-label" for="date_liv">de réception</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=isset($_SESSION['encours_filter']['date_start'])?$_SESSION['encours_filter']['date_start']:""?>">
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<input type="date" class="form-control form-primary" name="date_end" id="date_end"  value="<?=isset($_SESSION['encours_filter']['date_end'])?$_SESSION['encours_filter']['date_end']:""?>">
					</div>
				</div>
			</div>

		</div>
	</div>

	<!-- line 4 -->

	<div class="row">
		<div class="col">
			<div class="row">

				<div class="col-auto">GTs :</div>
				<div class="col cols-four">
					<?php foreach ($listGt as $keyGt => $value): ?>
						<div class="form-check">
							<input class="form-check-input form-primary" type="checkbox" value="<?=$keyGt?>" id="<?=$listGt[$keyGt]?>" name="gt[]"  <?=isset($_SESSION['encours_filter']['gt'])?FormHelpers::restoreCheckedArray($keyGt,$_SESSION['encours_filter']['gt']):""?>>
							<label class="form-check-label" for="<?=$listGt[$keyGt]?>"><?=ucfirst(strtolower($listGt[$keyGt]))?></label>
						</div>
					<?php endforeach ?>
				</div>
				<div class="col"></div>
			</div>
		</div>
		<div class="col">
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-secondary" name="reset">Effacer les filtres</button>

				</div>
				<div class="col-auto text-right">
					<button class="btn btn-primary" name="filter">Filtrer</button>
				</div>
			</div>
		</div>

	</div>
	<!-- line 5 -->
	<div class="row">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
	</div>

	<?php
// include "10-removed.php"
	?>




</form>
