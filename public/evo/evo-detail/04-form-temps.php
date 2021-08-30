<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="row">
		<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
			Temps passé  :
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="minutes">Temps passé :</label>
				<input type="text" class="form-control" name="minutes" id="minutes" placeholder="en minutes" pattern="[0-9]+" title="Seuls les chiffres sont autorisés" required>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="date_exec">Date :</label>
				<input type="date" class="form-control" name="date_exec" id="date_exec" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right">
			<button class="btn btn-dark" name="add_temps">Valider</button>
		</div>
	</div>
</form>