<div class="row">
	<div class="col">
		<div class="row">
			<div class="col">
				<h6 class="text-main-blue pb-3"><span class="step">2</span>Saisie des informations sur l'opération <?=$listArticle[0]['code_op']?></h6>
			</div>
		</div>
	</div>
</div>
<div class="alert alert-danger">Dans le champ "reçu", merci de ne saisir que le chiffre, pas le sigle %</div>
<div class="row pb-5">
	<div class="col">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">


			<div class="row">
				<div class="col">
					<div class="row py-2 border-bottom">
						<div class="col-lg-2">
							<div class="font-weight-bold">Article : </div>
						</div>
						<div class="col-lg-4">
							<div class="font-weight-bold">Désignation : </div>
						</div>
						<div class="col-lg-2">
							<div class="font-weight-bold">Marque :</div>

						</div>
						<div class="col-lg-3">
							<div class="font-weight-bold">Ean :</div>
						</div>
						<div class="col-lg-1 text-right">
						</div>
					</div>

					<?php foreach ($listArticle as $key => $article): ?>

					<input type="hidden" class="form-control" name="id_op" value="<?=$listInfoLivraison[$article['article']][0]['id_op']??""?>">

						<div class="row py-2 border-bottom test" id="<?=$article['id']?>">
							<div class="col-lg-2">
								<?=$article['article']?>
							</div>
							<div class="col-lg-4">
								<?=$article['libelle']?>
							</div>
							<div class="col-lg-2">
								<?=$article['marque']?>

							</div>
							<div class="col-lg-3">
								<?=$article['ean']?>
							</div>
							<div class="col-lg-1 text-right">
								<span class="btn btn-primary show-form" data-btn-id="<?=$article['id']?>">Saisir</span>
							</div>
						</div>

						<div class="row hidden-form " data-form-id="<?=$article['id']?>">
							<div class="col">
								<!-- info livraison -->
								<div class="row">
									<div class="col bg-light-grey border m-3 rounded px-3">
										<div class="row mt-1">
											<div class="col py-3 mr-1">
												<div class="row">
													<div class="col text-center text-main-blue font-weight-boldless pb-2">
														Deux lundis avant :
													</div>
												</div>
												<div class="row">
													<div class="col-lg-2">
														<div class="form-group">
															<label class="font-italic">Reçu :</label>
															<input type="text" class="form-control form-primary" pattern="[0-9]+" title="Merci de ne saisir que des chiffres" name="recu_deux[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['recu_deux']??""?>">
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label class="font-italic">Information livraison :</label>
															<input type="text" class="form-control form-primary" name="info_livraison_deux[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['info_livraison_deux']??""?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col py-3  ml-1">
												<div class="row">
													<div class="col text-center text-orange font-weight-boldless pb-2">
														Un lundi avant :
													</div>
												</div>
												<div class="row">
													<div class="col-lg-2">
														<div class="form-group">
															<label class="font-italic">Reçu :</label>
															<input type="text" class="form-control form-warning" pattern="[0-9]+" title="Merci de ne saisir que des chiffres" name="recu[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['recu']??""?>">
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label class="font-italic">Information livraison :</label>
															<input type="text" class="form-control form-warning" name="info_livraison[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['info_livraison']??""?>">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col mt-1 mb-3">
												<div class="row">
													<div class="col font-weight-boldless">
														Article de remplacement :
													</div>
												</div>

												<div class="row">
													<div class="col-lg-3">
														<div class="form-group">
															<label class="font-italic">Code article :</label>
															<input type="text" class="form-control" name="article_remplace[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['article_remplace']??''?>" >
														</div>
													</div>
													<div class="col-lg-3">
														<div class="form-group">
															<label class="font-italic">Ean :</label>
															<input type="text" class="form-control" name="ean_remplace[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['ean_remplace']??''?>">
														</div>
													</div>


												</div>
											</div>
										</div>
										<div class="row">
											<div class="col font-weight-boldless">
												Erratum :
											</div>
										</div>
										<div class="row">
											<div class="col-9 bg-white rounded pt-2">
												<div class="row ">
													<div class="col"  id="filename-erratum">
														<?php if (isset($listInfoLivraison[$article['article']][0]['erratum'])): ?>
														<p><span class="font-weight-bold">Fichier uploadé : <?=$listInfoLivraison[$article['article']][0]['erratum']?><br></span></p>

															<?php else: ?>
														<p><span class="font-weight-bold">Fichier sélectionné : <br></span></p>


														<?php endif ?>
													</div>
													<div class="col-auto">
														<div class="form-group">
															<label class="btn btn-upload-orange btn-file text-center">
																<input type="file" name="file_erratum[<?=$article['id']?>]" class='form-control-file'>
																Sélectionner
															</label>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col" id="file-erratum-msg"></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col text-right mt-4 pt-2">
												<button class="btn btn-orange" name="save[<?=$article['id']?>]">Enregistrer</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<input type="hidden" class="form-control" name="article[<?=$article['id']?>]" value="<?=$article['article']?>">
							<input type="hidden" class="form-control" name="code_op" value="<?=$listArticle[0]['code_op']?>">

						<!-- pas d'info livraison saisie pour l'article donc absent de la table article -->
						<?php if (!isset($listInfoLivraison[$article['article']][0])): ?>

							<input type="hidden" class="form-control" name="dossier[<?=$article['id']?>]" value="<?=$article['dossier']?>">
							<input type="hidden" class="form-control" name="libelle[<?=$article['id']?>]" value="<?=$article['libelle']?>">
							<input type="hidden" class="form-control" name="ean[<?=$article['id']?>]" value="<?=$article['ean']?>">
							<input type="hidden" class="form-control" name="gt[<?=$article['id']?>]" value="<?=$article['gt']?>">
							<input type="hidden" class="form-control" name="marque[<?=$article['id']?>]" value="<?=$article['marque']?>">
							<input type="hidden" class="form-control" name="fournisseur[<?=$article['id']?>]" value="<?=$article['fournisseur']?>">
							<input type="hidden" class="form-control" name="cnuf[<?=$article['id']?>]" value="<?=$article['cnuf']?>">
							<input type="hidden" class="form-control" name="deee[<?=$article['id']?>]" value="<?=$article['deee']?>">
							<input type="hidden" class="form-control" name="ppi[<?=$article['id']?>]" value="<?=$article['ppi']?>">
							<input type="hidden" class="form-control" name="exist[<?=$article['id']?>]" value="false">
							<?php else: ?>
							<input type="hidden" class="form-control" name="exist[<?=$article['id']?>]" value="true">
							<input type="hidden" class="form-control" name="id_article_table_article[<?=$article['id']?>]" value="<?=$listInfoLivraison[$article['article']][0]['id']??''?>">
						<?php endif ?>
					<?php endforeach ?>

				</div>
			</div>

		</form>
	</div>
</div>