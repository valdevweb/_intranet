<div class="row pb-5" id="modif-prosp">
	<div class="col border p-3">
		<?php if (isset($prospMod) && !empty($prospMod)): ?>

		<div class="row my-3">
			<div class="col">
				<h6 class="text-main-blue">Modification du prospectus <?=$prospMod['prospectus']?></h6>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?prosp-id-mod='.$_GET['prosp-id-mod']?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="prospectus">Prospectus : </label>
								<input type="text" class="form-control form-primary" name="prospectus" value="<?=$prospMod['prospectus']?>" id="prospectus" title="Veuillez supprimer les espaces"  pattern="[^' ']+">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Date de début : </label>
								<input type="date" class="form-control form-primary" name="date_start" value="<?=$prospMod['date_start']?>" >
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Date de fin : </label>
								<input type="date" class="form-control form-primary" name="date_end" value="<?=$prospMod['date_end']?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="link">Liens :</label>
								<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour ajouter plusieurs liens, veuillez les séparer par une virgule et un espace</div>
								<input type="text" class="form-control form-primary" name="link" id="link" value="<?=($_POST['link'])??""?>" >
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="label pb-3">Fichier ficWOPC :</div>
						</div>
					</div>
					<div class="row ml-1">
						<div class="col-lg-6 bg-blue-input rounded pt-2">
							<div class="row">
								<div class="col" id="file-name-mod">
									<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>
								</div>
								<div class="col-auto">

									<div class="form-group">
										<label class="btn btn-upload-primary btn-file">
											<input type="file" name="fic-mod" class='form-control-file'>
											<i class="fas fa-file pr-3"></i>Sélectionner
										</label>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col" id="file-msg-mod"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="label pb-3">Autres fichiers joints :</div>
						</div>
					</div>
					<div class="row ml-1">
						<div class="col bg-blue-input rounded pt-2">
							<div class="row">
								<div class="col"  id="filename-other">
									<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>
								</div>
								<div class="col-auto">
									<div class="form-group">
										<label class="btn btn-upload-primary btn-file text-center">
											<input type="file" name="file_other[]" class='form-control-file' multiple>
											Sélectionner
										</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col" id="file-other-msg"></div>
							</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col text-right">
							<button class="btn btn-primary" name="modify_prosp">Valider</button>

						</div>
					</div>
				</form>
			</div>
		</div>
		<?php else: ?>
			<div class="alert alert-danger">Ce prospectus n'existe pas</div>
		<?php endif ?>

	</div>
</div>