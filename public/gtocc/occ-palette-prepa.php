<div class="row">
	<div class="col">
		<h3 class="text-main-blue text-center pt-4" id="prepa">Palettes en cours de préparation</h3>

	</div>
</div>
<?php if (!empty($paletteEnPrepa)): ?>
	<?php foreach ($paletteEnPrepa as $key => $palette): ?>

		<div class="row my-3" id="detailPalette">
			<div class="col">
				<h5 class="text-main-blue text-center">Palette <?=$palette[0]['palette'] ?> (en préparation)</h5>
			</div>
		</div>

		<div class="row pb-5">
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
								<td><?=$article['designation']?></td>
								<td><?=$article['ean']?></td>

							</tr>
						<?php endforeach ?>


					</tbody>
				</table>

			</div>
		</div>
	<?php endforeach ?>
	<?php else: ?>

		<div class="row pb-5">
			<div class="col">
				<div class="alert alert-primary">
					Pas de palette en cours de préparation
				</div>
			</div>
		</div>
	<?php endif ?>
	<!-- titre uniquement pour bt -->
