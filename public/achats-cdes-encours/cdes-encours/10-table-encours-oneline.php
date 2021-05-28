	<div class="row">
		<div class="col">
			<div class="alert alert-primary">
				<i class="fas fa-lightbulb pr-3"></i>Vous pouvez masquer des colonnes en cliquant sur les switchs qui se trouvent sous les entêtes de colonnes.<br>
				<i class="fas fa-lightbulb pr-3"></i>Pour réafficher une colonne masquée, cliquez sur le nom de la colonne en question dans la liste des colonnes masquées<br>
				<i class="fas fa-exclamation-triangle pr-3"></i>A chaque rechargement de la page (saisie, filtrage, etc), les colonnes masquées se réaffichent
			</div>
		</div>
		<div class="col-lg-4"></div>
	</div>
	<div class="row my-3">
		<div class="col-auto">
			Colonnes masquées :
		</div>
		<div class="col" id="masked-col">

			<?php for ($j=0; $j <count($tableCol) ; $j++) : ?>
				<a href="#1" class="badge badge-primary show-col mr-3" data-col-show="<?=$j?>"><?=$tableCol[$j]?></a>
			<?php endfor ?>
		</div>
	</div>




	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

		<table class="table table-sm" id="table-cde-encours">
			<thead class="thead-dark">
				<thead>
					<tr>
						<th class="align-top bg-blue apply-filter">GT</th>
						<th class="align-top bg-blue">Date cde</th>
						<th class="align-top bg-blue apply-filter">Fournisseur</th>
						<th class="align-top bg-blue apply-filter">Marque</th>
						<th class="align-top bg-blue">Article</th>
						<th class="align-top bg-blue">Dossier</th>
						<th class="align-top bg-blue">Ref</th>
						<th class="align-top bg-blue">Désignation</th>
						<th class="align-top bg-light-grey apply-filter">Cde</th>
						<th class="align-top bg-light-grey text-right ">Qte init colis</th>
						<th class="align-top bg-light-grey text-right ">Qte colis</th>
						<th class="align-top bg-light-grey text-right ">Qte UV</th>
						<th class="align-top bg-light-grey text-right ">PCB</th>
						<th class="align-top bg-light-grey text-right ">% reçu</th>
						<th class="align-top bg-light-grey ">Date réception</th>
						<th class="align-top bg-blue">Date début op</th>
						<th class="align-top bg-blue apply-filter">Op</th>
						<th  class="align-top bg-light-grey text-center"><i class="far fa-square"></i></th>

						<th class="align-top bg-light-grey">Semaine prévi</th>
						<th class="align-top bg-light-grey" >Date prévi rdv</th>
						<th class="align-top bg-light-grey">Qte prévi</th>
						<th class="align-top bg-light-grey">Commentaire</th>
					</tr>

				</thead>
			</thead>
			<tbody>
				<tr>
					<?php for ($i=0; $i <20 ; $i++): ?>
						<td>
							<label class="switch">
								<input class="switch-input" data-col="<?=$i?>" type="checkbox" />
								<span class="switch-label"  data-on="On" data-off="Off"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					<?php endfor ?>


				</tr>
				<?php foreach ($listCdes as $key => $cdes): ?>
					<?php
					$recu=$cdes['qte_init']-$cdes['qte_cde'];
					if($recu!=0){
						$percentRecu=$recu/100*$cdes['qte_init'];
						$percentRecu=floor ($percentRecu);
					}else{
						$percentRecu=0;
					}
					if($percentRecu<50){
						$bgColor="bg-red";
					}elseif($percentRecu>=50 && $percentRecu<90){
						$bgColor="bg-yellow";
					}elseif($percentRecu>=90){
						$bgColor="bg-green";
					}

					?>
					<tr class="gt-<?=$cdes['gt']?>" id="<?=$cdes['id']?>">
						<td class="bg-verylight-blue"><?=$cdes['gt']?></td>
						<td class="bg-verylight-blue" class="text-right"><?=($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):""?></td>
						<td class="bg-verylight-blue"><?=$cdes['fournisseur']?></td>
						<td class="bg-verylight-blue"><?=$cdes['marque']?></td>
						<td class="bg-verylight-blue"><?=$cdes['article']?></td>
						<td class="bg-verylight-blue"><?=$cdes['dossier']?></td>
						<td class="bg-verylight-blue"><?=$cdes['ref']?></td>
						<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_art'])?></td>
						<td class=""><?=$cdes['id_cde']?></td>
						<td class="text-right"><?=$cdes['qte_init']?></td>
						<td class="text-right"><?=$cdes['qte_cde']?></td>
						<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
						<td class="text-right"><?=$cdes['cond_carton']?></td>
						<td class="text-right <?=$bgColor?>"><?=$percentRecu?></td>
						<td  class=""><?=($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):""?></td>
						<td class="bg-verylight-blue text-right"><?=($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):""?></td>
						<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_op'])?></td>
						<td  class="text-center">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="<?=$cdes['id']?>"  name="id_encours[]">

							</div>
						</td>
						<?php if (!empty($listInfos)): ?>
							<?php if (isset($listInfos[$cdes['id']])): ?>
								<td>
									<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
										<?=$listInfos[$cdes['id']][$key]['week_previ']?><br>
									<?php endforeach ?>
								</td>
								<td>
									<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
										<?=$listInfos[$cdes['id']][$key]['date_previ']?><br>
									<?php endforeach ?>
								</td>
								<td>
									<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
										<?=$listInfos[$cdes['id']][$key]['qte_previ']?><br>
									<?php endforeach ?>

								</td>
								<td>
									<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
										<?=$listInfos[$cdes['id']][$key]['cmt']?><br>
									<?php endforeach ?>
								</td>
								<?php else: ?>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								<?php endif ?>
								<?php else: ?>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								<?php endif ?>



							<?php endforeach ?>

						</tbody>
					</table>
					<div id="floating-nav">
						<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>

					</div>
				</form>
