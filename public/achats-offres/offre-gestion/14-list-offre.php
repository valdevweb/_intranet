<div class="row">
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
					<th class="text-center">Modifier</th>
					<th class="text-center">Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($listOffre as $key => $offre): ?>
					<tr>
						<td><?=$offre['prospectus']?></td>
						<td><?=$offre['marque']?></td>
						<td><?=$offre['produit']?></td>
						<td><?=$offre['reference']?></td>
						<td><?=$offre['ean']?></td>
						<td class="text-right"><?=$offre['gt']?></td>
						<td class="text-right"><?=$offre['pvc']?></td>
						<td class="text-right"><?=$offre['montant']?></td>
						<td class="text-right"><?=$offre['montant_finance']?></td>
						<td><?=($offre['offre']==1)?"BRII":"TEL"?></td>
						<td class="text-center"><a href="?offre-modif=<?=$offre['id_offre']?>#offre-delete"><i class="fas fa-edit"></i></a></td>
						<td class="text-center"><a href="offre-delete.php?id=<?=$offre['id_offre']?>"><i class="fas fa-trash-alt"></i></a></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>