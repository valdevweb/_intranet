<div class="row">
	<div class="col">
		<h4 class="text-main-blue text-center"> Repartition des  <?=$nbLitiges?> litiges de votre sélection</h4>
		<h5 class="text-main-blue text-center"> Filtre(s) actif(s) : <?=isset($_SESSION['pending-ico'])?$_SESSION['pending-ico']:'' ?><?=isset($_SESSION['vingtquatre-ico'])?$_SESSION['vingtquatre-ico']:'' ?><?= !isset($_SESSION['pending-ico']) && !isset($_SESSION['vingtquatre-ico']) ? '<span class="text-grey">aucun</span>' : ''?></h5>

	</div>
</div>

<div class="row ">
	<div class="col-6">
		<table class="table">
			<tbody>
				<tr>
					<td class="text-red">Valorisation Totale</td>
					<td class="text-right heavy bg-red"><?= number_format((float)$valoTotal['valo_totale'],2,'.',' ')?>&euro;</td>
					<td></td>
				</tr>
				<?php
				$col=1;
				$maxLig=ceil(count($valoEtat)/2);
				foreach ($valoEtat as $vEtat)
				{
					if(empty($vEtat['etat']))
					{
						$denoEtat='sans statut';
					}
					else
					{
						$denoEtat=$vEtat['etat'];
					}
					if($col<=$maxLig)
					{
						echo '<tr>';
						echo '<td>'.$denoEtat.'</td>';
						echo '<td class="text-right heavy">'.number_format((float)$vEtat['valo_etat'],2,'.',' ').'&euro;</td>';
						echo '<td class="text-right">'.$vEtat['nbEtat'].' dossiers</td>';
						echo '</tr>';
						$col++;
					}
					else
					{
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
						echo '<div class="col-6">';
						echo '<table class="table">';
						echo '<tbody>';
						echo '<tr>';
						echo '<td>'.$denoEtat.'</td>';
						echo '<td class="text-right heavy">'.number_format((float)$vEtat['valo_etat'],2,'.',' ').'&euro;</td>';
						echo '<td class="text-right">'.$vEtat['nbEtat'].' dossiers</td>';
						echo '</tr>';
						$col=1;
					}
				}
				?>

			</tbody>
		</table>
	</div>
</div>
<div class="row mt-3">

	<div class="col text-center">
		<a href="xl-selected.php" class="btn btn-green"> <i class="fas fa-file-excel pr-3"></i>Exporter la sélection</a>
		<a href="xl-encours.php" class="btn btn-red"> <i class="fas fa-file-excel pr-3"></i>Exporter la base entière</a>

	</div>
</div>