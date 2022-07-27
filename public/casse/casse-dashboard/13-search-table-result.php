<div class="row">
	<div class="col">
		<table class="table table-sm table-bordered table-striped" id="palettes">
			<thead class="thead-dark">
				<tr>
					<th>temp</th>
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


					<tr id="palette-<?= $palette['id'] ?>" class="<?= $classesAffectation[$palette['statut']] ?>">
						<td><?= $classesAffectation[$palette['statut']] . $palette['statut'] ?></td>
						<td><a href="#" class="<?= $classesAffectation[$palette['statut']] ?>"><?= $palette['id_exp'] ?></a></td>
						<td><a class="<?= $classesAffectation[$palette['statut']] ?>" href="detail-palette.php?id=<?= $palette['id'] ?>"><?= $palette['palette'] ?></a></td>
						<td><a href="#" class="<?= $classesAffectation[$palette['statut']] ?>"><?= $palette['contremarque'] ?></a></td>
						<td><?= isset($listAffectationIco[$palette['id_affectation']]) ? "<img src='../img/logos/" . $listAffectationIco[$palette['id_affectation']] . "'>" : "" ?></td>
						<td class="text-right"><?= logoStatut($palette) ?></td>
						<td class="text-center"><a href="#" class="<?= $classesAffectation[$palette['statut']] ?>"><?= (!empty($palette['date_delivery'])) ? date('d-m-Y', strtotime($palette['date_delivery'])) : "" ?></a></td>
						<td class="text-center"><a href="#" class="<?= $classesAffectation[$palette['statut']] ?>"><?= $palette['btlec'] ?></a></td>
						<td class="text-center"><a href="#" class="<?= $classesAffectation[$palette['statut']] ?>"><?= $palette['valopalette'] ?></a></td>
						<?php if ($userDao->userHasThisRight($_SESSION['id_web_user'], 105)) : ?>
							<td><a class="<?= $classesAffectation[$palette['statut']] ?>" href="#" data-toggle="modal" data-target="#edit-palette" data-id-palette="<?= $palette['id'] ?>" data-palette="<?= $palette['palette'] ?>"><i class="fas fa-tools"></i></a></td>
							<td><a class="<?= $classesAffectation[$palette['statut']] ?>" href="?del-palette=<?= $palette['id'] ?>" onclick="return confirm('Etes vous sûr de vouloir supprimer la palette <?= $palette['palette'] ?> ?')"><i class="fas fa-trash"></i></a></td>
						<?php endif ?>

					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>