	<div class="row border mb-3">
		<div class="col p-3">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-3">
						<div class="form-group">
							<label for="search_string">Valeur recherchée :</label>
							<input type="text" class="form-control" name="search_string" id="search_string">
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col">
								Recherche sur
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-auto">
								<div class="form-check">
									<input class="form-check-input" type="radio" value="article"  name="field">
									<label class="form-check-label" for="article">Code article</label>
								</div>
							</div>
							<div class="col-auto">
								<div class="form-check">
									<input class="form-check-input" type="radio" value="palette"  name="field">
									<label class="form-check-label" for="palette">Palette</label>
								</div>
							</div>
							<div class="col-auto">
								<div class="form-check">
									<input class="form-check-input" type="radio" value="id_casse"  name="field">
									<label class="form-check-label" for="id_casse">Numéro de casse</label>
								</div>
							</div>
							<div class="col-auto">
								<div class="form-check">
									<input class="form-check-input" type="radio" value="ean" id="ean" name="field">
									<label class="form-check-label" for="ean">EAN</label>
								</div>
							</div>
							<div class="col-auto">
								<div class="form-check">
									<input class="form-check-input" type="radio" value="btlec" id="btlec" name="field">
									<label class="form-check-label" for="btlec">Code BTLec</label>
								</div>
							</div>
							<div class="col-auto text-right">
								<button class="btn btn-primary"  type="submit" name="search" id="submit_search"><i class="fas fa-search"></i></button>
								<button class="btn secTwo" name="clear_form"><i class="fas fa-eraser"></i></button>

							</div>
							<div class="col-uto text-right">
							</div>
						</div>
					</div>
				</div>

				<div class="row">

				</div>
			</form>
		</div>
	</div>