<div class="row">
	<div class="col border p-5">
		<?php if (isset($offreMod) && !empty($offreMod)): ?>

		<div class="row my-3" id="modif-offre">
			<div class="col">
				<h6 class="text-main-blue">Modifier l'offre</h6>
			</div>
		</div>
		<div class="row" >
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?offre-modif='.$_GET['offre-modif']?>" method="post">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="id_prosp">Prospectus</label>
								<select class="form-control form-primary" name="id_prosp" id="id_prosp">
									<option value="">Sélectionner</option>
									<?php foreach ($listProsp as $key => $prosp): ?>
										<option value="<?=$prosp['id']?>" <?= FormHelpers::restoreSelected($prosp['id'],$offreMod['id_prosp'])?>>
											<?=$prosp['prospectus']?>

											</option>
									<?php endforeach ?>

								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="marque">Marque</label>
								<input type="text" class="form-control form-primary" name="marque" id="marque" value="<?=$offreMod['marque']?>">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="produit">Produit</label>
								<input type="text" class="form-control form-primary" name="produit" id="produit" value="<?=$offreMod['produit']?>">
							</div>
						</div>
						<div class="col-lg-2">
							<div class="form-group">
								<label for="reference">Référence</label>
								<input type="text" class="form-control form-primary" name="reference" id="reference" value="<?=$offreMod['reference']?>">
							</div>
						</div>
						<div class="col-lg-1">
							<div class="form-group">
								<label for="gt">GT</label>
								<input type="text" class="form-control form-primary" name="gt" value="<?=$offreMod['gt']?>" title="Veuillez saisir le numéro de GT" id="gt" pattern="^(?:[1-9]|0[1-9]|10)$">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="ean">EAN :</label>
								<input type="text" class="form-control form-primary" name="ean" id="ean" value="<?=$offreMod['ean']?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="pvc">Prix de vente conseillé</label>
								<input type="text" class="form-control form-primary" name="pvc" id="pvc" value="<?=$offreMod['pvc']?>" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="montant">Montant</label>
								<input type="text" class="form-control form-primary"  value="<?=$offreMod['montant']?>" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés" name="montant" id="montant">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="montant_finance">Montant financé</label>
								<input type="text" class="form-control form-primary" name="montant_finance" value="<?=$offreMod['montant_finance']?>" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés"  id="montant_finance">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="offre">Offre</label>
								<select class="form-control form-primary" name="offre" id="offre">
									<option value="">Sélectionner</option>
									<option value="1" <?= FormHelpers::restoreSelected(1,$offreMod['offre'])?>>BRII</option>
									<option value="2" <?= FormHelpers::restoreSelected(2,$offreMod['offre'])?>>TEL</option>
								</select>
							</div>

						</div>

					</div>
					<div class="row pb-5">
						<div class="col text-right"><button class="btn btn-primary" name="update_offre">Modifier</button></div>
					</div>
				</form>
			</div>
		</div>
		<?php else: ?>
			<div class="alert alert-danger">Ce prospectus n'existe pas</div>
		<?php endif ?>

	</div>
</div>