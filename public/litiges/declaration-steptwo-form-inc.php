<div class="row">
	<div class="col p-5">

		<?php
		$subForm=0;
		?>
		<?php foreach ($fLitige as $keyLitige => $litige): ?>
			<?php if($litige['box_tete']!=1):?>
				<!-- info produit -->
				<div class="row yellow-box">
					<div class="col">
						<h5 class="khand heavy spacy  pt-3 ">Produit : <?=$litige['descr'].' - Art. : '.$litige['article']?></h5>
						<div class="row no-gutters">
							<div class="col ">
								<span class="libelle">Fournisseur : </span>
								<span><?=$litige['fournisseur']?></span>
								<span class="libelle pl-5"> EAN : </span>
								<span><?=$litige['ean']?></span>
								<span class="libelle pl-5"> Dossier : </span>
								<span><?=$litige['dossier_gessica']?></span>
							</div>
						</div>
						<div class="row pb-3">
							<div class="col">
								<span class="libelle">Quantité : </span>
								<span><?=$litige['qte_cde']?></span>
								<span class="libelle pl-5">Palette : </span>
								<span><?=$litige['palette']?></span>
								<span class="libelle pl-5">Facture : </span>
								<span><?=$litige['facture']?></span>
								<span class="libelle pl-5">Date facture : </span>
								<span><?=$litige['datefac']?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row border pt-3 mb-5">
					<div class="col">
						<div class="row">
							<div class="col-4">
								<p class="khand heavy">Motif de la réclamation :</p>
								<div class="form-group">
									<select class="form-control" name="form_motif[<?=$keyLitige?>]"  id="motif<?=$litige['detail_id']?>"required>
										<option value="">Sélectionnez</option>
										<?php foreach ($fMotif as $motif){
											$classContrainte="";
											if ($motif['id_contrainte']==1) {
												$classContrainte="contrainte";
											}
											echo '<option value="'.$motif['id'].'" class="'.$classContrainte.'">'.$motif['reclamation'].'</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-3">
								<p class="khand heavy">Quantité concernée en UV :</p>
								<div class="form-group">
									<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" name="form_qte[<?=$keyLitige?>]" required>
									<input type="hidden" value="<?=$litige['detail_id']?>" name="form_id[<?=$keyLitige?>]">
								</div>
							</div>
						</div>
						<!-- hidden fields showed only if manquant => 8 -->
						<div class="hidden" id="toggleMissing<?=$litige['detail_id']?>">
							<div class="row">
								<div class="col-12  pl-3">
									<p class="text-reddish">Avez vous reçu un produit non commandé à la place des produits manquants ?</p>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" value="1" id="radio-inv-oui-<?=$litige['detail_id']?>" name="radio-inv[<?=$keyLitige?>]">
										<label class="form-check-label" for="radio-inv">Oui</label>
										<input class="form-check-input ml-3" type="radio" value="0" id="radio-inv-non-<?=$litige['detail_id']?>" name="radio-inv[<?=$keyLitige?>]">
										<label class="form-check-label" for="radio-inv-non">Non</label>
									</div>
								</div>
							</div>
						</div>
						<div class="hidden" id="toggleEan<?=$litige['detail_id']?>">
							<div class="row">
								<div class="col-4">
									<p class="khand heavy">Ean article reçu non commandé :
									</p>
									<div class="form-group">
										<input type="text" class="form-control" name="ean_inv[<?=$keyLitige?>]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" id="ean-received">
									</div>
								</div>
								<div class="col-3">
									<p class="khand heavy">Quantité UV reçue :
									</p>
									<div class="form-group">
										<input type="text" class="form-control" name="qte_inv[<?=$keyLitige?>]">
									</div>
								</div>
								<div class="col"></div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div  id="warningmotif<?=$litige['detail_id']?>" class="alert alert-danger">* Une photo du produit et de l'emballage est obligatoire pour ce type de réclamation</div>
							</div>
						</div>
						<!-- fin hidden fields -->
						<div class="row mt-3">
							<div class="col">
								<p><span class="khand heavy">Photos /vidéos :</span><br>
									<span class="circle-icon"><i class="fas fa-lightbulb"></i></span><span class="text-reddish pl-3 heavy tighter">Maintenez la touche CTRL enfoncée pour sélectionner plusieurs fichiers</span>
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col-auto">
								<div class="form-group">
									<label class="btn btn-upload btn-file text-center"><input name="form_file[<?=$keyLitige?>][]" id="form_file<?=$litige['detail_id']?>" type="file" multiple class="form-control-file"><i class="fas fa-file-image pr-3"></i>Sélectionner</label>
								</div>
							</div>
							<!-- // upload filename -->
							<div class="col" id="<?=$litige['detail_id']?>"></div>
						</div>
					</div>
				</div>
			<?php endif ?>
			<?php $subForm++; ?>
		<?php endforeach ?>
		<p class="khand heavy bigger">Commentaires : </p>
		<div class="form-group">
			<textarea class="form-control" name="form_com"><?= isset($cmt)? str_replace('<br />', "\n",'Demande d\'origine du magasin : <br />' .$cmt):''?></textarea>
		</div>
	</div>
</div>
<p class="pt-5 text-right upper"><button class="btn btn-primary" type="submit" name="submit">Envoyer</button></p>
