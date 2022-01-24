	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

		<table class="table table-sm table-striped" id="table-cde-encours">
			<thead class="thead-dark">
				<thead>
					<tr>
						<?php for ($i = 1; $i < count($tableCol); $i++) : ?>
							<th class="align-top bg-blue col-<?= $i ?>"><?= $tableCol[$i] ?></th>
						<?php endfor ?>
						<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
						<th class="align-top bg-blue">Prévisions</th>
						<th class="align-top bg-blue">Cmt BT</th>
						<th class="align-top bg-blue">Cmt Galec</th>

					</tr>
				</thead>
			</thead>
			<tbody>
				<?php foreach ($listCdes as $key => $cdes) : ?>
					<?php
					$bgColor = "";
					$percentRecu = "";
					$totalPrevi = 0;
					$restant = "";

					if ($cdes['qte_init'] != 0) {
						$recu = $cdes['qte_init'] - $cdes['qte_cde'];
						if ($recu != 0) {
							$percentRecu = ($recu * 100) / $cdes['qte_init'];
							$percentRecu = floor($percentRecu);
						} else {
							$percentRecu = 0;
						}
						if ($percentRecu < 50) {
							$bgColor = "bg-red";
						} elseif ($percentRecu >= 50 && $percentRecu < 90) {
							$bgColor = "bg-yellow";
						} elseif ($percentRecu >= 90) {
							$bgColor = "bg-green";
						}
						$percentRecu = $percentRecu . "%";
					}
					?>
					<tr id="<?= $cdes['id'] ?>" data="nosession">
						<td class="col-1"><?= $cdes['gt'] ?></td>
						<td class="col-2" class="text-right"><?= ($cdes['date_cde'] != null) ? date('d/m/y', strtotime($cdes['date_cde'])) : "" ?></td>
						<td class="col-3"><?= $cdes['fournisseur'] ?></td>
						<td class="col-4"><?= $cdes['marque'] ?></td>
						<td class="col-5"><?= $cdes['article'] ?></td>
						<td class="col-6"><?= $cdes['dossier'] ?></td>
						<td class="col-7 text-right"><?= ($cdes['date_start'] != null) ? date('d/m/y', strtotime($cdes['date_start'])) : "" ?></td>
						<td class="col-8"><?= strtolower($cdes['libelle_op']) ?></td>
						<td class="col-9"><?= $cdes['ref'] ?></td>
						<td class="col-10"><?= $cdes['ean'] ?></td>
						<td class="col-11"><?= strtolower($cdes['libelle_art']) ?></td>
						<td class="col-12"><?= $cdes['id_cde'] ?></td>
						<td class="col-13 text-right"><?= $cdes['qte_init'] ?></td>
						<td class="col-14 text-right"><?= $cdes['qte_cde'] ?></td>
						<td class="col-15 text-right"><?= $cdes['qte_uv_cde'] ?></td>
						<td class="col-16 text-right"><?= $cdes['cond_carton'] ?></td>
						<td class="col-17 text-right <?= $bgColor ?>"><?= $percentRecu ?></td>
						<?php if (isset($totalPrevi)) {
							$restant = $cdes['qte_init'] - $totalPrevi;
						}
						?>
						<td class="col-18 text-right"><?= $restant ?></td>
						<td class="col-19"><?= ($cdes['date_liv_init'] != null) ? date('d/m/y', strtotime($cdes['date_liv_init'])) : "" ?></td>
						<td class="col-20"><?= ($cdes['date_liv'] != null) ? date('d/m/y', strtotime($cdes['date_liv'])) : "" ?></td>
						<td class="text-center">
							<div class="form-check">
								<input class="form-check-input select-checkbox" type="checkbox" value="<?= $cdes['id'] ?>" name="id_encours[]">
							</div>
						</td>
						<td class="no-padding">
							<?php if (!empty($listInfos)) : ?>
								<?php if (isset($listInfos[$cdes['id']])) : ?>
									<table class="table-striped table-primary m-1">
										<?php foreach ($listInfos[$cdes['id']] as $key => $value) : ?>
											<tr>
												<?php
												$nbMaxcolspan = 4;
												$nbCol = 0;
												?>

												<?php if ($listInfos[$cdes['id']][$key]['week_previ'] != "") : ?>
													<td>
														<?= "s" . $listInfos[$cdes['id']][$key]['week_previ'] ?>
													</td>
													<?php $nbCol++ ?>


												<?php endif ?>
												<?php if ($listInfos[$cdes['id']][$key]['date_previ'] != "") : ?>
													<td>
														<?= date('d/m/y', strtotime($listInfos[$cdes['id']][$key]['date_previ'])) ?>
													</td>
													<?php $nbCol++ ?>

												<?php endif ?>
												<?php if ($listInfos[$cdes['id']][$key]['qte_previ'] != "") : ?>
													<td class="text-right">
														<?= $listInfos[$cdes['id']][$key]['qte_previ'] ?>
													</td>
													<?php $totalPrevi += ($listInfos[$cdes['id']][$key]['qte_previ'] / $cdes['cond_carton']); ?>
													<?php $nbCol++ ?>

												<?php endif ?>

												<?php
												$colspan = "";
												if ($nbCol != 0) {
													if ($nbMaxcolspan - $nbCol != 0) {
														$colspan = "colspan=" . $nbMaxcolspan - $nbCol;
													}
												} else {
													$colspan = "colspan=" . $nbMaxcolspan;
												}
												?>

											</tr>
										<?php endforeach ?>
									</table>
								<?php endif ?>

							<?php endif ?>
						</td>
						<td><?= $cdes['cmt_btlec'] ?></td>
						<td><?= $cdes['cmt_galec'] ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<div class="row">
			<div class="col text-right">Pour sélectionner toutes les lignes affichées :</div>
			<div class="col-auto">
				<input class="form-check-input" type="checkbox" value="1" name="checkall" id="checkall">
				<label class="form-check-label">Cocher tout</label>
			</div>
			<div class="col-"></div>
		</div>

		<div id="floating-nav">
			<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>
		</div>
	</form>