<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

	<table class="table table-sm" id="table-relance-trois">
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

				<th class="align-top bg-light-grey text-center"><i class="far fa-square"></i></th>
				<th class="align-top bg-light-grey">S.<br>prévi</th>
				<th class="align-top bg-light-grey" >Date<br>prévi</th>
				<th class="align-top bg-light-grey" >Qte<br>prévi</th>
				<th class="align-top bg-light-grey">Commentaire</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($relancesPerm as $key => $cdes): ?>

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
						<td  class="<?=$cdes['date_liv']==date('Y-m-d')?'bg-red':''?>"><?=date('d/m', strtotime($cdes['date_liv']))?></td>


						<td>
							<div class="form-check">
								<input class="form-check-input input_relance_perm" type="checkbox" value="<?=$cdes['id']?>"  name="rperm_encours_id[]">
							</div>
						</td>
						<?php if (!empty($relancesPermInfo) && isset($relancesPermInfo[$cdes['id']])): ?>
						<td>
							<?php foreach ($relancesPermInfo[$cdes['id']] as $key => $value): ?>
								<?=$relancesPermInfo[$cdes['id']][$key]['week_previ']?><br>
							<?php endforeach ?>

						</td>

						<td>
							<?php foreach ($relancesPermInfo[$cdes['id']] as $key => $value): ?>
								<?=$relancesPermInfo[$cdes['id']][$key]['date_previ']?><br>
							<?php endforeach ?>
						</td>
						<td>
							<?php foreach ($relancesPermInfo[$cdes['id']] as $key => $value): ?>
								<?=$relancesPermInfo[$cdes['id']][$key]['qte_previ']?><br>
							<?php endforeach ?>
						</td>
						<td>
							<?php foreach ($relancesPermInfo[$cdes['id']] as $key => $value): ?>
								<?=($relancesPermInfo[$cdes['id']][$key]['date_insert']!=null)?date('d/m/y', strtotime($relancesPermInfo[$cdes['id']][$key]['date_insert']))." : ":""?>

								<?=$relancesPermInfo[$cdes['id']][$key]['cmt']?><br>
							<?php endforeach ?>
						</td>
						<?php else: ?>
							<td></td>
							<td></td>
							<td></td>
							<td></td>

						<?php endif ?>
					</tr>

			<?php endforeach ?>
			<tr class="border-bottom">
				<td colspan="15"></td>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value=""  name="checkall" id="all_relance_perm">
					</div>
				</td>
				<td colspan="4">Cocher/décocher tout</td>
			</tr>

		</tbody>
	</table>

	<div class="row mb-5">
		<div class="col text-right">
			<button class="btn btn-orange" name="launch_relance_perm" type="submit">Relancer les articles sélectionnés</button>
		</div>
		<div class="col-lg-1"></div>
	</div>
</form>