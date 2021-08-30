
<table class="table table-sm">
	<thead class="thead-light">
		<tr>
			<th>Début</th>
			<th>Fin</th>
			<th class="text-center"><i class="fas fa-trash"></i></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($plannings as $key => $planning): ?>
			<tr>
				<td><?=date('d/m/y',strtotime($planning['date_start']))?></td>
				<td><?=date('d/m/y' ,strtotime($planning['date_end']))?></td>
				<td class="text-center"><a href="?id=<?=$_GET['id'].'&del_planning='.$planning['id']?>" class="link-orange"><i class="fas fa-trash"></i></a></td>
			</tr>
		<?php endforeach ?>

	</tbody>
</table>


