
<?php if (!empty($listProsp)): ?>
	<div class="row" id="add-offre">
		<div class="col">
			<div class="alert alert-primary">Les montants et le PVC doivent être saisis avec des points pour les décimales et sans le sigle euro<br>Exemple : 10.51, 55, etc</div>
		</div>
	</div>
	<div class="row" >
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="id_prosp">Prospectus</label>
							<select class="form-control form-primary" name="id_prosp" id="id_prosp">
								<option value="">Sélectionner</option>
								<?php foreach ($listProsp as $key => $prosp): ?>
									<option value="<?=$prosp['id']?>"><?=$prosp['prospectus']?></option>
								<?php endforeach ?>

							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="marque">Marque</label>
							<input type="text" class="form-control form-primary" name="marque" id="marque" value="<?=($_POST['marque'])??""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="produit">Produit</label>
							<input type="text" class="form-control form-primary" name="produit" id="produit" value="<?=($_POST['produit'])??""?>">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="reference">Référence</label>
							<input type="text" class="form-control form-primary" name="reference" id="reference" value="<?=($_POST['reference'])??""?>">
						</div>
					</div>
					<div class="col-lg-1">
						<div class="form-group">
							<label for="gt">GT</label>
							<input type="text" class="form-control form-primary" name="gt" title="Veuillez saisir le numéro de GT" id="gt" pattern="^(?:[1-9]|0[1-9]|10)$" value="<?=($_POST['gt'])??""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="ean">EAN :</label>
							<input type="text" class="form-control form-primary" name="ean" id="ean" value="<?=($_POST['ean'])??""?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="pvc">Prix de vente conseillé</label>
							<input type="text" class="form-control form-primary" name="pvc" id="pvc" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés" value="<?=($_POST['pvc'])??""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="montant">Montant</label>
							<input type="text" class="form-control form-primary"  pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés" name="montant" id="montant" value="<?=($_POST['montant'])??""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="montant_finance">Montant financé</label>
							<input type="text" class="form-control form-primary" name="montant_finance" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés"  id="montant_finance" value="<?=($_POST['montant_finance'])??""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="offre">Offre</label>
							<select class="form-control form-primary" name="offre" id="offre">
								<option value="">Sélectionner</option>
								<option value="1">BRII</option>
								<option value="2">TEL</option>
							</select>
						</div>

					</div>

				</div>
				<div class="row pb-5">
					<div class="col text-right"><button class="btn btn-primary" name="add_offre">Ajouter</button></div>
				</div>
			</form>
		</div>
	</div>

	<?php else: ?>
		<div class="alert alert-danger">Pour ajouter des offres, vous devez au préalable créer le prospectus auquel celles-ci se rattachent</div>
		<?php endif ?>