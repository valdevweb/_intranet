<div class="row">
	<div class="col">

		<?php if (isset($paramList) && !empty($paramList) ): ?>
		<h4 class="text-main-blue text-center"> Repartition des  <?=$nbLitiges?> litiges de votre sélection</h4>
		<?php else: ?>
			<h4 class="text-main-blue text-center"> Repartition par statuts - année en cours</h4>

		<?php endif ?>

		<h5 class="text-main-blue text-center"> Filtre(s) actif(s) :
			<?=isset($_SESSION['filter-data']['pending-ico'])?$_SESSION['filter-data']['pending-ico']:'' ?>
			<?=isset($_SESSION['filter-data']['vingtquatre-ico'])?$_SESSION['filter-data']['vingtquatre-ico']:'' ?>
			<?= !isset($_SESSION['filter-data']['pending-ico']) && !isset($_SESSION['filter-data']['vingtquatre-ico']) ? '<span class="text-grey">aucun</span>' : ''?></h5>

		</div>
	</div>

	<div class="row ">
		<div class="col">
			<div class="two-cols">
				<table class="table">
					<tbody>
						<?php foreach ($valoEtat as $key => $vEtat): ?>
							<?php
							if(empty($vEtat['etat'])){
								$denoEtat='sans statut';
							}
							else{
								$denoEtat=$vEtat['etat'];
							}
							if($vEtat['occ_etat']==1){
								$textColor="text-blue";
								$sumValoOcc+=$vEtat['valo'];
								$nbTotalDossierOcc+=$vEtat['nbEtat'];
							}else{
								$textColor="";
								$sumValoMain+=$vEtat['valo'];
								$nbTotalDossierMain+=$vEtat['nbEtat'];

							}
							$sumValoTotale=$sumValoOcc+ $sumValoMain;
							$nbTotalDossierStatut +=$vEtat['nbEtat'];

							?>
							<?php if ($key!=0 && $valoEtat[$key-1]['occ_etat']!=null && $valoEtat[$key]['occ_etat']!=$valoEtat[$key-1]['occ_etat']): ?>
								<tr>
									<td class="heavy bg-red">Valorisation hors occasion</td>
									<td class="text-right heavy bg-red"><?= number_format((float)$sumValoMain,2,'.',' ')?>&euro;</td>
									<td class="text-right heavy bg-red"><?=$nbTotalDossierMain?> dossiers</td>
								</tr>
							<?php endif ?>
							<tr>
								<td class="<?=$textColor?>"><?=$denoEtat?></td>
								<td class="text-right heavy"><?=number_format((float)$vEtat['valo'],2,'.',' ')?>&euro;</td>
								<td class="text-right"><?=$vEtat['nbEtat']?> dossiers</td>
							</tr>

						<?php endforeach ?>
						<!-- si on a eu les deux catégroeis occ et pas occ (dépend des filtres) -->
						<?php if ($sumValoMain!=0 && $sumValoOcc!=0): ?>
							<tr>
								<td class="bg-light-blue">Valorisation occasion</td>
								<td class="text-right bg-light-blue"><?= number_format((float)$sumValoOcc,2,'.',' ')?>&euro;</td>
								<td class="text-right bg-light-blue"><?=$nbTotalDossierOcc?> dossiers</td>
							</tr>
						<?php endif ?>
						<tr class="bg-red heavy">

							<td>Valorisation totale</td>
							<td class="text-right"><?= number_format((float)$sumValoTotale,2,'.',' ')?>&euro;</td>
							<td class="text-right"><?= $nbTotalDossierStatut?> dossiers</td>

						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h4 class="text-main-blue text-center">Répartition par typologie - année en cours</h4>
		</div>
	</div>
	<div class="row ">
		<div class="col">
			<div class="two-cols">
				<table class="table">
					<tbody>
						<?php foreach ($valoTypo as $key => $vTypo): ?>
							<tr>
								<td class=""><?= empty($vTypo['typo'])? "sans typo" : $vTypo['typo']?></td>
								<td class="text-right heavy"><?=number_format((float)$vTypo['valo'],2,'.',' ')?>&euro;</td>
								<td class="text-right"><?=$vTypo['nbTypo']?> dossiers</td>
							</tr>
							<?php
							$sumValoTypo += $vTypo['valo'];
							$nbTotalDossierTypo +=$vTypo['nbTypo'];
							 ?>
						<?php endforeach ?>
						<tr class="bg-red heavy">
							<td >Valorisation totale</td>
							<td class="text-right"><?= number_format((float)$sumValoTypo,2,'.',' ')?>&euro;</td>
							<td class="text-right"><?= $nbTotalDossierTypo?> dossiers</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

<div class="row">
	<div class="col text-center">
						<a href="xl-selected.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Exporter la sélection</a>

	</div>
</div>