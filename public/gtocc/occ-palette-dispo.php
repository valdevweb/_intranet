	<div class="bg-separation"></div>
	<div class="row">
		<div class="col">
			<h3 class="text-main-blue text-center pt-4 pb-2" id="over">Palettes disponibles à la commande</h3>
		</div>
	</div>


	<?php

	// echo "<pre>";
	// print_r($paletteCommandable);
	// echo '</pre>';


	?>


	<?php if (!empty($paletteCommandable)): ?>
		<?php foreach ($paletteCommandable as $key => $palette): ?>
			<?php
			$montantPalette=0 ;
			$nbPaletteDispo=$paletteMgr->getNbPaletteDispo($palette[0]['id_import']);
			?>

			<!-- 1er élement du lot -->
			<?php if($importW!=$palette[0]['id_import']): ?>
				<div class="row mb-3">
					<div class="col">
						<h5 class="text-main-blue">Lot du <?=date('d/m/Y', strtotime($palette[0]['date_import']))?> : <?=$nbPaletteDispo?> palettes restantes</h5>
					</div>

					<div class="col">
						<a href="#<?=$palette[0]['id_import']?>" class="btn btn-primary lot" data-lot-id="<?=$palette[0]['id_import']?>">Voir les palettes restantes</a>
					</div>
				</div>

				<?php if (!empty($palette[0]['cmt']) && date('Y-m-d')<=$palette[0]['date_end']): ?>
				<div class="row">
					<div class="col-1"></div>
					<div class="col">
						<div class="alert alert-danger">
							<b>Attention sur ce lot  : </b><br>
							<?=$palette[0]['cmt']?>
						</div>
					</div>
					<div class="col-1"></div>

				</div>
			<?php endif ?>
		<?php endif ?>
		<?php $importW=$palette[0]['id_import']; ?>

		<div class="row ">
			<div class="col detail-palette" data-lot-list="<?=$palette[0]['id_import']?>">
				<div class="row my-3" id="detailPalette">
					<div class="col">
						<h5 class="text-main-blue text-center">Palette <?=$palette[0]['palette'] . ' - lot du '.date('d/m/Y', strtotime($palette[0]['date_import'])) ?></h5>
					</div>
				</div>

				<div class="row pb-2">
					<div class="col-1"></div>
					<div class="col">
						<table class="table table-sm shadow">
							<thead class="thead-dark">
								<tr>

									<th>EAN</th>
									<th>Désignation</th>
									<th class="text-right">Qte</th>
									<th class="text-right">Prix achat Unitaire</th>
									<th class="text-right">PVC</th>
									<th class="text-right">PA Total</th>
									<th class="text-right">Taux de marge</th>

								</tr>
							</thead>
							<tbody>
								<?php foreach ($palette as $key => $article): ?>

									<tr id="<?=$article['id_palette']?>">

										<td><?= str_pad($article['ean'], 13, '0', STR_PAD_LEFT)?></td>
										<td><?=$article['designation']?></td>
										<td class="text-right"><?=$article['quantite']?></td>
										<td class="text-right"><?=$article['pa']?>&euro;</td>
										<td class="text-right"><?=$article['pvc']?>&euro;</td>
										<td class="text-right"><?=number_format((float)($article['pa'] *$article['quantite']),2,'.', ' '); ?>&euro;</td>
										<td class="text-right"><?=$article['marge']?>%</td>

									</tr>
									<?php $montantPalette=$montantPalette+($article['pa'] *$article['quantite']) ?>
								<?php endforeach ?>
								<tr class="bg-main-blue text-white my-5">
									<td class="text-right" colspan="6">Montant total palette : </td>
									<td class="text-right"><?=number_format((float)($montantPalette),2,'.', ' ');?>&euro;</td>
									<td></td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="col-1"></div>

				</div>
				<div class="row pb-2">
					<div class="col">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'#cart-count'?>" method="post">
							<input type="hidden" name="id_palette" value="<?=$article['id_palette']?>">
							<div class="row">
								<div class="col text-right">
									<button class="btn btn-primary" name="addtocart"><i class="fas fa-cart-plus pr-3"></i>Ajouter</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col text-right"><a href="#top">Haut</a></div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
	<?php else: ?>

		<div class="row">
			<div class="col">
				<div class="alert alert-primary">
					Aucune palette occasion disponible pour l'instant
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col text-right"><a href="#top">Haut</a></div>
		</div>

	<?php endif ?>
