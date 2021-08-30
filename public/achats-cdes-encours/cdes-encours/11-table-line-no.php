	<td class=""><?=$cdes['gt']?></td>
	<td class="" class="text-right"><?=($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):""?></td>
	<td class=""><?=$cdes['fournisseur']?></td>
	<td class=""><?=$cdes['marque']?></td>
	<td class=""><?=$cdes['article']?></td>
	<td class=""><?=$cdes['dossier']?></td>
	<td class=" text-right"><?=($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):""?></td>
	<td class=""><?=strtolower($cdes['libelle_op'])?></td>
	<td class=""><?=$cdes['ref']?></td>
	<td class=""><?=$cdes['ean']?></td>
	<td class=""><?=strtolower($cdes['libelle_art'])?></td>
	<td class=""><?=$cdes['id_cde']?></td>
	<td class="text-right"><?=$cdes['qte_init']?></td>
	<td class="text-right"><?=$cdes['qte_cde']?></td>
	<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
	<td class="text-right"><?=$cdes['cond_carton']?></td>
	<td class="text-right <?=$bgColor?>"><?=$percentRecu?></td>
		<?php if (isset($totalPrevi)){
		$restant=$cdes['qte_init']-$totalPrevi;
	}
	?>
	<td class="text-right"><?=$restant?></td>
	<td class=""><?=($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):""?></td>
	<td class=""><?=($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):""?></td>
	<td  class="text-center">
		<div class="form-check">
			<input class="form-check-input select-checkbox" type="checkbox" value="<?=$cdes['id']?>"  name="id_encours[]">
		</div>
	</td>
	<td class="no-padding">
		<?php if (!empty($listInfos)): ?>
			<?php if (isset($listInfos[$cdes['id']])): ?>
				<table class="table-striped table-primary m-1">
					<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
						<tr>
							<?php
							$nbMaxcolspan=4;
							$nbCol=0;
							?>

							<?php if ($listInfos[$cdes['id']][$key]['week_previ']!=""): ?>
								<td>
									<?="s".$listInfos[$cdes['id']][$key]['week_previ']?>
								</td>
								<?php $nbCol++?>


							<?php endif ?>
							<?php if ($listInfos[$cdes['id']][$key]['date_previ']!=""): ?>
								<td>
									<?=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_previ']))?>
								</td>
								<?php $nbCol++?>

							<?php endif ?>
							<?php if ($listInfos[$cdes['id']][$key]['qte_previ']!=""): ?>
								<td class="text-right">
									<?=$listInfos[$cdes['id']][$key]['qte_previ']?>
								</td>
								<?php $totalPrevi+=($listInfos[$cdes['id']][$key]['qte_previ']/$cdes['cond_carton']);?>
								<?php $nbCol++?>

							<?php endif ?>

							<?php
							$colspan="";
							if($nbCol!=0){
								if($nbMaxcolspan-$nbCol!=0){
									$colspan= "colspan=" .$nbMaxcolspan-$nbCol;
								}
							}else{
								$colspan= "colspan=" .$nbMaxcolspan;

							}
							?>
							<td <?=$colspan?>>
								<div class="tooltiplaunch"><?=$listInfos[$cdes['id']][$key]['cmt']?> <span class="tooltiptext-yellow"><?=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert']))?></span></div>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			<?php endif ?>

		<?php endif ?>
	</td>

