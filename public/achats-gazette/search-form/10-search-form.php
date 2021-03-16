	<div class="row">
		<div class="col-lg-2"></div>
		<div class="col">
			<div class="alert alert-primary">
				<i class="fas fa-lightbulb pr-3"></i>Vous pouvez ne renseigner que les champs que vous trouvez utiles pour faire votre recherche.<br>
				<i class="fas fa-lightbulb pr-3"></i>Les gazettes ne sont conservées que 60 jours
			</div>
		</div>
		<div class="col-lg-2"></div>

	</div>
	<div class="row">
		<div class="col border p-3">


			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>#results" method="post">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="strg">Mot clé : </label>
							<input type="text" class="form-control form-primary" name="strg" id="strg">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="main_cat">Catégorie :</label>
							<select class="form-control form-primary" name="main_cat" id="main_cat" >
								<option value="">Sélectionner</option>
								<option value="1">BTLEC</option>
								<option value="2">GALEC</option>
							</select>
						</div>

					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="cat">Type d'information :</label>
							<select class="form-control form-primary" name="cat" id="cat" >
								<option value="">Sélectionner</option>
							</select>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="date_start">Du :</label>
							<input type="date" class="form-control form-primary" name="date_start" id="date_start" value=<?=$dayOneStr?>>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="date_end">Au : </label>
							<input type="date" class="form-control form-primary" name="date_end" id="date_end" value="<?=date('Y-m-d')?>">
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