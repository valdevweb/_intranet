<div class="row pb-5">
	<div class="col border rounded p-3">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">
				<div class="col pr-3">
					<span class="text-main-blue font-weight-boldless"><span class="text-orange"><i class="fas fa-angle-double-right pr-3"></i></span>Soit par numéro d'opération</span>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="code_op">Code de l'opération :</label>
						<input type="text" class="form-control" name="code_op" id="code_op">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="search_by_cata">Afficher</button>
				</div>
			</div>
		</form>
	</div>

	<div class="col-lg-8 border rounded p-3 ml-3">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">
				<div class="col">
					<span class="text-main-blue font-weight-boldless"><span class="text-orange"><i class="fas fa-angle-double-right pr-3"></i></span>Soit par sélection de semaine</span>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="week">Semaine de l'opération :</label>
						<input type="week" class="form-control" name="week" id="week">
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="op">Opérations :</label>
						<select class="form-control" name="op" id="op">
							<option value="">Sélectionner</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="search_by_week">Afficher</button>
				</div>
			</div>
		</form>
	</div>
</div>
