
<table class="table table-sm">
	<thead class="thead-dark">
		<tr>
			<th>Nom de l'opération</th>
			<th>Salon</th>
			<th>Catalogue</th>
			<th>Code op</th>
			<th>Date de remonté</th>
			<th>Guide d'achat</th>
			<th>Commentaires</th>
			<th>Fichiers</th>
			<th><i class="fas fa-edit"></i></th>
			<th><i class="fas fa-trash"></i></th>
		</tr>
	</thead>
	<tbody>



		<?php foreach ($listGesap as $key => $gesap): ?>
			<tr>
				<td><?=$gesap['op']?></td>
				<td><?=$gesap['salon']?></td>
				<td><?=$gesap['cata']?></td>
				<td><?=$gesap['code_op']?></td>
				<td><?=date('d-m-Y', strtotime($gesap['date_remonte']))?></td>
				<td><a href="<?=URL_UPLOAD.'gesap/'.$gesap['ga_file']?>"><?=$gesap['ga_num']?></a></td>
				<td><?=$gesap['cmt']?></td>

				<?php if (!empty($listFiles) && isset($listFiles[$gesap['id']])): ?>
					<td>

				<?php for($i=0;$i<count($listFiles[$gesap['id']]);$i++): ?>
						<a href="<?=URL_UPLOAD.'gesap/'.$listFiles[$gesap['id']][$i]['file']?>"><?=empty($listFiles[$gesap['id']][$i]['filename'])?'<i class="fas fa-file pb-3"></i>':$listFiles[$gesap['id']][$i]['filename']?></a><br>
					<?php endfor ?>
				</td>
				<?php else: ?>
					<td></td>
				<?php endif ?>
				<td><a href="gesap-modif.php?id=<?=$gesap['id']?>"><i class="fas fa-edit"></i></a></td>
				<td><a href="gesap-delete.php?id=<?=$gesap['id']?>"><i class="fas fa-trash"></i></a></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>