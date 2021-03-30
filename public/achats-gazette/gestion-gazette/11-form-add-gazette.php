	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="main_cat">Catégorie :</label>
							<select class="form-control form-primary" name="main_cat" id="main_cat" required>
								<option value="">Sélectionner</option>
								<option value="1">BTLEC</option>
								<option value="2">GALEC</option>
							</select>
						</div>

					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="cat">Type d'information :</label>
							<select class="form-control form-primary" name="cat" id="cat" required>
								<option value="">Sélectionner</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3"></div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="date_start">Date de parution</label>
							<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=($_POST['date_start'])??date('Y-m-d')?>" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="titre">Titre :</label>
							<input type="text" class="form-control form-primary" name="titre" id="titre" value="<?=($_POST['titre'])??""?>" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="description">Description* :</label>
							<textarea class="form-control form-primary" name="description" id="description" row="3"><?=($_POST['description'])??""?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="link">Liens* :</label>
							<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour ajouter plusieurs liens, veuillez les séparer par une virgule et un espace</div>

							<input type="text" class="form-control form-primary" name="link" id="link" value="<?=($_POST['link'])??""?>" >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour sélectionner plusieurs fichiers, maintenez la touche <strong>ctrl</strong> appuyée lors de la sélection</div>
					</div>
				</div>
				<div class="row">
					<div class="col-8">
						<div class="row bg-blue-input rounded mx-1 pt-2">
							<div class="col" id="gazette-filenames">
								<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span></p>
							</div>
						</div>
						<div class="row">
							<div class="col" id="file-msg-gazette"></div>
						</div>
					</div>
					<div class="col-4 pt-2">
						<div class="form-group">
							<label class="btn btn-upload-primary btn-file text-center">
								<input type="file" name="gazette_files[]" class='form-control-file' multiple>
								Sélectionner
							</label>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-4" id="zone-noms"></div>
					<div class="col-3" id="zone-ordre"></div>
					<div class="col"></div>
				</div>
				<div class="row mb-5">
					<div class="col text-right">
						<button class="btn btn-primary" name="add-gazette" type="submit">Ajouter</button>
					</div>
				</div>
			</form>
		</div>
	</div>