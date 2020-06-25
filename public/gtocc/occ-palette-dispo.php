	<div class="bg-separation"></div>
	<div class="row">
		<div class="col">
			<h3 class="text-main-blue text-center pt-4 pb-2" id="over">Palettes disponibles à la commande</h3>
		</div>
	</div>

	<?php if (!empty($paletteCommandable)): ?>

		<?php foreach ($paletteCommandable as $key => $palette): ?>

			<div class="row my-3" id="detailPalette">
				<div class="col">
					<h5 class="text-main-blue text-center">Palette <?=$palette[0]['palette'] ?></h5>
				</div>
			</div>

			<div class="row pb-2">
				<div class="col">
					<table class="table table-sm shadow">
						<thead class="thead-dark">
							<tr>
								<th>Code article</th>
								<th>Code Dossier</th>
								<th>Désignation</th>
								<th>EAN</th>

							</tr>
						</thead>
						<tbody>
							<?php foreach ($palette as $key => $article): ?>

								<tr>
									<td><?=$article['code_article']?></td>
									<td><?=$article['code_dossier']?></td>
									<td><?=$article['designation'] . $article['id_palette']?></td>
									<td><?=$article['ean']?></td>

								</tr>
							<?php endforeach ?>


						</tbody>
					</table>

				</div>
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

		<?php endforeach ?>
		<?php else: ?>

			<div class="row">
				<div class="col">
					<div class="alert alert-primary">
						Aucune palette occasion disponible pour l'instant
					</div>
				</div>
			</div>


		<?php endif ?>
