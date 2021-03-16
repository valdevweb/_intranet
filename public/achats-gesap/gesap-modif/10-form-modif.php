<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="op">Nom de l'opération :</label>
				<input type="text" class="form-control form-primary" name="op" id="op" required value="<?=($gesap['op'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="salon">Salon : </label>
				<input type="text" class="form-control form-primary" name="salon" id="salon" required value="<?=($gesap['salon'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="cata">Catalogue : </label>
				<input type="text" class="form-control form-primary" name="cata" id="cata" required value="<?=($gesap['cata'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="code_op">Code opération :</label>
				<input type="text" class="form-control form-primary" name="code_op" id="code_op" required value="<?=($gesap['code_op'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="date_remonte">Date de remontée :</label>
				<input type="date" class="form-control form-primary" name="date_remonte" id="date_remonte" required value="<?=($gesap['date_remonte'])??""?>">
			</div>
		</div>
	</div>
	<div class="row mb-2">
		<div class="col">
			Guide d'achat :
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="ga_num">Numéro du guide d'achat :</label>
				<input type="text" class="form-control form-primary" name="ga_num" id="ga_num" value="<?=($gesap['ga_num'])??""?>">
			</div>
		</div>
		<div class="col-9 bg-blue-input rounded pt-2">
			<div class="row">
				<div class="col"  id="filename-ga">
					<p><span class="text-main-blue font-weight-bold">Fichier actuel : <br></span></p>
					<?=($gesap['ga_file'])??""?>
				</div>
				<div class="col-auto">
					<div class="form-group">
						<label class="btn btn-upload-primary btn-file text-center">
							<input type="file" name="file_ga" class='form-control-file'>
							Sélectionner
						</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col" id="file-ga-msg"></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="cmt">Commentaire :</label>
				<input type="text" class="form-control form-primary" name="cmt" id="cmt" value="<?=($gesap['cmt'])??""?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			Autres fichiers joints
		</div>
	</div>
	<div class="row ml-1">
		<div class="col bg-blue-input  rounded pt-2">
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

	<div class="row mt-3 pb-5">
		<div class="col text-right">
			<button class="btn btn-primary" name="update">Modifier</button>
		</div>
	</div>
</form>