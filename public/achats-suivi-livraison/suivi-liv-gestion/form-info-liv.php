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
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">


			<div class="row">
				<div class="col">

					<input type="hidden" class="form-control" name="update" value="<?=$inInfoLiv??0?>">
					<?php foreach ($listArticle as $key => $article): ?>
						<div class="row bg-grey py-2">
							<div class="col-lg-2">
								<div class="font-weight-bold">Article : </div>
								<?=$article['article']?>
							</div>
							<div class="col">
								<div class="font-weight-bold">Désignation : </div>
								<?=$article['libelle']?>
							</div>
							<div class="col">
								<div class="font-weight-bold">Marque :</div>
								<?=$article['marque']?>

							</div>
							<div class="col-lg-3">
								<div class="font-weight-bold">Ean :</div>
								<?=$article['ean']?>
							</div>
						</div>

						<div class="row mt-1">
							<div class="col py-3 border rounded mr-1">
								<div class="row">
									<div class="col text-center text-main-blue font-weight-boldless pb-2">
										Deux lundis avant :
									</div>
								</div>
								<div class="row">
									<div class="col-lg-2">
										<div class="form-group">
											<label class="font-italic">Reçu :</label>
											<input type="text" class="form-control form-primary" pattern="[0-9]+" title="Merci de ne saisir que des chiffres" name="recu_deux[<?=$article['id']?>]" value="<?=$article['recu_deux']??""?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label class="font-italic">Information livraison :</label>
											<input type="text" class="form-control form-primary" name="info_livraison_deux[<?=$article['id']?>]" value="<?=$article['info_livraison_deux']??""?>">
										</div>
									</div>
								</div>
							</div>
							<div class="col py-3 border rounded ml-1">
								<div class="row">
									<div class="col text-center text-orange font-weight-boldless pb-2">
										Un lundi avant :
									</div>
								</div>
								<div class="row">
									<div class="col-lg-2">
										<div class="form-group">
											<label class="font-italic">Reçu :</label>
											<input type="text" class="form-control form-warning" pattern="[0-9]+" title="Merci de ne saisir que des chiffres" name="recu[<?=$article['id']?>]" value="<?=$article['recu']??""?>">
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label class="font-italic">Information livraison :</label>
											<input type="text" class="form-control form-warning" name="info_livraison[<?=$article['id']?>]" value="<?=$article['info_livraison']??""?>">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col border rounded py-3 mt-1 mb-3">
								<div class="row">
									<div class="col  font-weight-boldless">
										Article de remplacement :
									</div>
								</div>

								<div class="row">
									<div class="col-lg-3">
										<div class="form-group">
											<label class="font-italic">Code article :</label>
											<input type="text" class="form-control" name="article_remplace[<?=$article['id']?>]" value="<?=$article['article_remplace']??''?>" >
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label class="font-italic">Ean :</label>
											<input type="text" class="form-control" name="ean_remplace[<?=$article['id']?>]" value="<?=$article['ean_remplace']??''?>">
										</div>
									</div>
									<div class="col-lg-3"></div>

								</div>
							</div>
						</div>



						<input type="hidden" class="form-control" name="article[<?=$article['id']?>]" value="<?=$article['article']?>">
						<?php if (!isset($inInfoLiv) || $inInfoLiv==0): ?>
							<input type="hidden" class="form-control" name="dossier[<?=$article['id']?>]" value="<?=$article['dossier']?>">
							<input type="hidden" class="form-control" name="libelle[<?=$article['id']?>]" value="<?=$article['libelle']?>">
							<input type="hidden" class="form-control" name="ean[<?=$article['id']?>]" value="<?=$article['ean']?>">
							<input type="hidden" class="form-control" name="gt[<?=$article['id']?>]" value="<?=$article['gt']?>">
							<input type="hidden" class="form-control" name="marque[<?=$article['id']?>]" value="<?=$article['marque']?>">
							<input type="hidden" class="form-control" name="fournisseur[<?=$article['id']?>]" value="<?=$article['fournisseur']?>">
							<input type="hidden" class="form-control" name="cnuf[<?=$article['id']?>]" value="<?=$article['cnuf']?>">
							<input type="hidden" class="form-control" name="deee[<?=$article['id']?>]" value="<?=$article['deee']?>">
							<input type="hidden" class="form-control" name="ppi[<?=$article['id']?>]" value="<?=$article['ppi']?>">
							<input type="hidden" class="form-control" name="code_op" value="<?=$listArticle[0]['code_op']?>">
						<?php endif ?>
					<?php endforeach ?>

				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="save">Enregistrer</button>
				</div>
			</div>
		</form>
	</div>
</div>