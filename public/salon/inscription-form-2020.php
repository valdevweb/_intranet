
<div class="row">
	<div class="col  border p-4">
		<form method="post" id="add" action="<?=$_SERVER['PHP_SELF']?>">
			<!-- titre 1 -->
			<div class="row">
				<div class="col text-blue heavy pb-3">
					<i class="fa fa-user-circle pr-3"></i>Informations participant :
				</div>
			</div>
			<!-- form container one -->
			<div class="row">
				<div class="col alert alert-primary pt-4">
					<!-- row 1 -->
					<div class="row">

						<div class="col">
							<div class="form-group">
								<select name="genre" class="form-control" required>
									<option value="">Madame/Monsieur *</option>
									<option value="Madame">Madame</option>
									<option value="Monsieur">Monsieur</option>

								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" name="nom" placeholder="nom*" class="form-control" value="<?=$nom?>" required >
							</div>

						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" name="prenom" placeholder="prenom*" class="form-control" value="<?=$prenom?>" required>
							</div>
						</div>
					</div>
					<!-- row 2 -->
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<input type="email" class="form-control" placeholder="email*" value="<?=$email?>" name="email" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<select name="fonction" id="fonction" class="form-control" required>
									<option value="">fonction*</option>
									<?php
									foreach ($fonctionList as $fonction) {
										echo '<option value="'.$fonction['id'].'">'.$fonction['fonction'].'</option>';
									}

									?>
								</select>
							</div>
						</div>
						<div class="col">

						</div>
					</div>
					<!-- row 3 -->
					<div class="row">
						<div class="col">
							<span class="pl-3"><i>* : champs obligatoires</i></span><br>

						</div>
					</div>
				</div>
			</div>
			<!-- ./form container 1 -->
			<!-- titre 2 -->
			<div class="row">
				<div class="col text-blue heavy py-3">
					<i class="fas fa-calendar-alt pr-3"></i> Dates :
				</div>
			</div>
			<!-- form container 2 -->
			<div class="row">
				<div class="col">
					<!-- container mardi -->
					<div class="row alert alert-primary">
						<div class="col">
							<div class="row">
								<div class="col-4">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="mardi" id="mardi" value="1">
										<label for="mardi" class="form-check-label">Mardi</label>
									</div>
								</div>
								<div class="col"></div>
							</div>
							<!-- repas mardi -->
							<div class="row hidden" id="repas-mardi">
								<div class="col-auto">
									<span class="pr-5"><i class="fas fa-utensils pr-3"></i>Repas :</span>
									<div class="form-check-inline">
										<input type="radio" class="form-check-input" name="repas-mardi" id="repas-mardi-oui" value="1">
										<label for="repas-mardi-oui" class="form-check-label pr-5">Oui</label>
										<input type="radio" class="form-check-input" name="repas-mardi" id="repas-mardi-non" value="0">
										<label for="repas-mardi-non" class="form-check-label">Non</label>
									</div>
								</div>
								<div class="col"></div>
							</div>
						</div>
					</div>
					<!-- ./repas mardi -->
				</div>
				<div class="col">
					<div class="row alert alert-primary">
						<div class="col">
							<div class="row ">
								<div class="col-4">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="mercredi" id="mercredi" value="1">
										<label for="mercredi" class="form-check-label">Mercredi</label>
									</div>
								</div>
								<div class="col"></div>
							</div>
							<!-- repas mercredi -->
							<div class="row hidden" id="repas-mercredi">
								<div class="col-auto">
									<span class="pr-5"><i class="fas fa-utensils pr-3"></i>Repas :</span>
									<div class="form-check-inline">
										<input type="radio" class="form-check-input" name="repas-mercredi" id="repas-mercredi-oui" value="1">
										<label for="repas-mercredi-oui" class="form-check-label pr-5">Oui</label>
										<input type="radio" class="form-check-input" name="repas-mercredi" id="repas-mercredi-non" value="0">
										<label for="repas-mercredi-non" class="form-check-label">Non</label>
									</div>
								</div>
								<div class="col"></div>
							</div>
							<!-- ./repas mercredi -->
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col text-center">
					<button class="btn btn-primary" type="submit" name="submit">Ajouter</button>
				</div>
			</div>

		</form>
	</div>
</div>






