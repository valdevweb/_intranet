<!-- formulaire de recherche -->
<div class="row mb-3">
	<div class="col bg-ghostwhite border py-3">
		<div class="row">
			<div class="col-6">
				<p class="text-red heavy">Recherche par date et/ou état :</p>
			</div>
		</div>
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">

				<div class="col-auto">
					<div class="form-group">
						<label>Date début</label>
						<input type="date" class="form-control" value="<?= isset($_SESSION['form-data']['date_start']) ? $_SESSION['form-data']['date_start'] :'' ?>" name="date_start">
					</div>

				</div>

				<div class="col-auto">
					<div class="form-group">
						<label>Date Fin</label>
						<input type="date" class="form-control" min="2019-01-01" value="<?=isset($_SESSION['form-data']['date_end']) ? $_SESSION['form-data']['date_end'] :'' ?>" name="date_end">
					</div>
				</div>
				<div class="col-auto">
					<div class="form-group">
						<label>Etat* </label>
						<select name="etat[]"  class="form-control"  multiple>
							<option value="">Sélectionner</option>
							<?php
							foreach ($listEtat as $etat)
							{
								$selected="";
								if(isset($_SESSION['form-data']['etat']) && !empty($_SESSION['form-data']['etat'])){
									if(in_array($etat['id'],$_SESSION['form-data']['etat'])){
										$selected='selected';
									}
									else{
										$selected="";
									}
								}
								echo '<option value="'.$etat['id'].'" '.$selected.'>'.$etat['etat'].'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-group">
						<label for="typo">Typologie :</label>
						<select class="form-control" name="typo[]" id="typo" multiple>
							<option value="">Sélectionner</option>
							<?php

							foreach ($arTypo as $keyTypo =>$value)
							{
								$selected="";
								if(isset($_SESSION['form-data']['typo']) && !empty($_SESSION['form-data']['typo'])){
									if(in_array($keyTypo,$_SESSION['form-data']['typo'])){
										$selected='selected';
									}
									else{
										$selected="";
									}
								}
								echo '<option value="'.$keyTypo.'" '.$selected.'>'.$arTypo[$keyTypo].'</option>';
							}
							?>
						</select>
					</div>

				</div>
				<div class="col-auto">
					<div class="form-group">
						<label>Centrales * </label>
						<select name="centrale[]"  class="form-control"  multiple>
							<option value="">Sélectionner</option>
							<?php
							foreach ($arCentrale as $key => $centrale)
							{
								$selected="";
								if(isset($_SESSION['form-data']['centrale']) && !empty($_SESSION['form-data']['centrale'])){
									if(in_array($key,$_SESSION['form-data']['centrale'])){
										$selected='selected';
									}
									else{
										$selected="";
									}
								}
								echo '<option value="'.$key.'" '.$selected.'>'.$arCentrale[$key].'</option>';
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-black mr-5" type="submit"  name="search_one"><i class="fas fa-search pr-2"></i>Rechercher</button>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<span class="text-small"><i>* Multisélection : maintenir la touche contrôle pour séléctionner plusieurs élements</i></span>
				</div>
			</div>

		</form>
	</div>
</div>
<div class="row mb-3">
	<div class="col border bg-ghostwhite py-3">

		<div class="row">
			<div class="col">
				<p class="text-red heavy">Recherche par numéro de litige, code article, magasin (nom ou panonceau galec) :</p>
			</div>
		</div>
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

			<div class="row">
				<div class="col-4">
					<div class="form-group" id="equipe">
						<input class="form-control mr-5 pr-5" placeholder="n°litige,  magasin, galec" name="search_strg" type="text"  value="<?=isset($_SESSION['form-data-deux']['search_strg'])? $_SESSION['form-data-deux']['search_strg'] : "" ?>">
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check form-check-inline mt-3">
						<input type="checkbox" class="form-check-input" name="article" id="article" <?= isset($_SESSION['form-data-deux']['article'])? "checked" : ""?>>
						<label for="article" class="form-check-label">Code article</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check form-check-inline mt-3">
						<input type="checkbox" class="form-check-input" name="btlec" id="btlec" <?= isset($_SESSION['form-data-deux']['btlec'])? "checked" : ""?>>
						<label for="btlec" class="form-check-label">Code BTlec</label>
					</div>
				</div>
				<div class="col-auto">
					<div class="form-check form-check-inline mt-3">
						<input type="checkbox" class="form-check-input" name="galec" id="galec" <?= isset($_SESSION['form-data-deux']['galec'])? "checked" : ""?>>
						<label for="galec" class="form-check-label">Panonceau Galec</label>
					</div>
				</div>
				<div class="col text-right">
					<button class="btn btn-black mr-5" type="submit"  name="search_two"><i class="fas fa-search pr-2"></i>Rechercher</button>
				</div>

			</div>
		</form>
	</div>
</div>
<div class="row mb-5">
	<div class="col border bg-ghostwhite py-3">

		<div class="row">
			<div class="col">
				<p class="text-red heavy">Réinitialisation des sélections : </p>
			</div>
		</div>
		<div class="row">
			<div class="col text-right">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<button class="btn btn-red" type="submit" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer toutes les sélections</button>
				</form>
			</div>
		</div>


	</div>
</div>