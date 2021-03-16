<?php foreach ($opToDisplay as $key => $op): ?>
	<div class="row mt-5 mb-3">
		<div class="col">
			<p class="text-main-blue font-weight-bold text-center"><?=$op['operation'] .' du '.date('d/m', strtotime($op['date_start'])). ' au '.date('d/m/Y', strtotime($op['date_end']))?><br>
				<?=$op['code_op']?></p>
			</div>
		</div>
		<?php $infoLiv=$infoLivDao->getInfoLivByOp($op['code_op']) ?>
		<div class="row">
			<div class="col">
				<table class="table table-sm shadow">
					<thead class="thead-light">
						<tr>
							<th colspan="4"></th>
							<th class="text-center border-special" colspan="2">Lundi 2</th>
							<th class="text-center border-special" colspan="2">Lundi 1</th>
							<th colspan="4"></th>
						</tr>
						<tr>
							<th>Marque</th>
							<th>article</th>
							<th>EAN</th>
							<th>Désignation</th>
							<th class="border-special-left">Reçu</th>
							<th class="border-special-right">Information</th>
							<th class="border-special-left">Reçu</th>
							<th class="border-special-right">Information</th>

							<th>Erratum</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($infoLiv as $key => $info): ?>
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

							<tr>
								<td><?=$info['marque']?></td>
								<td><?=$info['article']?></td>
								<td><?=$info['ean']?></td>
								<td><?=$info['libelle']?></td>
								<td class="<?=$bgDeux?> text-right"><?=$info['recu_deux']?><?=$pourcentDeux?></td>
								<td><?=$info['info_livraison_deux']?></td>
								<td class="<?=$bg?> text-right"><?=$info['recu']?><?=$pourcent?></td>
								<td><?=$info['info_livraison']?></td>

								<td><?=$info['id']?></td>
							</tr>
							<?php if (!empty($info['article_remplace']) || !empty($info['ean_remplace'])): ?>
							<tr>
								<td> </td>
								<td colspan="9">
									<span class="text-danger">Article de remplacement : </span><?=$info['article_remplace']?>
									<span class="pl-5 text-danger">EAN : </span>
									<?=$info['ean_remplace']?>
								</td>

							</tr>
						<?php endif ?>
							<?php $gt=$info['gt'] ?>

					<?php endforeach ?>
				</tbody>
			</table>

		</div>
	</div>
	<div class="bg-separation-thin mt-2"></div>
	<?php endforeach ?>