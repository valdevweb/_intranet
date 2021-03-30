<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col text-center text-main-blue font-weight-bold pb-5 sub-title">
			Produit et date de validité :
		</div>
	</div>
	<div class="row pb-3">
		<div class="col-lg-2">
			<div class="form-group">
				<label for="date_start">Date de début :</label>
				<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=($_POST['date_start'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="date_end">Date de fin :</label>
				<input type="date" class="form-control form-primary" name="date_end" id="date_end" value="<?=($_POST['date_end'])??""?>">
			</div>
		</div>
		<div class="col-lg-1">
			<div class="form-group">
				<label for="gt">GT :</label>
				<input type="text" class="form-control form-primary" name="gt" id="gt" title="Veuillez saisir le numéro de GT" id="gt" value="<?=($_POST['gt'])??""?>" pattern="^(?:[1-9]|0[1-9]|10)$">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="famille">Famille :</label>
				<input type="text" class="form-control form-primary" name="famille" id="famille"  value="<?=($_POST['famille'])??""?>">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="marque">Marque :</label>
				<input type="text"  class="form-control form-primary" name="marque" id="marque"  value="<?=($_POST['marque'])??""?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col border m-3 p-3">
			<div class="row">
				<div class="col text-center text-main-blue sub-title font-weight-bold pb-5">
					Liste des EANS  :
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="font-italic"><i class="fas fa-exclamation-circle pr-2"></i>Vous pouvez soit saisir la liste soit la joindre via un fichier</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="ean"><i class="fas fa-angle-double-right pr-3"></i>Saisie des eans :</label>
						<textarea class="form-control form-primary" name="ean" id="ean" row="3" pattern = "^[0-9]+(, [0-9]+)*$" placeholder="Veuillez séparer les eans par une virgule et un espace"><?=($_POST['ean'])??""?></textarea>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col">
					<div class="pb-3 text-main-blue"><i class="fas fa-angle-double-right pr-3"></i>Fichier des EANS :</div>

				</div>
			</div>
			<div class="row ml-1">
				<div class="col-8 bg-blue-input rounded pt-2" id="filenames-ga">
					<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>

				</div>
				<div class="col-4">
					<div class="form-group">
						<label class="btn btn-upload-primary btn-file text-center">
							<input type="file" name="ean_file" class='form-control-file'>
							Sélectionner
						</label>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col" id="file-msg"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col border m-3 p-3">
			<div class="row">
				<div class="col mb-5 text-main-blue text-center sub-title font-weight-bold ">
					Fichiers de l'ODR :
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour sélectionner plusieurs fichiers, maintenez la touche <strong>ctrl</strong> appuyée lors de la sélection</div>
				</div>
			</div>
			<div class="row ">
				<div class="col-8">
					<div class="row bg-blue-input rounded mx-1 pt-2">
						<div class="col" id="filenames-odr">
							<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span></p>
						</div>
					</div>
					<div class="row">
						<div class="col" id="file-msg-odr"></div>
					</div>
				</div>
				<div class="col-4 pt-2">
					<div class="form-group">
						<label class="btn btn-upload-primary btn-file text-center">
							<input type="file" name="odr_files[]" class='form-control-file' multiple>
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
		</div>
	</div>
	<div class="row">
		<div class="col text-right">
			<button type="submit" class="btn btn-primary" name="add_odr">Ajouter</button>
		</div>
	</div>
</form>