	<div class="row">
		<div class="col">
			<?php if (!empty($docs)): ?>


				<table class="table table-sm">
					<thead class="thead-dark">
						<tr>
							<th>Document</th>
							<th>Date ajout</th>
							<th class="text-center"><i class="fas fa-trash"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($docs as $key => $doc): ?>
							<tr>
								<td><a href="<?=UPLOAD_URL_EVO.$doc['file']?>" target='_blank' class="grey-link"><i class="fas fa-file pr-3"></i><?=$doc['filename']?></a></td>
								<td><?=date('d/m/Y', strtotime($doc['date_insert']))?></td>
								<td class="text-center"><a href="?del_document=<?=$doc['id'].'&id='.$_GET['id']?>" class="link-orange"><i class="fas fa-trash"></i></a></td>
							</tr>
						<?php endforeach ?>

					</tbody>
				</table>

			<?php endif ?>
		</div>
	</div>
