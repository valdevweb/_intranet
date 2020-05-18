<div class="row mt-3">
	<div class="col">
		<div class="row">
			<div class="col">

				<table class="table light-shadow table-sm">
					<thead class="thead-dark">
						<tr>
							<th class="align-top">Article</th>
							<th class="align-top">Dossier</th>
							<th class="align-top">Palette</th>
							<th class="align-top">Désignation</th>
							<th class="align-top">Fournisseur</th>
							<th class="align-top">Réclamation</th>
							<th class="align-top">Qté <br>litige</th>
							<th class="align-top text-right">Valo</th>
							<th class="align-top">Supprimer</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($fLitige as $prod): ?>
							<?php
							$sumValo=0;

							if($prod['box_tete']==1){
								$classBoxHead='class=box-head';
							}
							else{
								$classBoxHead='';
							}
							if($prod['box_art']!=''){
								$boxIco='<i class="fas fa-box-open text-green pr-2"></i>';
							}
							else{
								$boxIco='';
							}


							?>

							<?php if ($prod['inversion'] ==""): ?>
								<!-- reclamation autre que inversion de produit -->
								<tr <?=$classBoxHead?>>
									<td><?=$prod['article']?></td>
									<td><?=$prod['dossier_gessica']?></td>
									<td><?=$prod['palette']?></td>
									<td><?=$boxIco.$prod['descr']?></td>
									<td><?=$prod['fournisseur']?></td>
									<td><?=$prod['reclamation']?></td>
									<td class="text-right"><?=$prod['qte_litige']?></td>
									<td class="text-right"> <?=number_format((float)$prod['valo_line'],2,'.','')?>&euro;</td>
									<td class="text-center"><a href="edit-dossier-litige.php?id=<?=$_GET['id']?>&iddetaildelete=<?=$prod['id_detail']?>"><i class="fas fa-trash-alt"></i></td>
								</tr>
								<?php else: ?>
									<!-- inversion de produit - produit qui aurait du être reçu-->
									<tr class="text-reddish">
										<td> <?= $prod['article']?></td>
										<td> <?= $prod['dossier_gessica']?></td>
										<td> <?= $prod['palette']?></td>
										<td> <?= $prod['descr']?></td>
										<td> <?= $prod['fournisseur']?></td>
										<td> <?= $prod['reclamation']?></td>
										<td class="text-right"> <?= $prod['qte_litige']?></td>
										<td class="text-right">  <?= number_format((float)$prod['tarif']/$prod['qte_cde']*$prod['qte_litige'],2,'.','')?></td>
										<td class="text-center"><a href="edit-dossier-litige.php?id=<?=$_GET['id']?>&iddetaildelete=<?=$prod['id_detail']?>"><i class="fas fa-trash-alt"></i></td>
										<!-- inversion de produit - produit reçu à la place-->
										<!-- cas 1 produit non trouvé dans la base -->
										<?php if ($prod['inv_article']==''): ?>
											<tr class="text-center text-reddish">
												<td colspan="9">Produit reçu à la place de la référence ci-dessus :</td>
											</tr>

											<tr class="text-reddish">
												<td colspan="4">Produit non trouvé - EAN saisi :</td>
												<td colspan="2" class="text-left"><?= $prod['inversion']?></td>
												<td class="text-right"><?=$prod['inv_qte']?></td>
												<td colspan="2" class="text-right"></td>
											</tr>
											<tr class="text-center bg-reddish text-white">
												<td colspan="9" class="text-right">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="9" class="text-right">&nbsp;</td>
											</tr>
											<?php else: ?>
												<!-- cas 2 produit trouvé dans la base -->
												<!-- $valoInv=round( $prod['inv_qte']*$prod['inv_tarif'],2); -->
												<tr class="text-center text-reddish">
													<td colspan="9">Produit reçu à la place de la référence ci-dessus :</td>
												</tr>
												<tr class="text-reddish">
													<td><?= $prod['inv_article']?></td>
													<td colspan="2">&nbsp;</td>
													<td><?= $prod['inv_descr']?></td>
													<td><?= $prod['inv_fournisseur']?></td>
													<td></td>
													<td class="text-right"><?= $prod['inv_qte']?></td>
													<td class="text-right"><?=number_format((float)round( $prod['inv_qte']*$prod['inv_tarif'],2),2,'.','')?>&euro;</td>
													<td class="text-center"></td>
												</tr>
												<tr class="text-center bg-reddish text-white">
													<td colspan="8" class="text-right"><?=number_format((float)$prod['valo_line'],2,'.','')?>&euro;</td>
													<td></td>
												</tr>
												<tr>
													<td colspan="9" class="text-right">&nbsp;</td>
												</tr>

											<?php endif ?>




										</tr>




									<?php endif ?>

								<?php endforeach ?>


							</tbody>
						</table>
						<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?= $prod['valo']?> </p>




					</div>
				</div>
			</div>
		</div>