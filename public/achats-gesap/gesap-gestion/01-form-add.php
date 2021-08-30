<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="op">Nom de l'opération :</label>
				<input type="text" class="form-control form-primary" name="op" id="op" required value="<?=($_POST['op'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="salon">Salon : </label>
				<input type="text" class="form-control form-primary" name="salon" id="salon" required value="<?=($_POST['salon'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="cata">Catalogue : </label>
				<input type="text" class="form-control form-primary" name="cata" id="cata" required value="<?=($_POST['cata'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="code_op">Code opération :</label>
				<input type="text" class="form-control form-primary" name="code_op" id="code_op" required value="<?=($_POST['code_op'])??""?>">
			</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="date_remonte">Date de remontée :</label>
				<input type="date" class="form-control form-primary" name="date_remonte" id="date_remonte" required value="<?=($_POST['date_remonte'])??""?>">
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
				<label for="ga_name">Numéro du guide d'achat :</label>
				<input type="text" class="form-control form-primary" name="ga_name" id="ga_name">
			</div>
		</div>
		<div class="col-9">
			<div class="row">
				<div class="col" id="ga">
					<input type="file" name="file" class="dragndropfile" multiple="multiple">
					<div class="upload-area uploadfile">
						<p>Glisser/déposer le guide d'achat<br/>ou<br/>cliquez ici pour le sélectionner</p>
					</div>
					<div class="filename"></div>
				</div>
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="cmt">Commentaire :</label>
				<input type="text" class="form-control form-primary" name="cmt" id="cmt">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			Autres fichiers joints
		</div>
	</div>
	<div class="row ml-1">
		<div class="col">
			<div class="row">
				<div class="col" id="otherfile">
					<input type="file" name="file" class="dragndropfile" multiple="multiple">
					<div class="upload-area uploadfile">
						<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
					</div>
					<div class="filename"></div>
					<div class="readablename"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col text-right">
			<button class="btn btn-primary" name="add">Ajouter</button>
		</div>
	</div>
</form>