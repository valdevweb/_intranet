<div class="row mt-3 mb-3">
	<div class="col">
		<div class="row">

			<div class="col-auto">
				<h5 class="khand text-main-blue pb-3">Palettes :</h5>

				<table class="table light-shadow table-bordered">
					<thead>
						<tr>
							<th class="bg-reddish text-white">Palette commandée</th>
							<th class="bg-reddish text-white text-right">Valorisation</th>
							<th class="bg-reddish text-white text-center">Détail</th>
							<th class="bg-black">Palette reçue</th>
							<th class="text-right bg-black">Valorisation</th>
							<th class="text-right bg-black text-center">Détail</th>
							<th class="align-top bg-black">Pièces jointes</th>

						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?= $cdePal['palette'] ?></td>
							<td class="text-right"><?= $cdePal['valoCde'] ?></td>
							<td class="text-center"><a href="?id=<?= $_GET['id']?>&cde"><i class="fas fa-eye"></i></a></td>
							<td><?= $invPal['palette'] ?></td>
							<td class="text-right"><?= $invPal['valoInv'] ?></td>
							<td class="text-center"><a href="?id=<?= $_GET['id']?>&inv"><i class="fas fa-eye"></i></a></td>
							<td><?= $pj ?></td>
						</tr>
					</tbody>
				</table>

			</div>
			<div class="col"></div>
		</div>
	</div>
</div>

<?php


if($detailInv)
{

	echo '<div class="row mt-3 mb-3">';
	echo '<div class="col">';
	echo '<div class="row">';
	echo '<div class="col">';
	echo '<h5 class="khand text-main-blue pb-3">Détail de la palette reçue :</h5>';
	echo '<table class="table light-shadow">';
	echo '<thead class="thead-dark">';
	echo '<tr>';
	echo '<th>Article</th>';
	echo '<th>Dossier</th>';
	echo '<th>Désignation</th>';
	echo '<th>Fournisseur</th>';
	echo '<th>Quantité</th>';
	echo '<th>Valo</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach ($invPalette as $pal)
	{
		echo '<tr>';
		echo '<td>'.$pal['article'].'</td>';
		echo '<td>'.$pal['dossier_gessica'].'</td>';
		echo '<td>'.$pal['descr'].'</td>';
		echo '<td>'.$pal['fournisseur'].'</td>';
		echo '<td>'.$pal['qte_cde'].'</td>';
		echo '<td class="text-right">'.$pal['tarif'].'</td>';
		echo '</tr>';

	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
echo '<div class="text-right pb-2"><a href="?id='.$_GET['id'].'" class="btn btn-primary"><i class="far fa-times-circle pr-3"></i>Fermer</a></div>';

}

if($detailCde)
{

	echo '<div class="row mt-3 mb-3">';
	echo '<div class="col">';
	echo '<div class="row">';
	echo '<div class="col">';
	echo '<h5 class="khand text-main-blue pb-3">Détail de la palette non reçue :</h5>';
	echo '<table class="table light-shadow">';
	echo '<thead class="thead-dark">';
	echo '<tr>';
	echo '<th>Article</th>';
	echo '<th>Dossier</th>';
	echo '<th>Désignation</th>';
	echo '<th>Fournisseur</th>';
	echo '<th>Quantité</th>';
	echo '<th>Valo</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach ($thisLitige as $pal)
	{
		echo '<tr>';
		echo '<td>'.$pal['article'].'</td>';
		echo '<td>'.$pal['dossier_gessica'].'</td>';
		echo '<td>'.$pal['descr'].'</td>';
		echo '<td>'.$pal['fournisseur'].'</td>';
		echo '<td>'.$pal['qte_cde'].'</td>';
		echo '<td class="text-right">'.$pal['tarif'].'</td>';
		echo '</tr>';

	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
echo '<div class="text-right pb-2"><a href="?id='.$_GET['id'].'" class="btn btn-primary"><i class="far fa-times-circle pr-3"></i>Fermer</a></div>';

}


?>



