
	<table class="table table-sm">
		<thead class="thead-light">
			<tr>
				<th>Nom</th>
				<th class="text-center"><i class="fas fa-trash-alt"></i></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($affectation as $key => $aff): ?>

			<tr>
				<td><?=$aff['fullname']?></td>
				<td class="text-center"><a href="?id=<?=$_GET['id']?>&del_affectation=<?=$aff['id']?>" class="link-orange"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
<?php endforeach ?>

		</tbody>
	</table>

