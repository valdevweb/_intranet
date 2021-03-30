<table class="table table-sm shadow-sm">
	<thead class="thead-dark">
		<tr>
			<th>date de début</th>
			<th>date de fin</th>
			<th>GT</th>
			<th>Famille</th>
			<th>Marque</th>
			<th>EAN</th>
			<th>Fichiers</th>
			<th class="text-right">Modifier</th>
			<th class="text-right">Supprimer</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listOdr as $key => $odr): ?>
			<tr>
				<td class="nowrap"><?=$odr['date_start']?></td>
				<td class="nowrap"><?=$odr['date_end']?></td>
				<td><?=$odr['gt']?></td>
				<td><?=$odr['famille']?></td>
				<td><?=$odr['marque']?></td>
				<td>
					<?php if (isset($listEan[$odr['id']])): ?>
						<?php for ($i=0; $i < count($listEan[$odr['id']]); $i++): ?>
							<?php if (!empty($listEan[$odr['id']][$i]['ean_file'])): ?>
								<a href="<?=URL_UPLOAD.'odr/'.$listEan[$odr['id']][$i]['ean_file']?>">liste des EAN</a><br>
							<?php endif ?>
							<?php if (!empty($listEan[$odr['id']][$i]['ean'])): ?>
								<?=$listEan[$odr['id']][$i]['ean']?><br>
							<?php endif ?>
						<?php endfor ?>
					<?php endif ?>
				</td>
				<td>
					<?php if (isset($listFiles[$odr['id']])): ?>
						<?php for ($i=0; $i < count($listFiles[$odr['id']]); $i++): ?>
							<a href="<?=URL_UPLOAD.'odr/'.$listFiles[$odr['id']][$i]['file']?>"><?=(empty($listFiles[$odr['id']][$i]['filename']))?'<i class="fas fa-file-alt"></i>':$listFiles[$odr['id']][$i]['filename']?></a><br>

						<?php endfor ?>
					<?php endif ?>

				</td>

				<td class="text-right"><a href="odr-modif.php?id=<?=$odr['id']?>"><i class="fas fa-edit"></i></a></td>
				<td class="text-right"><a href="odr-delete.php?id=<?=$odr['id']?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
		<?php endforeach ?>

	</tbody>
</table>