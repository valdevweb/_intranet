	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

		<table class="table table-sm" id="table-cde-encours">
			<thead class="thead-dark">
				<thead>
					<?php if (!isset($_SESSION['encours_col'])): ?>
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
						<?php else: ?>

							<?php  if(in_array(0,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">GT</th>
							<?php endif ?>
							<?php  if(in_array(1,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Date cde</th>
							<?php endif ?>
							<?php  if(in_array(2,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Fournisseur</th>
							<?php endif ?>
							<?php  if(in_array(3,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Marque</th>
							<?php endif ?>
							<?php  if(in_array(4,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Article</th>
							<?php endif ?>
							<?php  if(in_array(5,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Dossier</th>
							<?php endif ?>
							<?php  if(in_array(6,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Ref</th>
							<?php endif ?>
							<?php  if(in_array(7,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Désignation</th>
							<?php endif ?>
							<?php  if(in_array(8,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Cde</th>
							<?php endif ?>
							<?php  if(in_array(9,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue text-right ">Qte init colis</th>
							<?php endif ?>
							<?php  if(in_array(10,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue text-right ">Qte colis</th>
							<?php endif ?>
							<?php  if(in_array(11,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue text-right ">Qte UV</th>
							<?php endif ?>
							<?php  if(in_array(12,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue text-right ">PCB</th>
							<?php endif ?>
							<?php  if(in_array(13,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue ">Date réception</th>
							<?php endif ?>
							<?php  if(in_array(14,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Date début op</th>
							<?php endif ?>
							<?php  if(in_array(15,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Op</th>
							<?php endif ?>
							<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
							<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Semaine prévi</th>
							<?php endif ?>
							<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue" >Date prévi rdv</th>
							<?php endif ?>
							<?php  if(in_array(18,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Qte prévi</th>
							<?php endif ?>
							<?php  if(in_array(19,$_SESSION['encours_col'])): ?>
								<th class="align-top bg-blue">Commentaire</th>
							<?php endif ?>

						<?php endif ?>

					</thead>
				</thead>
				<tbody>
					<?php foreach ($listCdes as $key => $cdes): ?>
						<?php if (!isset($_SESSION['encours_col'])): ?>
							<tr id="<?=$cdes['id']?>">
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
									</tr>
									<?php else: ?>
										<tr id="<?=$cdes['id']?>">

											<?php  if(in_array(0,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['gt']?></td>
											<?php endif ?>
											<?php  if(in_array(1,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue" class="text-right"><?=($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):""?></td>
											<?php endif ?>
											<?php  if(in_array(2,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['fournisseur']?></td>
											<?php endif ?>
											<?php  if(in_array(3,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['marque']?></td>
											<?php endif ?>
											<?php  if(in_array(4,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['article']?></td>
											<?php endif ?>
											<?php  if(in_array(5,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['dossier']?></td>
											<?php endif ?>
											<?php  if(in_array(6,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=$cdes['ref']?></td>
											<?php endif ?>
											<?php  if(in_array(7,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_art'])?></td>
											<?php endif ?>
											<?php  if(in_array(8,$_SESSION['encours_col'])): ?>
												<td class=""><?=$cdes['id_cde']?></td>
											<?php endif ?>
											<?php  if(in_array(9,$_SESSION['encours_col'])): ?>
												<td class="text-right"><?=$cdes['qte_init']?></td>
											<?php endif ?>
											<?php  if(in_array(10,$_SESSION['encours_col'])): ?>
												<td class="text-right"><?=$cdes['qte_cde']?></td>
											<?php endif ?>
											<?php  if(in_array(11,$_SESSION['encours_col'])): ?>
												<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
											<?php endif ?>
											<?php  if(in_array(12,$_SESSION['encours_col'])): ?>
												<td class="text-right"><?=$cdes['cond_carton']?></td>
											<?php endif ?>
											<?php  if(in_array(13,$_SESSION['encours_col'])): ?>
												<td  class=""><?=($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):""?></td>
											<?php endif ?>
											<?php  if(in_array(14,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue text-right"><?=($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):""?></td>
											<?php endif ?>
											<?php  if(in_array(15,$_SESSION['encours_col'])): ?>
												<td class="bg-verylight-blue">15<?=strtolower($cdes['libelle_op'])?></td>
											<?php endif ?>
											<td>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="<?=$cdes['id']?>"  name="id_encours[]">
												</div>
											</td>
											<?php if (!empty($listInfos)): ?>
												<?php if (isset($listInfos[$cdes['id']])): ?>
													<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
														<td>
															<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
																<?=$listInfos[$cdes['id']][$key]['week_previ']?><br>
															<?php endforeach ?>
														</td>
													<?php endif ?>
													<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
														<td>
															<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
																<?=$listInfos[$cdes['id']][$key]['date_previ']?><br>
															<?php endforeach ?>
														</td>
													<?php endif ?>
													<?php  if(in_array(18,$_SESSION['encours_col'])): ?>

														<td>
															<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
																<?=$listInfos[$cdes['id']][$key]['qte_previ']?><br>
															<?php endforeach ?>

														</td>
													<?php endif ?>
													<?php  if(in_array(19,$_SESSION['encours_col'])): ?>

														<td>
															<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
																<?=$listInfos[$cdes['id']][$key]['cmt']?><br>
															<?php endforeach ?>
														</td>
													<?php endif ?>

													<?php else: ?>
														<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(18,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(19,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
													<?php endif ?>
													<?php else: ?>
														<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(18,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
														<?php  if(in_array(19,$_SESSION['encours_col'])): ?>
															<td></td>
														<?php endif ?>
													<?php endif ?>



												<?php endif ?>
											</tr>

										<?php endforeach ?>

									</tbody>
								</table>
								<div id="floating-nav">
									<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>

								</div>
							</form>
