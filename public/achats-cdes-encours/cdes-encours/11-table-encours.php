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
							<th class="align-top bg-blue">EAN</th>
							<th class="align-top bg-blue">Désignation</th>
							<th class="align-top bg-blue">Cde</th>
							<th class="align-top bg-blue text-right ">Qte init colis</th>
							<th class="align-top bg-blue text-right ">Colis à<br>recevoir</th>
							<th class="align-top bg-blue text-right ">UV à <br>recevoir</th>
							<th class="align-top bg-blue text-right ">PCB</th>
							<th class="align-top bg-blue text-right ">% reçu</th>
							<th class="align-top bg-blue ">Livraison<br>initiale</th>
							<th class="align-top bg-blue ">Livraison</th>
							<th class="align-top bg-blue">Date début op</th>
							<th class="align-top bg-blue">Op</th>
							<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
							<th class="align-top bg-blue">Prévisions/commentaires</th>
<!-- 							<th class="align-top bg-blue">Semaine prévi</th>
							<th class="align-top bg-blue" >Date prévi rdv</th>
							<th class="align-top bg-blue">Qte prévi</th>
							<th class="align-top bg-blue">Commentaire</ th>
							</tr>-->
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
									<th class="align-top bg-blue">EAN</th>
								<?php endif ?>
								<?php  if(in_array(8,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue">Désignation</th>
								<?php endif ?>
								<?php  if(in_array(9,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue">Cde</th>
								<?php endif ?>
								<?php  if(in_array(10,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue text-right ">Qte init colis</th>
								<?php endif ?>
								<?php  if(in_array(11,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue text-right ">Colis à<br>recevoir</th>
								<?php endif ?>
								<?php  if(in_array(12,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue text-right ">UV à<br> recevoir</th>
								<?php endif ?>
								<?php  if(in_array(13,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue text-right ">PCB</th>
								<?php endif ?>
								<?php  if(in_array(14,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue text-right ">% recu</th>
								<?php endif ?>
								<?php  if(in_array(15,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue ">Livraison<br>initiale</th>
								<?php endif ?>
								<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue ">Livraison</th>
								<?php endif ?>
								<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue">Date début op</th>
								<?php endif ?>
								<?php  if(in_array(18,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue">Op</th>
								<?php endif ?>
								<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
								<th class="align-top bg-blue">Prévisions/commentaires</th>
							<?php endif ?>

						</thead>
					</thead>
					<tbody>
						<?php foreach ($listCdes as $key => $cdes): ?>
							<?php
							$bgColor="";
							$percentRecu="";

							if($cdes['qte_init']!=0){
								$recu=$cdes['qte_init']-$cdes['qte_cde'];
								if($recu!=0){
									$percentRecu=($recu*100)/$cdes['qte_init'];
									$percentRecu=floor ($percentRecu);
								}else{
									$percentRecu=0 ;
								}
								if($percentRecu<50){
									$bgColor="bg-red";
								}elseif($percentRecu>=50 && $percentRecu<90){
									$bgColor="bg-yellow";
								}elseif($percentRecu>=90){
									$bgColor="bg-green";
								}
								$percentRecu=$percentRecu."%";
							}


							?>
							<?php if (!isset($_SESSION['encours_col'])): ?>
								<tr id="<?=$cdes['id']?>">
									<td class="bg-verylight-blue"><?=$cdes['gt']?></td>
									<td class="bg-verylight-blue" class="text-right"><?=($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):""?></td>
									<td class="bg-verylight-blue"><?=$cdes['fournisseur']?></td>
									<td class="bg-verylight-blue"><?=$cdes['marque']?></td>
									<td class="bg-verylight-blue"><?=$cdes['article']?></td>
									<td class="bg-verylight-blue"><?=$cdes['dossier']?></td>
									<td class="bg-verylight-blue"><?=$cdes['ref']?></td>
									<td class="bg-verylight-blue"><?=$cdes['ean']?></td>
									<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_art'])?></td>
									<td class=""><?=$cdes['id_cde']?></td>
									<td class="text-right"><?=$cdes['qte_init']?></td>
									<td class="text-right"><?=$cdes['qte_cde']?></td>
									<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
									<td class="text-right"><?=$cdes['cond_carton']?></td>
									<td class="text-right <?=$bgColor?>"><?=$percentRecu?></td>
									<td  class=""><?=($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):""?></td>
									<td  class=""><?=($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):""?></td>
									<td class="bg-verylight-blue text-right"><?=($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):""?></td>
									<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_op'])?></td>
									<td  class="text-center">
										<div class="form-check">
											<input class="form-check-input select-checkbox" type="checkbox" value="<?=$cdes['id']?>"  name="id_encours[]">
										</div>
									</td>
									<!-- si on a des info -->
									<?php if (!empty($listInfos)): ?>
										<!-- si on a des info pour cette commande -->
										<?php if (isset($listInfos[$cdes['id']])): ?>
											<td class="no-padding">
												<table>
													<tr>
														<th class="special-table">S.</th>
														<th class="special-table">Date</th>
														<th class="special-table">Qte</th>
														<th class="special-table">Commentaire</th>
													</tr>
													<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>
														<tr>
															<td>
																<?=$listInfos[$cdes['id']][$key]['week_previ']?>
															</td>
															<td>
																<?=$listInfos[$cdes['id']][$key]['date_previ']?>
															</td>
															<td class="text-right">
																<?=$listInfos[$cdes['id']][$key]['qte_previ']?>
															</td>
															<td>
																<?=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert']))?> :<?=$listInfos[$cdes['id']][$key]['cmt']?>
															</td>
														</tr>
													<?php endforeach ?>

												</table>
											</td>

											<!-- pas d'info pour cette commande -->
											<?php else: ?>
												<td></td>
											<?php endif ?>
											<!-- pas d'info du tout -->
											<?php else: ?>
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
													<td class="bg-verylight-blue"><?=$cdes['ean']?></td>
												<?php endif ?>
												<?php  if(in_array(8,$_SESSION['encours_col'])): ?>
													<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_art'])?></td>
												<?php endif ?>
												<?php  if(in_array(9,$_SESSION['encours_col'])): ?>
													<td class=""><?=$cdes['id_cde']?></td>
												<?php endif ?>
												<?php  if(in_array(10,$_SESSION['encours_col'])): ?>
													<td class="text-right"><?=$cdes['qte_init']?></td>
												<?php endif ?>
												<?php  if(in_array(11,$_SESSION['encours_col'])): ?>
													<td class="text-right"><?=$cdes['qte_cde']?></td>
												<?php endif ?>
												<?php  if(in_array(12,$_SESSION['encours_col'])): ?>
													<td class="text-right"><?=$cdes['qte_uv_cde']?></td>
												<?php endif ?>
												<?php  if(in_array(13,$_SESSION['encours_col'])): ?>
													<td class="text-right"><?=$cdes['cond_carton']?></td>
												<?php endif ?>
												<?php  if(in_array(14,$_SESSION['encours_col'])): ?>
													<td class="text-right <?=$bgColor?>"><?=$percentRecu?></td>
												<?php endif ?>
												<?php  if(in_array(15,$_SESSION['encours_col'])): ?>
													<td  class=""><?=($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):""?></td>
												<?php endif ?>
												<?php  if(in_array(16,$_SESSION['encours_col'])): ?>
													<td  class=""><?=($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):""?></td>
												<?php endif ?>
												<?php  if(in_array(17,$_SESSION['encours_col'])): ?>
													<td class="bg-verylight-blue text-right"><?=($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):""?></td>
												<?php endif ?>
												<?php  if(in_array(18,$_SESSION['encours_col'])): ?>
													<td class="bg-verylight-blue"><?=strtolower($cdes['libelle_op'])?></td>
												<?php endif ?>
												<td>
													<div class="form-check">
														<input class="form-check-input select-checkbox" type="checkbox" value="<?=$cdes['id']?>"  name="id_encours[]">
													</div>
												</td>
												<?php if (!empty($listInfos)): ?>
													<?php if (isset($listInfos[$cdes['id']])): ?>
														<td class="no-padding">
															<table>
																<tr>
																	<th class="special-table">S.</th>
																	<th class="special-table">Date</th>
																	<th class="special-table">Qte</th>
																	<th class="special-table">Commentaire</th>
																</tr>
																<?php foreach ($listInfos[$cdes['id']] as $key => $value): ?>

																	<tr>
																		<td>
																			<?=$listInfos[$cdes['id']][$key]['week_previ']?>
																		</td>
																		<td>
																			<?=$listInfos[$cdes['id']][$key]['date_previ']?>
																		</td>
																		<td class="text-right">

																			<?=$listInfos[$cdes['id']][$key]['qte_previ']?>
																		</td>
																		<td>
																			<?=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert']))?> :<?=$listInfos[$cdes['id']][$key]['cmt']?>
																		</td>
																	</tr>
																<?php endforeach ?>

															</table>
														</td>

														<?php else: ?>
															<td></td>
														<?php endif ?>
														<?php else: ?>
															<td></td>
														<?php endif ?>
													<?php endif ?>
												</tr>

											<?php endforeach ?>

											<?php
											if (!isset($_SESSION['encours_col'])){
												$colspan=sizeof($tableCol);
												// echo "pas sesssion ".$colspan;
											}else{
												$colspan=sizeof($_SESSION['encours_col']);
												// echo $colspan;

											}
											?>

											<tr>
												<td colspan="<?=$colspan?>"></td>
												<td colspan="2">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" value="1"  name="checkall" id="checkall">
														<label class="form-check-label" >Cocher tout</label>
													</div>

												</td>
											</tr>

										</tbody>
									</table>


									<div id="floating-nav">
										<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>

									</div>
								</form>
