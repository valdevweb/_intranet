<div class="bg-separation"></div>
<div class="row">
	<div class="col">
		<h3 class="text-main-blue text-center pt-4 pb-2" id="cde">Palettes commandées</h3>
	</div>
</div>


<?php if (!empty($paletteCommandees)): ?>
	<div class="row pb-2">
		<div class="col">
			<table class="table table-sm shadow table-striped borderless">
				<thead class="thead-dark">
					<tr>
						<th>Cde n°</th>

						<th>Magasin</th>
						<th>Date commande</th>
						<th>Détail</th>
						<th>Modifier</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach ($paletteCommandees as $key => $palette): ?>
						<tr>
							<td><?=$palette['id_cde']?></td>

							<td><?= UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $palette['id_web_user'], 'deno_sca')  ?></td>
							<td><?=$palette['date_insert']?></td>
							<td><div class="btn btn-primary detail-btn" data-btn-id="<?=$palette['id_cde']?>">Voir le détail</div></td>
							<td><a href="<?=$_SERVER['PHP_SELF'].'?expedier='.$palette['id_cde']?>" class="btn btn-primary">Expédier</a></td>
						</tr>

						<tr class="borderless">
							<td colspan="6">
								<?php

								$infoCde=getFullCde($pdoBt,$palette['id_cde']);

								?>

								<table class="table more" data-table-id="<?=$palette['id_cde']?>">


										<tr>
											<th>Palette</th>
											<th>EAN</th>
											<th>Désignation</th>
											<th>Quantité</th>
										</tr>


									<tbody>
										<?php foreach ($infoCde as $key => $cde): ?>
											<?php
											if(!empty($cde['id_palette'])){
												$article=$cde['code_article'];
												$designation=$cde['designation'];
												$ean=$cde['ean'];
												$qte=$cde['quantite'];
												$palette=$arrayListPalette[$cde['id_palette']];
											}else{
												$article=$cde['article_occ'];
												$designation=$cde['design_occ'];
												$ean=$cde['ean_occ'];
												$qte=$cde['qte_cde'];
												$palette="";

											}

											?>

											<tr>
												<td><?=$palette?></td>
												<td><?=$ean?></td>
												<td><?=$designation?></td>
												<td><?=$qte?></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top">Haut</a></div>
	</div>

	<?php else: ?>

		<div class="row">
			<div class="col">
				<div class="alert alert-primary">
					Aucune palette en commande
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col text-right"><a href="#top">Haut</a></div>
		</div>

		<?php endif ?>