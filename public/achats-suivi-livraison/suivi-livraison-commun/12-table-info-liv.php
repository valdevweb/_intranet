<?php foreach ($opToDisplay as $key => $op): ?>
	<div class="row mt-3 mb-3">
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
				<table class="table table-sm shadow table-borderless mr-2">
					<thead class="thead-light">

						<tr>
							<th class="w-200">Marque</th>
							<th class="w-120">article</th>
							<th class="w-120" colspan="2">EAN</th>
							<th>Désignation</th>


							<th class="w-80">Erratum</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($infoLiv as $key => $info): ?>


							<?php

							$bgDeux="";
							$bg="";
							$bgDeuxRemplace="";
							$bgRemplace="";
							$pourcent="";
							$pourcentDeux="";
							$pourcentDeuxRemplace="";
							$pourcentRemplace="";
							if (!empty($info['recu_deux']) || $info['recu_deux']==0 && $info['recu_deux']!=null) {
								$pourcentDeux=' %';
								if($info['recu_deux']<50){
									$bgDeux="bg-red";
								}elseif($info['recu_deux']>=50 && $info['recu_deux']<90){
									$bgDeux="bg-yellow";
								}elseif($info['recu_deux']>=90){
									$bgDeux="bg-green";
								}
							}
							if (!empty($info['recu']) || $info['recu']==0 && $info['recu']!=null) {
								$pourcent=' %';
								if($info['recu']<50){
									$bg="bg-red";
								}elseif($info['recu']>=50 && $info['recu']<90){
									$bg="bg-yellow";
								}elseif($info['recu']>=90){
									$bg="bg-green";
								}
							}

							if (!empty($info['recu_deux_remplace'])|| $info['recu_deux_remplace']==0 && $info['recu_deux_remplace']!=null) {
								$pourcentDeuxRemplace=' %';

								if($info['recu_deux_remplace']<50){
									$bgDeuxRemplace="bg-red";
								}elseif($info['recu_deux_remplace']>=50 && $info['recu_deux_remplace']<90){
									$bgDeuxRemplace="bg-yellow";
								}elseif($info['recu_deux_remplace']>=90){
									$bgDeuxRemplace="bg-green";
								}
							}

							if (!empty($info['recu_remplace'])|| $info['recu_remplace']==0 && $info['recu_remplace']!=null) {
								$pourcentRemplace=' %';
								if($info['recu_remplace']<50){
									$bgRemplace="bg-red";
								}elseif($info['recu_remplace']>=50 && $info['recu_remplace']<90){
									$bgRemplace="bg-yellow";
								}elseif($info['recu_remplace']>=90){
									$bgRemplace="bg-green";
								}
							}

							?>

							<?php if ($info['gt']!=$gt): ?>
								<tr>
									<td class="text-center font-weight-bold bg-light-blue" colspan="9"><?=mb_strtoupper($listGt[$info['gt']])??""?></td>
								</tr>
							<?php endif ?>
							<?php
							if($key%2==0){
								$lineColor="bg-light-grey";
							}else{
								$lineColor="";

							}
							?>



							<tr class="font-weight-boldless border-top-blue border-left-blue border-right-blue <?=$lineColor?>">
								<td><?=$info['marque']?></td>
								<td><?=$info['article']?></td>
								<td colspan="2"><?=$info['ean']?></td>
								<td><?=$info['libelle']?></td>
								<td></td>
							</tr>
							<tr class="border-right-blue border-left-blue <?=$lineColor?>">

								<td colspan="2" class="text-right">Info livraison au <span class="text-main-blue font-weight-boldless "><?=DateHelpers::concatJourMoisDateTime($lundiDeux, "long")?></span></td>
								<td class="text-right pr-2">Reçu :</td>
								<td class="<?=$bgDeux?> text-right w-80"><?=$info['recu_deux']?><?=$pourcentDeux?></td>
								<td class="pl-3"><?=$info['info_livraison_deux']?></td>
								<td></td>
							</tr>
							<tr  class="border-right-blue border-left-blue <?=$lineColor?>">

								<td colspan="2" class="text-main-blue font-weight-boldless text-right"><?=DateHelpers::concatJourMoisDateTime($lundiUn, "long")?></td>
								<td class="text-right pr-2">Reçu :</td>
								<td class="<?=$bg?> text-right"><?=$info['recu']?><?=$pourcent?></td>
								<td class="pl-3"><?=$info['info_livraison']?></td>
								<td class="text-center">
									<?php if (!empty($info['erratum'])): ?>
										<a href="<?=URL_UPLOAD.'erratum/'.$info['erratum']?>" target="_blank"><i class="fas fa-file-alt"></i></a>
									<?php endif ?>

								</td>
							</tr>
							<?php if (!empty($info['article_remplace']) || !empty($info['ean_remplace'])): ?>
							<tr class="border-right-blue border-left-blue <?=$lineColor?>">
								<td> </td>
								<td colspan="5">
									<span class="text-danger font-weight-boldless">Article de remplacement : </span><?=$info['article_remplace']?>
									<span class="pl-5 text-danger font-weight-boldless">EAN : </span>
									<?=$info['ean_remplace']?>
								</td>

							</tr>
							<tr  class="border-right-blue border-left-blue <?=$lineColor?>">
								<td></td>
								<td class="text-danger font-weight-boldless"><?=DateHelpers::concatJourMoisDateTime($lundiDeux, "long")?></td>
								<td class="text-right pr-2">Reçu :</td>
								<td class="<?=$bgDeuxRemplace?> text-right w-80"><?=$info['recu_deux_remplace']?><?=$pourcentDeuxRemplace?></td>
								<td class="pl-3"><?=$info['info_livraison_deux_remplace']?></td>
								<td></td>
							</tr>
							<tr  class="border-right-blue border-left-blue <?=$lineColor?>">
								<td></td>
								<td class="text-danger font-weight-boldless"><?=DateHelpers::concatJourMoisDateTime($lundiUn, "long")?></td>
								<td class="text-right pr-2">Reçu :</td>
								<td class="<?=$bgRemplace?> text-right"><?=$info['recu_remplace']?><?=$pourcentRemplace?></td>
								<td class="pl-3"><?=$info['info_livraison_remplace']?></td>
								<td class="text-center"></td>
							</tr>

						<?php endif ?>
						<?php $gt=$info['gt'] ?>

					<?php endforeach ?>
				</tbody>
			</table>

		</div>
	</div>
	<?php endforeach ?>