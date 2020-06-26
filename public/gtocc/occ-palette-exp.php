<div class="bg-separation"></div>
<div class="row">
	<div class="col">
		<h3 class="text-main-blue text-center pt-4 pb-2" id="exp">Palettes Expédiées</h3>
	</div>
</div>

<?php if (!empty($paletteExpediees)): ?>
	<div class="row pb-2">
		<div class="col">
			<table class="table table-sm shadow">
				<thead class="thead-dark">
					<tr>
						<th>Cde n°</th>
						<th>Palette</th>
						<th>Magasin</th>
						<th>Date commande</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach ($paletteExpediees as $key => $palette): ?>
						<tr>
							<td><?=$palette['id_cde']?></td>
							<td><?=$palette['palette']?></td>
							<td><?= UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $palette['id_web_user'], 'deno_sca')  ?></td>
							<td><?=$palette['date_cde']?></td>

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