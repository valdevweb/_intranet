<div class="row pb-5">
	<div class="col">
		<table class="table table-sm" id="offre-table">
			<thead class="thead-dark">
				<tr>
					<th class="filter">Prospectus</th>
					<th>Marque</th>
					<th>Produit</th>
					<th>Référence</th>
					<th>EAN</th>
					<th class="text-right">GT</th>
					<th class="text-right">PVC</th>
					<th class="text-right">Montant</th>
					<th class="text-right">Montant<br>financé</th>
					<th>Offre</th>
					<th>Commentaire</th>
					<th class="text-center">Modifier</th>
					<th class="text-center">Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($listOffre as $key => $offre): ?>
					<?php if ($offre['id']!=$listIdProsp): ?>
						<?php
						$ancreProsp="prosp-".$offre['id'];
						$listIdProsp=$offre['id'];
						?>
						<?php else: ?>
							<?php
							$ancreProsp="";
							?>
						<?php endif ?>
						<tr id="offre-<?=$offre['id_offre']?>">
							<td id="<?=$ancreProsp?>"><?=$offre['prospectus']?></td>
							<td><?=$offre['marque']?></td>
							<td><?=$offre['produit']?></td>
							<td><?=$offre['reference']?></td>
							<td><?=$offre['ean']?></td>
							<td class="text-right"><?=$offre['gt']?></td>
							<td class="text-right"><?=$offre['pvc']?></td>
							<td class="text-right">
								<?=($offre['euro']==1)?str_replace('.','&euro;',$offre['montant']):round($offre['montant']).'%'?>
							</td>
							<td class="text-right">
								<?=($offre['euro']==1)?str_replace('.','&euro;',$offre['montant_finance']):round($offre['montant_finance']).'%'?>
							</td>
							<td><?=($offre['offre']==1)?"<span class='badge badge-primary'>BRII</span>":"<span class='badge badge-orange'>TEL</span>"?></td>
							<td><?=$offre['cmt']?></td>
							<td class="text-center"><a href="?offre-modif=<?=$offre['id_offre']?>#modif-offre"><i class="fas fa-edit"></i></a></td>
							<td class="text-center"><a href="offre-delete.php?id=<?=$offre['id_offre']?>"><i class="fas fa-trash-alt"></i></a></td>
						</tr>
					<?php endforeach ?>

				</tbody>
			</table>
		</div>
	</div>