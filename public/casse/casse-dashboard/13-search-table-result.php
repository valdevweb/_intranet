<div class="row justify-content-center mb-2">
	<div class="col-auto border rounded py-2">
		<div class="row">

			<div class="col-auto <?= $classesAffectation[''] ?>"><i class="fas fa-palette pr-2"></i>Non affectée</div>
			<div class="col-auto <?= $classesAffectation[1] ?>"><i class="fas fa-palette pr-2"></i>Magasin</div>
			<div class="col-auto <?= $classesAffectation[2] ?>"><i class="fas fa-palette pr-2"></i>GT13</div>
			<div class="col-auto <?= $classesAffectation[3] ?>"><i class="fas fa-palette pr-2"></i>SAV</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<table class="table table-sm table-bordered table-striped" id="palettes">
			<thead class="thead-dark">
				<tr>
					<th class="sortable" onclick="sortTable(0);">Exp</th>
					<th class="sortable" onclick="sortTable(1);">Palette</th>
					<th class="sortable" onclick="sortTable(2);">Palette<br> contremarque</th>
					<th>Affectation</th>
					<th class="sortable text-center" onclick="sortTable(4);">Statut</th>
					<th class="sortable" onclick="sortTable(5);">Date expé</th>
					<th class="sortable" onclick="sortTable(6);">Magasin</th>
					<th class="sortable" onclick="sortTable(7);">Valo<br>palette</th>
					<?php if ($userDao->userHasThisRight($_SESSION['id_web_user'], 105)) : ?>
						<th><i class="fas fa-tools"></i></th>
						<th><i class="fas fa-trash"></i></th>
					<?php endif ?>

				</tr>
			</thead>
			<tbody>
				<?php foreach ($palettesToDisplay as $key => $palette) : ?>


					<tr id="palette-<?= $palette['id'] ?>" class="<?= $classesAffectation[$palette['id_affectation']] ?>">
						<td><?= $palette['id_exp'] ?></td>
						<td><a class="<?= $classesAffectation[$palette['id_affectation']] ?>" href="detail-palette.php?id=<?= $palette['id'] ?>"><?= $palette['palette'] ?></a></td>
						<td><?= $palette['contremarque'] ?></td>
						<td><?= isset($listAffectationIco[$palette['id_affectation']]) ? "<img src='../img/logos/" . $listAffectationIco[$palette['id_affectation']] . "'>" : "" ?></td>
						<td class="text-right"><?= logoStatut($palette) ?></td>
						<td class="text-center"><?= (!empty($palette['date_delivery'])) ? date('d-m-Y', strtotime($palette['date_delivery'])) : "" ?></td>
						<td class="text-center"><?= $palette['btlec'] ?></td>
						<td class="text-center"><?= $palette['valopalette'] ?></td>
						<?php if ($userDao->userHasThisRight($_SESSION['id_web_user'], 105)) : ?>
							<td><a class="<?= $classesAffectation[$palette['id_affectation']] ?>" href="#" data-toggle="modal" data-target="#edit-palette" data-id-palette="<?= $palette['id'] ?>" data-palette="<?= $palette['palette'] ?>"><i class="fas fa-tools"></i></a></td>
							<td><a class="<?= $classesAffectation[$palette['id_affectation']] ?>" href="?del-palette=<?= $palette['id'] ?>" onclick="return confirm('Etes vous sûr de vouloir supprimer la palette <?= $palette['palette'] ?> ?')"><i class="fas fa-trash"></i></a></td>
						<?php endif ?>

					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>