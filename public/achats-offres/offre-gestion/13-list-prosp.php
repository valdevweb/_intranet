<div class="row">
	<div class="col">
		<table class="table table-sm">
			<thead class="thead-dark">
				<tr>
					<th>Prospectus</th>
					<th>Date de début</th>
					<th>Date de fin</th>
					<th>FicWopc</th>
					<th>Fichiers</th>
					<th>Liens</th>
					<th class="text-center">Modifier</th>
					<th class="text-center">Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($listProsp as $key => $prosp): ?>
					<tr>
						<td><a href="#prosp-<?=$prosp['id']?>"><?=$prosp['prospectus']?></a></td>
						<td class="nowrap"><?=date('d-m-Y', strtotime($prosp['date_start']))?></td>
						<td class="nowrap"><?=date('d-m-Y', strtotime($prosp['date_end']))?></td>
						<td><a href="<?=URL_UPLOAD.'ficwopc/'.$prosp['fic']?>" download><?=$prosp['fic']?></a></td>
						<td>
							<?php if (isset($listFiles[$prosp['id']])): ?>
								<?php foreach ($listFiles[$prosp['id']] as $key => $file): ?>
									<a href="<?=URL_UPLOAD.'offres/'.$file['file']?>" download><?=($file['filename'])??'<i class="fas fa-file"></i>'?></a><br>
								<?php endforeach ?>
							<?php endif ?>
						</td>
						<td>
							<?php if (isset($listLinks[$prosp['id']])): ?>
								<?php foreach ($listLinks[$prosp['id']] as $key => $link): ?>
									<a href="<?=URL_UPLOAD.'offres/'.$link['link']?>" target="_blank"><?=($link['linkname'])??'lien'?></a><br>
								<?php endforeach ?>
							<?php endif ?>
						</td>
						<td class="text-center"><a href="?prosp-id-mod=<?=$prosp['id']?>#modif-prosp"><i class="fas fa-edit"></i></a></td>
						<td class="text-center"><a href="offre-delete-prosp.php?id=<?=$prosp['id']?>"><i class="fas fa-trash-alt"></i></a></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>