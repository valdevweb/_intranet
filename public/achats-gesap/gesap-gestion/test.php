<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">

	<div class="row">
		<div class="col">
			Autres fichiers joints
		</div>
	</div>
	<div class="row ml-1">
		<div class="col">
			<div class="row">
				<div class="col" id="otherfile">
					<input type="file" name="file[]" class="dragndropfile" multiple>
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