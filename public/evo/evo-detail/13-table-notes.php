
<table class="table table-sm">
	<thead class="thead-dark">
		<tr>
			<th>date</th>
			<th>Note</th>
			<th><i class="fas fa-edit"></i></th>
			<th><i class="fas fa-trash"></i></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($notes as $key => $note): ?>

		<tr>
			<td class="nowrap"><?=date('d-m-Y', strtotime($note['insert_on']))?></td>
			<td><?=nl2br($note['note'])?></td>
			<td><a href="?id=<?=$_GET['id']?>&id_note_update=<?=$note['id']?>#title-note"><i class="fas fa-edit"></i></a></td>
			<td><a href="?id=<?=$_GET['id']?>&id_note_del=<?=$note['id']?>"><i class="fas fa-trash"></i></a></td>
		</tr>
		<?php endforeach ?>

	</tbody>
</table>