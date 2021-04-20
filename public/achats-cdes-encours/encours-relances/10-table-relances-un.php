<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

	<table class="table table-sm" id="table-relance-un">
		<thead class="">
			<tr>
				<th class="align-top bg-blue">GT</th>
				<th class="align-top bg-blue">Date cde</th>
				<th class="align-top bg-blue">Fournisseur</th>
				<th class="align-top bg-blue">Article</th>
				<th class="align-top bg-blue">Dossier</th>
				<th class="align-top bg-blue">Ref</th>
				<th class="align-top bg-blue">Désignation</th>
				<th class="align-top bg-light-grey">Cde</th>
				<th class="align-top bg-light-grey text-right ">Qte init <br>colis</th>
				<th class="align-top bg-light-grey text-right ">Qte <br>colis</th>
				<th class="align-top bg-light-grey text-right ">Qte <br>UV</th>
				<th class="align-top bg-light-grey text-right ">PCB</th>
				<th class="align-top bg-light-grey ">Date<br> récep</th>
				<th class="align-top bg-blue">Date op</th>
				<th class="align-top bg-blue">Op</th>
				<th class="align-top bg-light-grey text-center"><i class="far fa-square"></i></th>
				<th class="align-top bg-light-grey">S.<br>prévi</th>
				<th class="align-top bg-light-grey" >Date<br>prévi</th>
				<th class="align-top bg-light-grey" >Qte<br>prévi</th>
				<th class="align-top bg-light-grey">Commentaire</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($relancesOne as $key => $cdes): ?>
				<?php if (!isset($relancesOnePrevi[$cdes['id']])): ?>

					<tr>
						<td class="bg-verylight-blue"><?=$cdes['gt']?></td>
						<td class="bg-verylight-blue" class="text-right"><?=date('d/m/y', strtotime($cdes['date_cde']))?></td>
						<td class="bg-verylight-blue"><?=ucwords(strtolower($cdes['fournisseur']))?></td>
						<td class="bg-verylight-blue"><?=$cdes['article']?></td>
						<td class="bg-verylight-blue"><?=$cdes['dossier']?></td>
						<td class="bg-verylight-blue"><?=$cdes['ref']?></td>
						<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_art'])?></td>
						<td class=""><?=$cdes['id_cde']?></td>
						<td class="text-right"><?=$cdes['qte_init']?></td>
						<td class="text-right"><?=$cdes['qte_cde']?></td>
						<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
						<td class="text-right"><?=$cdes['cond_carton']?></td>
						<td  class=""><?=date('d/m', strtotime($cdes['date_liv']))?></td>

						<td class="bg-verylight-blue text-right"><?=date('d/m', strtotime($cdes['date_start']))?></td>
						<td class="bg-verylight-blue"><?=ucwords(strtolower($cdes['libelle_op']))?></td>
						<td>
							<div class="form-check">
								<input class="form-check-input input_relance_one" type="checkbox" value="<?=$cdes['id']?>"  name="run_encours_id[]">
							</div>

						</td>
						<?php if (!empty($relancesOneInfos) && isset($relancesOneInfos[$cdes['id']])): ?>
						<td>
							<?php foreach ($relancesOneInfos[$cdes['id']] as $key => $value): ?>
								<?=$relancesOneInfos[$cdes['id']][$key]['week_previ']?><br>
							<?php endforeach ?>

						</td>

						<td>
							<?php foreach ($relancesOneInfos[$cdes['id']] as $key => $value): ?>
								<?=$relancesOneInfos[$cdes['id']][$key]['date_previ']?><br>
							<?php endforeach ?>
						</td>
						<td>
							<?php foreach ($relancesOneInfos[$cdes['id']] as $key => $value): ?>
								<?=$relancesOneInfos[$cdes['id']][$key]['qte_previ']?><br>
							<?php endforeach ?>
						</td>
						<td>
							<?php foreach ($relancesOneInfos[$cdes['id']] as $key => $value): ?>
								<?=$relancesOneInfos[$cdes['id']][$key]['cmt']?><br>
							<?php endforeach ?>
						</td>
						<?php else: ?>
							<td></td>
							<td></td>
							<td></td>
							<td></td>

						<?php endif ?>
					</tr>
				<?php endif ?>

			<?php endforeach ?>
			<tr class="border-bottom">
				<td colspan="15"></td>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value=""  name="checkall" id="all_relance_one">
					</div>
				</td>
				<td colspan="4">Cocher/décocher tout</td>
			</tr>

		</tbody>
	</table>

	<div class="row">
		<div class="col"></div>
		<div class="col-auto text-right">
		</div>
		<div class="col-auto text-right">
			<button class="btn btn-orange" name="launch_relance_one" type="submit">Relancer les articles sélectionnés</button>
		</div>
		<div class="col-lg-1"></div>
	</div>
</form>