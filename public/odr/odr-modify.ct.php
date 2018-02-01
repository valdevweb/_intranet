<div class="container">
	<h1 class="blue-text text-darken-4">ODR - BRII - TICKETS</h1>
	<br><br>
	<!-- formulaire d'uplaod -->
	<div class="row">
		<div class="col-sm-12 col-md-10">

			<h4 class="blue-text text-darken-4" id="upload-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload d'ODR :</h4>
			<hr>
			<br><br>
			<!-- formulaire d'upload -->
			<div class="box-bd">
			<form method="post" action="odr-modify.php?odr=<?=$odrId?>" enctype="multipart/form-data" >


				<div class="form-row">
					<div class="col-sm12 col-md-12">

						<label for="operation">Nom de l'opération</label>
						<input type="text" class="browser-default form-control" id="operation"  placeholder="Saisir le nom de l'opération" name="operation" value="<?=$odr['operation']?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-sm12 col-md-6">
						<label for="brand">Marque</label>
						<input type="text" class="browser-default form-control" id="brand"  placeholder="Marque" name="brand" value="<?=$odr['brand']?>">
					</div>

					<div class="col-sm12 col-md-6">

						<label for="gt">Choisir le GT</label>
						<select class="form-control" id="gt" name="gt">
							<option value="brun" <?=$odr['gt']=='brun' ? ' selected="selected"' : '' ?>>BRUN</option>
							<option value="gem" <?=$odr['gt']=='gem' ? ' selected="selected"' : '' ?> >GEM</option>
							<option value="gris" <?=$odr['gt']=='gris' ? ' selected="selected"' : '' ?> >GRIS</option>
							<option value="pem" <?=$odr['gt']=='pem' ? ' selected="selected"' : '' ?> >PEM</option>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="col-sm12 col-md-6">
						<label for="startdate">date de début</label>
						<input type="date" class="browser-default form-control" id="startdate"  name="startdate" value="<?=$odr['startdate']?>">
					</div>
					<div class="col-sm12 col-md-6">
						<label for="enddate">date de fin</label>
						<input type="date" class="browser-default form-control" id="enddate"  name="enddate" value="<?=$odr['enddate']?>">
					</div>
				</div>
				<br><br>

				<div class="form-row">
					<div class="col-sm12 col-md-12">
						<p>Fichiers actuels : </p>
						<p><?=extractLink($odr['files'])?></p>
						<br><br>
					</div>

				</div>

				<div class="form-row">
					<div class="col-sm12">
						<p>Ajouter des fichiers :</p>
						<label for="file"></label>
						<p><input type="file" name="file_1" class='input-file'></p>
						<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Ajouter d'autres fichiers</a></p>
						<!-- <input type="file" class="browser-default form-control" id="file"  placeholder="choisir fichier" name="file"> -->
					</div>
				</div>
				<br>
				<!-- affichage des erreurs -->
				<?= $errorsDisplay; ?>
				<div class="form-row">
					<div class="col-sm12 col-md-10">

						<button type="submit" class="btn btn-primary" name="submit">Envoyer</button>
					</div>
				</div>
			</form>

			</div>
		</div> <!-- fin col de gauche!-->
		<!--side nav-->
		<div class="col-sm-12 col-md-2">
			<h3 class="mb-4">Aller à : </h3>

			<ul class="nav flex-column nav-pills">
				<li class="nav-item">
					<a class="nav-link active" href="#">Upload</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#listing-title">Listing</a>
				</li>
			</ul>
		</div>
	</div> 	<!--fin 1st row-->
	<br><br>
	<!--listing - width 100%-->


</div> <!--fin container -->

