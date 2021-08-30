
	<table class="table table-sm">
		<thead class="thead-dark">
			<tr>
				<th>Date</th>
				<th>Objet</th>
				<th>Detail</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($notifs as $key => $notif): ?>

			<tr>
				<td><?=$notif['date_notif']?></td>
				<td><?=$notif['title']?></td>
				<td><?=$notif['notif']?></td>
			</tr>
			<?php endforeach ?>

		</tbody>
	</table>