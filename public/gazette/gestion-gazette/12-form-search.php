<div class="row mb-3">
	<div class="col text-main-blue">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">
				<div class="col-lg-4">
					<div class="form-group">
						<label for="strg">Par mot clé :</label>
						<input type="text" class="form-control form-primary" name="strg" id="strg">
					</div>
				</div>
				<div class="col-lg-2 text-center font-weight-bold">ou</div>
				<div class="col">
					<div class="form-group">
						<label for="date_start">Par période :</label>
						<input type="date" class="form-control form-primary" name="date_start" id="date_start">
					</div>
				</div>
				<div class="col mt-4 pt-2">
					<div class="form-group">
						<input type="date" class="form-control form-primary" name="date_end" id="date_end">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="search">Rechercher</button>
				</div>
			</div>
		</form>
	</div>
</div>
