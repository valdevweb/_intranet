<!-- formulaire de recherche -->
<div class="row mb-5">
	<div class="col border py-3">
		<div class="row">
			<div class="col-6">
				<p class="text-red heavy">Recherche par date et/ou état :</p>
			</div>
		</div>

		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">
				<div class="col-auto mt-2">
					<p class="text-red">Date de début :</p>
				</div>
				<div class="col-auto">
					<div class="form-group">
						<input type="date" class="form-control" value="<?= isset($_SESSION['form-data']['date_start']) ? $_SESSION['form-data']['date_start'] :'' ?>" name="date_start">
					</div>

				</div>
				<div class="col-auto mt-2">
					<p class="text-red">Date de fin :</p>
				</div>
				<div class="col-auto">
					<div class="form-group">
						<input type="date" class="form-control" min="2019-01-01" value="<?=isset($_SESSION['form-data']['date_end']) ? $_SESSION['form-data']['date_end'] :'' ?>" name="date_end">
					</div>
				</div>

				<div class="col-auto mt-2">
					<p class="text-red ">Etat :</p>
				</div>
				<div class="col-2">
					<div class="form-group">
						<select name="etat"  class="form-control">
							<option value="">Sélectionner</option>
							<?php
							foreach ($listEtat as $etat)
							{
								$selected="";
								if(!empty($_SESSION['form-data']['etat']))
								{
									if($etat['id']==$_SESSION['form-data']['etat'])
									{
										$selected='selected';
									}
									else
									{
										$selected="";

									}
								}
								echo '<option value="'.$etat['id'].'" '.$selected.'>'.$etat['etat'].'</option>';
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col"></div>

				<div class="col-auto">
					<button class="btn btn-black mr-5" type="submit"  name="search_one"><i class="fas fa-search pr-2"></i>Rechercher</button>
				</div>
			</div>
		</form>

		<div class="row mt-5">
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
				<div class="col">
					<div class="form-check form-check-inline mt-3">
						<input type="checkbox" class="form-check-input" name="article" id="article" <?= isset($_SESSION['form-data-deux']['article'])? "checked" : ""?>>
						<label for="article" class="form-check-label">recherche d'un code article</label>
					</div>
				</div>
				<div class="col text-right">
					<button class="btn btn-black mr-5" type="submit"  name="search_two"><i class="fas fa-search pr-2"></i>Rechercher</button>
				</div>

			</div>
		</form>
		<div class="row">
			<div class="col text-center">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
						<button class="btn btn-red" type="submit" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer toutes les sélections</button>
					</form>
			</div>
		</div>


	</div>
</div>