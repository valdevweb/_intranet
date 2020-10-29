<div class="row">
	<div class="col">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
			<div class="row mt-3">
				<div class="col-auto">
					<div class="text-main-blue font-weight-bold">Rechercher dans la table : </div>
					<div class="form-check mt-3">
						<input class="form-check-input" type="radio" value="1" id="cde_mag1" name="cde_mag" required>
						<label class="form-check-label" for="cde_mag1"><span class="font-weight-bold">litige :</span> l'article été commandé par le magasin déclarant le litige et n'est pas un article de palette occasion</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" value="2" id="cde_mag2" name="cde_mag">
						<label class="form-check-label" for="cde_mag2"><span class="font-weight-bold">base article :</span> l'article n'a pas été commandé par le magasin déclarant)</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" value="3" id="cde_mag3" name="cde_mag">
						<label class="form-check-label" for="cde_mag1"><span class="font-weight-bold">palettes d'occasion :</span> l'article provient d'une palette d'occasion</label>
					</div>

				</div>


			</div>
			<div class="row mt-3">
				<div class="col">
					<div class="form-group">
						<label class="text-main-blue font-weight-bold" for="article">Saisir le code de l'article ou l'EAN à chercher :</label>
						<input type="text" class="form-control" placeholder="EAN ou code article" name="article" id="article">
					</div>
				</div>
				<div class="col mt-4 pt-2">
					<button type="submit" name="search_2" class="btn btn-primary">Rechercher</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php if (isset($listArticles)): ?>
	<?php if (empty($listArticles)): ?>
		<div class="alert alert-danger">Aucun article n'a été trouvé</div>
		<?php else: ?>
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">

				<div class="row">
					<div class="col">
						Articles trouvés dans la base :<br>
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th>EAN</th>
									<th>Article</th>
									<th>Dossier</th>
									<th>Libellé</th>
									<th>Tarif</th>
									<th>Qte cde</th>
									<th>Qte litige</th>
									<th>Reclamation</th>
									<th>Action</th>

								</tr>
							</thead>
							<tbody>

								<?php foreach ($listArticles as $keyArticles => $article): ?>
									<tr>
										<td>
											<?=$article['gencod']?>
											<input type="hidden" class="form-control" name="ean[]" id="ean" value="<?=$article['gencod']?>">
										</td>
										<td>
											<?=isset($article['article'])? $article['article']: ""?>
											<?=isset($article['article_palette'])? $article['article_palette']: ""?>
											<input type="hidden" class="form-control" name="article[]" id="article" value="<?=isset($article['article'])? $article['article']: ""?>">
										</td>
										<td>
											<?=isset($article['dossier'])? $article['dossier']: ""?>
											<input type="hidden" class="form-control" name="dossier[]" id="dossier" value="<?=isset($article['dossier'])? $article['dossier']: ""?>">
										</td>
										<td>
											<?=$article['libelle']?>
											<input type="hidden" class="form-control" name="descr[]" id="descr" value="<?=$article['libelle']?>">
										</td>
										<td>
											<?php $tarif=(isset($article['panf']))? $article['panf']* $article['pcb'] :$article['tarif']?>
											<?=$tarif?>

											<input type="hidden" class="form-control" name="tarif[]" id="tarif" value="<?=$tarif?>">
										</td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control mini-input" name="qte_cde[]" value="<?=isset($article['qte'])? $article['qte']:""?>">
											</div>
										</td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control mini-input" name="qte_litige[]" id="qte_litige">
											</div>
										</td>
										<td>
											<div class="form-group">
												<select class="form-control" name="id_reclamation[]" id="id_reclamation">
													<option value="">sélectionnez</option>
													<?php foreach ($listReclamationsEdit as $key => $reclam): ?>
														<option value="<?=$key?>">
															<?=$listReclamations[$key]?>
														</option>
													<?php endforeach ?>
												</select>
											</div>

										</td>
										<td>
											<button type="submit" class="btn btn-primary" name="add_article[<?=$keyArticles?>]">Ajouter</button>
											<input type="hidden" class="form-control" name="dossier_litige[]"  value="<?=$detailLitige[0]['dossier']?>">
											<input type="hidden" class="form-control" name="id_litige[]" value="<?=$detailLitige[0]['id_dossier']?>">
											<input type="hidden" class="form-control" name="fournisseur[]" value="<?=isset($article['fournisseur'])?$article['fournisseur']:""?>">
											<input type="hidden" class="form-control" name="cnuf[]" value="<?=isset($article['cnuf'])?$article['cnuf']:""?>">
											<?php if ($_POST['cde_mag']==1): ?>

												<input type="hidden" class="form-control" name="facture[]" value="<?=$article['facture']?>">
												<input type="hidden" class="form-control" name="palette[]" value="<?=$article['palette']?>">
												<input type="hidden" class="form-control" name="date_facture[]" value="<?=$article['date_mvt']?>">
											<?php endif ?>
											<?php if ($_POST['cde_mag']==3): ?>

												<input type="hidden" class="form-control" name="facture[]" value="<?=$detailLitige[0]['facture']?>">
												<input type="hidden" class="form-control" name="date_facture[]" value="<?=$detailLitige[0]['date_facture']?>">
												<input type="hidden" class="form-control" name="occ_article_palette[]" value="<?=$article['article_palette']?>">
											<?php endif ?>


										</td>
									</tr>
								<?php endforeach ?>

							</tbody>
						</table>
					</div>
				</div>
			<?php endif ?>
		</form>
		<?php endif ?>