
<table class="table table-sm">
	<thead class="thead-dark">
		<tr>
			<th>Date publication</th>
			<th>Catégorie</th>
			<th>Type d'information</th>
			<th>Titre</th>
			<th>Description</th>
			<th>Fichiers joints</th>
			<th>Liens</th>
			<th colspan="2">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listGazette as $key => $gazette): ?>
			<tr>
				<td><?=FR_DAYS[date('w', strtotime($gazette['date_start']))].' '.date('j', strtotime($gazette['date_start'])).' '.FR_MONTHS[date('n', strtotime($gazette['date_start']))]?></td>
				<td><?=($mainCat[$gazette['main_cat']])??""?></td>
				<td><?=($listCat[$gazette['cat']])??""?></td>
				<td><?=$gazette['titre']?></td>
				<td><?=$gazette['description']?></td>
				<td>

					<?php if (isset($listFiles[$gazette['id']])): ?>
						<?php foreach ($listFiles[$gazette['id']] as $key => $file): ?>
							<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><?=($file['filename'])?$file['filename']:'<i class="fas fa-file pb-3"></i>'?></a><br>
						<?php endforeach ?>
					<?php endif ?>
				</td>
				<td>
					<?php if (isset($listLinks[$gazette['id']])): ?>
						<?php foreach ($listLinks[$gazette['id']] as $key => $link): ?>
							<a href="<?=$link['link']?>"><?=($link['linkname'])?$link['linkname']:'cliquez ici'?></a><br>
						<?php endforeach ?>
					<?php endif ?>
				</td>
				<td><a href="modif-gazette.php?id=<?=$gazette['id']?>" title="modifier"><i class="fas fa-edit"></i></a></td>
				<td><a href="delete-gazette.php?id=<?=$gazette['id']?>" title="supprimer"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
		<?php endforeach ?>

	</tbody>
</table>
