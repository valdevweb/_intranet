<div class="row">
	<div class="col border p-5">
		<?php if (isset($prospMod) && !empty($prospMod)): ?>

		<div class="row my-3">
			<div class="col">
				<h6 class="text-main-blue">Modifier le prospectus <?=$prospMod['prospectus']?></h6>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?prosp-id-mod='.$_GET['prosp-id-mod']?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="prospectus">Prospectus : </label>
								<input type="text" class="form-control" name="prospectus" value="<?=$prospMod['prospectus']?>" id="prospectus" title="Veuillez supprimer les espaces"  pattern="[^' ']+">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Date de début : </label>
								<input type="date" class="form-control" name="date_start" value="<?=$prospMod['date_start']?>" >
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label>Date de fin : </label>
								<input type="date" class="form-control" name="date_end" value="<?=$prospMod['date_end']?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="label pb-3">Uploader le fichier ficWOPC :</div>
							<div class="form-group">
								<label class="btn btn-upload btn-file text-center">
									<input type="file" name="fic-mod" class='form-control-file'>
									<i class="fas fa-file pr-3"></i>Sélectionner
								</label>
							</div>
						</div>
						<div class="col mt-3" id="filenames-mod">
							<?php if (!empty($prospMod['fic'])): ?>
								<div class="text-danger">Si le fichier <?=$prospMod['fic']?> a été modifié, merci de l'uploader à nouveau</div>
								<input type="hidden" class="form-control" name="previous_fic" id="previous_fic" value="<?=$prospMod['fic']?>">
							<?php endif ?>
						</div>
					</div>
					<div class="row">
						<div class="col" id="file-mod-msg"></div>
					</div>
					<div class="row">
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