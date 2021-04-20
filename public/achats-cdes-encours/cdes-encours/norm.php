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
	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

		<table class="table table-sm" id="table-cde-encours">
			<thead class="thead-dark">
				<thead>
					<tr>
						<th class="align-top bg-blue">GT</th>
						<th class="align-top bg-blue">Date cde</th>
						<th class="align-top bg-blue">Fournisseur</th>
						<th class="align-top bg-blue">Marque</th>
						<th class="align-top bg-blue">Article</th>
						<th class="align-top bg-blue">Dossier</th>
						<th class="align-top bg-blue">Ref</th>
						<th class="align-top bg-blue">Désignation</th>
						<th class="align-top bg-blue">Cde</th>
						<th class="align-top bg-blue text-right ">Qte init colis</th>
						<th class="align-top bg-blue text-right ">Qte colis</th>
						<th class="align-top bg-blue text-right ">Qte UV</th>
						<th class="align-top bg-blue text-right ">PCB</th>
						<th class="align-top bg-blue ">Date réception</th>
						<th class="align-top bg-blue">Date début op</th>
						<th class="align-top bg-blue">Op</th>
						<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
						<th class="align-top bg-blue">Semaine prévi</th>
						<th class="align-top bg-blue" >Date prévi rdv</th>
						<th class="align-top bg-blue">Qte prévi</th>
						<th class="align-top bg-blue">Commentaire</th>
					</tr>

				</thead>
			</thead>
			<tbody>

				<?php foreach ($listCdes as $key => $cdes): ?>
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
						</tr>
					</tbody>
				</table>
				<div id="floating-nav">
					<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>

				</div>
			</form>
