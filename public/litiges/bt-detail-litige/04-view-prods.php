
<div class="row mt-3">
	<div class="col-10">
		<h5 class="khand text-main-blue pb-3">Produit(s) :</h5>
		<p><span class="text-main-blue">Facture : </span><?=$infoLitige[0]['facture'] .' du '.$infoLitige[0]['datefacture']?></p>


	</div>
	<div class="col-2 text-right">
		<?php if ($infoLitige[0]['id_robbery'] !=null): ?>
			<a href="xl-robbery.php?id=<?=$infoLitige[0]['id_robbery']?>" class="img-overlay"></a>
		<?php endif ?>

	</div>
</div>

<div class="row">
	<div class="col">
		<table class="table light-shadow">
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
					<th class="align-top">PJ</th>
					<th class="align-top pt-1"><img src="../img/litiges/serial-icon-title.png"></th>

				</tr>
			</thead>
			<tbody>
				<?php foreach ($infoLitige as $prod): ?>

					<?php
					$pj='';

					if($prod['pj']!=''){
						$pj=createFileLink($prod['pj']);
					}

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
					if($prod['serials']){
						$serialIcon="serial-ok.png";
					}else{
						$serialIcon="serial-ko.png";
					}
					?>
					<?php if ($prod['inversion'] ==""): ?>
						<tr <?=$classBoxHead?>>
							<?php if (empty($prod['occ_article_palette'])): ?>
								<td><?=$prod['article']?></td>
								<td><?=$prod['dossier_gessica']?></td>
								<?php else: ?>
									<td colspan="2">Palette Occasion <?= OccHelpers::getPaletteNameByArticlePalette($pdoOcc,$prod['occ_article_palette'])?></td>
								<?php endif ?>

								<td><?=$prod['palette']?></td>
								<td><?=$boxIco.$prod['descr']?><br>Ean : <?=$prod['ean']?></td>
								<td><?=$prod['fournisseur']?></td>
								<td><?=$prod['reclamation']?></td>
								<td class="text-right"><?=$prod['qte_litige']?></td>
								<td class="text-right"><?=number_format((float)$prod['valo_line'],2,'.','')?>&euro;</td>
								<td class="text-right"><?=$pj?></td>
								<td><a href="#largeModal" data-toggle="modal" data-id="<?=$prod['id_detail']?>"><img src="../img/litiges/<?=$serialIcon?>"></a></td>
							</tr>

							<?php else: ?>

								<tr class="text-reddish">
									<td><?=$prod['article']?></td>
									<td><?=$prod['dossier_gessica']?></td>
									<td><?=$prod['palette']?></td>
									<td><?=$prod['descr']?><br>Ean : <?=$prod['ean']?></td>
									<td><?=$prod['fournisseur']?></td>
									<td><?=$prod['reclamation']?></td>
									<td class="text-right"><?=$prod['qte_litige']?></td>
									<td class="text-right"><?=number_format((float)$prod['tarif']/$prod['qte_cde']*$prod['qte_litige'],2,'.','')?>&euro;</td>
									<td class="text-right"><?=$pj?></td>

									<td><a href="#largeModal" data-toggle="modal" data-id="<?=$prod['id_detail']?>">SN</a></td>

								</tr>
								<?php if ($prod['inv_article']==''): ?>
									<tr class="text-center text-reddish">
										<td colspan="10">Produit reçu à la place de la référence ci-dessus :</td>
									</tr>
									<tr class="text-reddish">
										<td colspan="4">Produit non trouvé - EAN saisi :</td>

										<td colspan="2" class="text-left"><?=$prod['inversion']?></td>

										<td class="text-right"><?=$prod['inv_qte']?></td>
										<td class="text-right"></td>
										<td class="text-right"></td>

									</tr>
									<tr class="text-center bg-reddish text-white">
										<td colspan="10" class="text-right">&nbsp;</td>

									</tr>
									<tr>
										<td colspan="10" class="text-right">&nbsp;</td>
									</tr>

									<?php else: ?>
										<?php $valoInv=round( $prod['inv_qte']*$prod['inv_tarif'],2); ?>
										<tr class="text-center text-reddish"><td colspan="11">Produit reçu à la place de la référence ci-dessus :</td></tr>
										<tr class="text-reddish">
											<td><?=$prod['inv_article']?></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?=$prod['inv_descr']?></td>
											<td><?=$prod['inv_fournisseur']?></td>
											<td></td>
											<td class="text-right"><?=$prod['inv_qte']?></td>
											<td class="text-right"><?=number_format((float)$valoInv,2,'.','')?>&euro;</td>
											<td class="text-right"></td>
											<td class="text-center"></td>
										</tr>
										<tr class="text-center bg-reddish text-white">
											<td colspan="8" class="text-right"><?=number_format((float)$prod['valo_line'],2,'.','')?>&euro;</td>
											<td colspan="2"></td>

										</tr>
										<tr>
											<td colspan="10" class="text-right">&nbsp;</td>
										</tr>
									<?php endif ?>

								<?php endif ?>

							<?php endforeach ?>

						</tbody>
					</table>

				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<a href="edit-litige.php?id=<?=$prod['id_main']?>" class="btn btn-primary"><i class="fas fa-tools pr-3"></i>Modifier</a>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?= $prod['valo']?> </p>
					<p><?= $articleAZero?></p>

				</div>
			</div>



