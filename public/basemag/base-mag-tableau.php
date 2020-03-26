<div class="row">
	<div class="col">
		<h5 class="text-main-blue text-center pt-3 pb-3">Nombre de magasins affichés : <?=$nbResult?></h5>
		<div class="alert alert-primary">Pour obtenir plus d'information sur un magasin, veuillez cliquer sur son nom</div>
		<table class="table table-sm shadow">
			<thead class="thead-dark">
				<tr>
					<th>Btlec</th>
					<th>Deno</th>
					<th>Galec</th>
					<th>Ville</th>
					<th>code acdlec</th>
					<th>Centrale</th>
					<th>Chargé de mission</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($magList)): ?>
					<?php foreach ($magList as $key => $mag): ?>
						<tr>
							<td><?=$mag['id']?></td>
							<td><a class="text-sca" href="fiche-mag.php?id=<?=$mag['id']?>"><?=$mag['deno_sca']?></a></td>
							<td class="text-sca"><?=$mag['galec_sca']?></td>
							<td class="text-sca"><?=$mag['cp_sca'] .' '.$mag['ville']?></td>
							<td class="text-gessica"><?=$mag['acdlec_code']?></td>
							<td class="text-sca"><?=isset($centraleName[$mag['centrale_sca']])?$centraleName[$mag['centrale_sca']]:"" ?></td>
							<td><?= UserHelpers::getFullname($pdoUser, $mag['id_cm_web_user'])?></td>
						</tr>
					<?php endforeach ?>

				<?php endif ?>

			</tbody>
		</table>

	</div>
</div>