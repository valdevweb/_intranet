<div class="row pb-5">
	<div class="col">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<input type="hidden" class="form-control form-primary" name="code_op" value="<?=($_POST['code_op'])??$_POST['op']?>">
			<?php foreach ($listArticle as $key => $article): ?>

				<div class="row  alert alert-secondary text-main-blue font-weight-boldless">
					<div class="col">
						<?=$article['marque']?>
					</div>
					<div class="col">
						<?=$article['libelle']?>
					</div>
					<div class="col-lg-2">
						<?=$article['ean']?>
					</div>
					<div class="col-lg-2 text-right">
						<?=number_format($article['ppi'],2,'.',' ')?>&euro;
					</div>
				</div>

				<div class="row">
					<!-- <div class="col-lg-1"></div> -->
					<div class="col-lg-3">
						<div class="form-group">
							<label for="produit">Produit :</label>
							<input type="text" class="form-control form-primary" name="produit_gessica[<?=$article['id']?>]" value="<?=$article['libelle']?>">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="montant">Montant</label>
							<input type="text" class="form-control form-primary"  pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés" name="montant_gessica[<?=$article['id']?>]"  value="<?=($_POST['montant_gessica'][$article['id']])??""?>">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="montant_finance">Montant financé</label>
							<input type="text" class="form-control form-primary" name="montant_finance_gessica[<?=$article['id']?>]" pattern="^\d+(\.\d{1,2})?$" title="Seuls les chiffres sont autorisés" value="<?=($_POST['montant_finance_gessica'][$article['id']])??""?>">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="euro">Unité :</label>
							<select class="form-control form-primary" name="euro_gessica[<?=$article['id']?>]">
								<option value="1">Euro</option>
								<option value="0">Pourcentage</option>
							</select>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="offre">Offre</label>
							<select class="form-control form-primary" name="offre_gessica[<?=$article['id']?>]">
								<option value="">Sélectionner</option>
								<option value="1">BRII</option>
								<option value="2">TEL</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="cmt">Commentaire</label>
							<input type="text" class="form-control form-primary" name="cmt_gessica[<?=$article['id']?>]"  value="<?=($_POST['cmt_gessica'][$article['id']])??""?>">

						</div>
					</div>
				</div>
				<input type="hidden" class="form-control form-primary" name="marque_gessica[<?=$article['id']?>]" value="<?=$article['marque']?>">
				<input type="hidden" class="form-control form-primary" name="gt_gessica[<?=$article['id']?>]" value="<?=$article['gt']?>">
				<input type="hidden" class="form-control form-primary" name="ean_gessica[<?=$article['id']?>]" value="<?=$article['ean']?>">
				<input type="hidden" class="form-control form-primary" name="ppi_gessica[<?=$article['id']?>]" value="<?=$article['ppi']?>">
				<input type="hidden" class="form-control form-primary" name="reference_gessica[<?=$article['id']?>]" value="na">
			<?php endforeach ?>


			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="add_offre_gessica">Ajouter</button>
				</div>
			</div>
		</form>
	</div>
</div>