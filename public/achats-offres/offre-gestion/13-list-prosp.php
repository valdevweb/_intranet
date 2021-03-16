<div class="row">
	<div class="col">
		<table class="table table-sm">
			<thead class="thead-dark">
				<tr>
					<th>Prospectus</th>
					<th>Date de début</th>
					<th>Date de fin</th>
					<th>FicWopc</th>
					<th class="text-center">Modifier</th>
					<th class="text-center">Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($listProsp as $key => $prosp): ?>
					<tr>
						<td><?=$prosp['prospectus']?></td>
						<td><?=date('d-m-Y', strtotime($prosp['date_start']))?></td>
						<td><?=date('d-m-Y', strtotime($prosp['date_end']))?></td>
						<td><a href="<?=URL_UPLOAD.'ficwopc/'.$prosp['fic']?>" download><?=$prosp['fic']?></a></td>
						<td class="text-center"><a href="?prosp-id-mod=<?=$prosp['id']?>"><i class="fas fa-edit"></i></a></td>
						<td class="text-center"><a href="offre-delete-prosp.php?id=<?=$prosp['id']?>"><i class="fas fa-trash-alt"></i></a></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>