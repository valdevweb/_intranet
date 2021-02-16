<?php if ($detailLitige[0]['id_reclamation']!=7): ?>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th>Article</th>
					<th>Dossier</th>
					<th>Qte cde</th>
					<th>Tarif</th>
					<th>Qte litige</th>
					<th>Valo</th>
					<th>Réclamation</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
					<?php foreach ($detailLitige as $keydetail => $detail): ?>
						<?php if (empty($detail['inversion'])): ?>
							<tr>
								<td><?=$detail['article']?></td>
								<td><?=$detail['dossier_gessica']?></td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide" name="qte_cde[]" id="qte_cde" value="<?=$detail['qte_cde']?>">
									</div>


								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="tarif[]" id="tarif" value="<?=$detail['tarif']?>">
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide"  name="qte_litige[]" id="qte_litige" value=<?=$detail['qte_litige']?>>
									</div>


								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control moyen-input" name="valo_line[]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" id="valo_line" value="<?=$detail['valo_line']?>">
									</div>


								</td>
								<td>

									<div class="form-group">
										<select class="form-control" name="id_reclamation[]" id="id_reclamation">
											<?php foreach ($listReclamations as $key => $reclam): ?>
												<option value="" >NC</option>
												<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
													<?=$listReclamations[$key]?>
												</option>
											<?php endforeach ?>
										</select>
									</div>

								</td>
								<td>
									<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
									<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier</button>
								</td>
								<td>
									<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
								</td>
							</tr>
							<?php else: ?>
								<tr>
									<td class="inv" colspan="9">Article commandé :</td>
								</tr>
								<tr>
									<td><?=$detail['article']?></td>
									<td><?=$detail['dossier']?></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide" name="qte_cde[]" id="qte_cde" value="<?=$detail['qte_cde']?>">
										</div>


									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="tarif[]" id="tarif" value="<?=$detail['tarif']?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide"  name="qte_litige[]" id="qte_litige" value=<?=$detail['qte_litige']?>>
										</div>
									</td>
									<td></td>
									<td>
										<div class="form-group">
											<div class="form-group">
												<select class="form-control" name="id_reclamation[]" id="id_reclamation">
													<?php foreach ($listReclamationsIncludingMasked as $key => $reclam): ?>
														<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
															<?=$listReclamationsIncludingMasked[$key]?>

														</option>
													<?php endforeach ?>
												</select>
											</div>

										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="inv" colspan="9">Article reçu :</td>
								</tr>
								<?php if ($detail['inv_article']==""): ?>
									<tr>
										<td  colspan="9">
											L'EAN saisi par le magasin n'a pas permis de trouver d'article correspondant. Vous pouvez le modifer ci dessous et effectuer à nouveau une recherche dans la base
										</td>
									</tr>
									<tr class="no-bottom-border">
										<td colspan="4">
											<div class="form-group">

												<input type="text" class="form-control" name="ean"  value="<?=$detail['inversion']?>" id="ean" >
											</div>
										</td>
										<td colspan="5">
											<button class="btn btn-primary" name="search">Rechercher l'article</button>
										</td>
									</tr>

									<?php if (isset($found) && $found!=false): ?>
										<tr>
											<td class="inv" colspan="9">
												Articles trouvés dans la base :<br>

											</td>
										</tr>
										<tr  class="no-bottom-border">
											<td></td>
											<td colspan="7">
												<div class="text-center">Pour mettre à jour le litige avec un des articles listé, veuillez cliquer sur le bouton <i class="fas fa-check-circle"></i></div>
												<table class="table table-sm">
													<thead class="thead-dark">
														<tr>
															<th>EAN</th>
															<th>Article</th>
															<th>Dossier</th>
															<th>Libellé</th>
															<th>PANF</th>
															<th>PCB</th>
															<th><i class="fas fa-check-circle"></i></th>

														</tr>
													</thead>
													<tbody>

														<?php foreach ($found as $key => $f): ?>
															<tr>
																<td><?=$f['GESSICA.Gencod']?></td>
																<td><?=$f['GESSICA.CodeArticle']?></td>
																<td><?=$f['GESSICA.CodeDossier']?></td>
																<td><?=$f['GESSICA.LibelleArticle']?></td>
																<td><?=$f['GESSICA.PANF']?></td>
																<td><?=$f['GESSICA.PCB']?></td>
																<td><a href="edit-litige/03-inv.php?id_dossier=<?=$_GET['id']?>&id_detail=<?=$detail['id_detail']?>&id_inv=<?=$f['id']?>&inv_qte=<?=$detail['inv_qte']?>"><i class="fas fa-check-circle"></i></a></td>
															</tr>
														<?php endforeach ?>

													</tbody>
												</table>
											</td>
											<td></td>

										</tr>
										<?php elseif(isset($found) && $found==false): ?>
											<div class="alert alert-warning">Aucun article trouvé avec ce Gencod</div>
										<?php endif ?>

										<?php else: ?>

											<tr>
												<td>
													<?=$detail['inv_article']?>
													<input type="hidden" class="form-control" name="inv_article[]" id="inv_article" value="<?=$detail['inv_article']?>">
													<input type="hidden" class="form-control" name="inversion[]" id="inversion" value="<?=$detail['inversion']?>">

												</td>
												<td></td>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="inv_tarif[]" id="inv_tarif" value="<?=$detail['inv_tarif']?>">
													</div>

												</td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control mini-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="inv_qte[]" id="tarif" value="<?=$detail['inv_qte']?>">
													</div>
												</td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control moyen-input" name="valo_line[]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" id="valo_line" value="<?=$detail['valo_line']?>">
													</div>

												</td>
												<td></td>
												<td>
													<input type="hidden" class="form-control" name="inv_ref[]" id="inv_ref" value="<?=$detail['id_detail']?>">
													<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
													<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier <?=$keydetail?></button>
												</td>
												<td>
													<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
												</td>
											</tr>
										<?php endif?>

									<?php endif ?>

								<?php endforeach ?>
							</form>
						</tbody>
					</table>
					<?php else: ?>
						Inversion de palette, la fonctionnalité de modification sur une inversion de la palette n'a pas été développée

					<?php endif ?>