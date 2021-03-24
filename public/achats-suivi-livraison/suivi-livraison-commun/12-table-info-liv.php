<?php foreach ($opToDisplay as $key => $op): ?>
	<div class="row mt-5 mb-3">
		<div class="col">
			<p class="text-main-blue font-weight-bold text-center"><?=$op['operation'] .' du '.date('d/m', strtotime($op['date_start'])). ' au '.date('d/m/Y', strtotime($op['date_end']))?> -
				<?=$op['code_op']?></p>
			</div>
		</div>
		<?php $infoLiv=$infoLivDao->getInfoLivByOp($op['code_op']) ?>
		<div class="row">
			<div class="col">
				<?php
				$lundiUn=(new DateTime($op['date_start']))->modify("monday this week");
				$lundiDeux=clone($lundiUn);
				$lundiDeux=$lundiDeux->modify("- 7 days");
				?>
				<table class="table table-sm shadow table-borderless">
					<thead class="thead-light">

						<tr>
							<th>Marque</th>
							<th>article</th>
							<th colspan="2">EAN</th>
							<th>Désignation</th>


							<th>Erratum</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($infoLiv as $key => $info): ?>
							<?php if (!empty($info['recu']) || !empty($info['recu_deux'])): ?>

							<?php
							$bgDeux="";
							$bg="";
							$pourcent="";
							$pourcentDeux="";
							if (!empty($info['recu_deux'])) {
								$pourcentDeux=' %';

								if($info['recu_deux']<50){
									$bgDeux="bg-red";
								}elseif($info['recu_deux']>=50 && $info['recu_deux']<90){
									$bgDeux="bg-yellow";
								}elseif($info['recu_deux']>=90){
									$bgDeux="bg-green";
								}
							}
							if (!empty($info['recu'])) {
								$pourcent=' %';
								if($info['recu']<50){
									$bg="bg-red";
								}elseif($info['recu']>=50 && $info['recu']<90){
									$bg="bg-yellow";
								}elseif($info['recu']>=90){
									$bg="bg-green";
								}
							}
							?>
							<?php if ($info['gt']!=$gt): ?>
								<tr>
									<td class="text-center font-weight-bold bg-light-blue" colspan="9"><?=mb_strtoupper($listGt[$info['gt']])??""?></td>
								</tr>
							<?php endif ?>

							<tr class="font-weight-boldless border">
								<td><?=$info['marque']?></td>
								<td><?=$info['article']?></td>
								<td colspan="2"><?=$info['ean']?></td>
								<td><?=$info['libelle']?></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td><?=DateHelpers::concatJourMoisDateTime($lundiDeux, "long")?></td>
								<td></td>
								<td class="<?=$bgDeux?> text-right w-80"><?=$info['recu_deux']?><?=$pourcentDeux?></td>
								<td><?=$info['info_livraison_deux']?></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td><?=DateHelpers::concatJourMoisDateTime($lundiUn, "long")?></td>
								<td></td>
								<td class="<?=$bg?> text-right"><?=$info['recu']?><?=$pourcent?></td>
								<td><?=$info['info_livraison']?></td>
							<td class="text-center">
								<?php if (!empty($info['erratum'])): ?>
									<a href="<?=URL_UPLOAD.'erratum/'.$info['erratum']?>" target="_blank"><i class="fas fa-file-alt"></i></a>
								<?php endif ?>

							</td>
						</tr>
						<?php if (!empty($info['article_remplace']) || !empty($info['ean_remplace'])): ?>
						<tr>
							<td> </td>
							<td colspan="5">
								<span class="text-danger">Article de remplacement : </span><?=$info['article_remplace']?>
								<span class="pl-5 text-danger">EAN : </span>
								<?=$info['ean_remplace']?>
							</td>

						</tr>
					<?php endif ?>
					<?php $gt=$info['gt'] ?>
				<?php endif ?>

			<?php endforeach ?>
		</tbody>
	</table>

</div>
</div>
<div class="bg-separation-thin mt-2"></div>
<?php endforeach ?>