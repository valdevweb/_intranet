<?php
$detailInv=false;
$detailCde=false;
$majrecherchepalette='';
$pj='';
$invPal = sommeInvPalette($pdoLitige);
$cdePal=sommePaletteCde($pdoLitige);
if(isset($_GET['inv'])){
	$invPalette=getInvPaletteDetail($pdoLitige);
	$detailInv=true;
}
if(isset($_GET['cde']))
{
		//on réutilise fLitige
	$detailCde=true;
}
		// tableau palette + pj
if($cdePal['pj']!='')
{
	$pj=createFileLink($cdePal['pj']);

}

		// si la palette n'a pas été trouvée au moment de la déclaration, l'utilisateur voit un btn rechercher apparaitre , l'adresse du bouton contient le paramètre search
if(isset($_GET['search'])){
	$newFoundPalette=searchPalette($pdoQlik, $infoLitige[0]['inv_palette']);
	if(empty($newFoundPalette))
	{
		$majrecherchepalette='<div class="alert alert-danger">la palette n\'a pas été trouvée</div>';
	}
	else
	{
		foreach ($newFoundPalette as $pal)
		{
			$paletteFound=addPaletteInv($pdoLitige,$pal['palette'],$pal['facture'], $pal['date_mvt'],$pal['article'],$pal['gencod'],$pal['dossier'],$pal['libelle'],$pal['qte'],$pal['tarif'],$pal['fournisseur'],$pal['cnuf']);
			if($paletteFound!=1)
			{
				$errors[]="Problème d'enregistrement lors de l'ajout de la palette reçue";
			}
			else
			{
						// il faut recalculer la valo totale
				$sumLitige=getSumLitige($pdoLitige);
				$sumRecu=getSumPaletteRecu($pdoLitige);
				$sumCde=$sumLitige['sumValo'];
				$sumRecu=$sumRecu['sumValo'];
				$sumValo=$sumCde -$sumRecu;
				$update=updateValoDossier($pdoLitige,$sumValo);
				$majrecherchepalette='<div class="alert alert-success">la palette a été trouvée et la base de donnée mise à jour. Cliquez <a href="?id='.$_GET['id'].'">ici pour rafraichir la page</a></div>';
			}
		}

	}
}

?>


<div class="row mt-3 mb-3">
	<div class="col">
		<div class="row">

			<div class="col-auto">
				<h5 class="khand text-main-blue pb-3">Palettes :</h5>
				<p><span class="text-main-blue">Facture : </span><?=$infoLitige[0]['facture'] .' du '.$infoLitige[0]['datefacture']?></p>


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
				<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?=number_format((float)$infoLitige[0]['valo'],2,'.',' ')?> &euro; </p>

			</div>
			<div class="col"></div>
		</div>
	</div>
</div>

<?php if (!$invPal['palette']): ?>
	<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					Le magasin a signalé qu'il a reçu la palette <?=$infoLitige[0]['inv_palette']?>  Cette palette n'a pas été retrouvée dans la base, voulez-voulancer une <span class="text-main-blue">nouvelle recherche ?</span>';
					<div class="text-center py-3"><a href="?id=<?=$_GET['id']?>&search" class="btn btn-primary"><i class="fas fa-search pr-3"></i>Rechercher</a></div>
					<?=$majrecherchepalette?>

				</div>
			</div>
		</div>
	</div>
<?php endif ?>
<?php if ($detailInv): ?>
	<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Détail de la palette reçue :</h5>
					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th>Article</th>
								<th>Dossier</th>
								<th>Désignation</th>
								<th>Fournisseur</th>
								<th>Quantité</th>
								<th>Valo</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($invPalette as $pal): ?>
								<tr>
									<td><?=$pal['article']?></td>
									<td><?=$pal['dossier_gessica']?></td>
									<td><?=$pal['descr']?></td>
									<td><?=$pal['fournisseur']?></td>
									<td><?=$pal['qte_cde']?></td>
									<td class="text-right">'.$pal['tarif']?></td>
								</tr>
							<?php endforeach ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right pb-2"><a href="?id='.$_GET['id'].'" class="btn btn-primary"><i class="far fa-times-circle pr-3"></i>Fermer</a></div>
<?php endif ?>

<?php if ($detailCde): ?>
	<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Détail de la palette non reçue :</h5>
					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th>Article</th>
								<th>Dossier</th>
								<th>Désignation</th>
								<th>Fournisseur</th>
								<th>Quantité</th>
								<th>Valo</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($infoLitige as $pal): ?>
								<tr>
									<td><?=$pal['article']?></td>
									<td><?=$pal['dossier_gessica']?></td>
									<td><?=$pal['descr']?></td>
									<td><?=$pal['fournisseur']?></td>
									<td><?=$pal['qte_cde']?></td>
									<td class="text-right">'.$pal['tarif']?></td>
								</tr>
							<?php endforeach ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right pb-2"><a href="?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="far fa-times-circle pr-3"></i>Fermer</a></div>
<?php endif ?>

