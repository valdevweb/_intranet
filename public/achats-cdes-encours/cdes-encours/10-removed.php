	<div class="row">
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="1000" id="permanent" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1000)?"checked":""?>>
				<label class="form-check-label" for="permanent">Permanent</label>
			</div>
		</div>
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="1" id="op" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1)?"checked":""?>
				>
				<label class="form-check-label" for="permanent">Opérations</label>
			</div>
		</div>
		<div class="col-auto">
			<div class="form-check">
				<input class="form-check-input" type="radio" value="9" id="permanent" name="dossier"
				<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==9)?"checked":""?>
				>
				<label class="form-check-label" for="permanent">Permanent et opération</label>
			</div>
		</div>
	</div>