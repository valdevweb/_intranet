		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="prospectus">Prospectus : </label>
								<input type="text" class="form-control" name="prospectus" id="prospectus" title="Veuillez supprimer les espaces"  pattern="[^' ']+">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="date_start">Date de début : </label>
								<input type="date" class="form-control" name="date_start" id="date_start">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="date_end">Date de fin : </label>
								<input type="date" class="form-control" name="date_end" id="date_end">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="label pb-3">Uploader le fichier ficWOPC :</div>
							<div class="form-group">
								<label class="btn btn-upload btn-file text-center">
									<input type="file" name="fic" class='form-control-file'>
									<i class="fas fa-file pr-3"></i>Sélectionner
								</label>
							</div>
						</div>
						<div class="col" id="filenames">

						</div>
					</div>
					<div class="row">
						<div class="col" id="file-msg"></div>
					</div>
					<div class="row pb-5">
						<div class="col text-right">
							<button class="btn btn-primary" name="add_prosp">Valider</button>

						</div>
					</div>
				</form>
			</div>
		</div>