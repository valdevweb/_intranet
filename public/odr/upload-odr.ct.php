<div class="container">
	<?=isset($_GET['success']) ? " <p><div class='alert alert-success'>Modification enregistrée</div></p>" : false ?>

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
			<form method="post" action="upload-odr.php"  enctype="multipart/form-data" >
				<div class="form-row">
					<div class="col-sm12 col-md-12">

						<label for="operation">Nom de l'opération</label>
						<input type="text" class="browser-default form-control" id="operation"  placeholder="Saisir le nom de l'opération" name="operation" value="<?=isset($_POST['operation'])? $_POST['operation']: false?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-sm12 col-md-6">
						<label for="brand">Marque</label>
						<input type="text" class="browser-default form-control" id="brand"  placeholder="Marque" name="brand" value="<?=isset($_POST['brand'])? $_POST['brand']: false?>">
					</div>

					<div class="col-sm12 col-md-6">

						<label for="gt">Choisir le GT</label>
						<select class="form-control" id="gt" name="gt">
							<option value="brun">BRUN</option>
							<option value="gem">GEM</option>
							<option value="gris">GRIS</option>
							<option value="pem">PEM</option>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="col-sm12 col-md-6">
						<label for="startdate">date de début</label>
						<input type="date" class="browser-default form-control" id="startdate"  name="startdate">
					</div>
					<div class="col-sm12 col-md-6">
						<label for="enddate">date de fin</label>
						<input type="date" class="browser-default form-control" id="enddate"  name="enddate">
					</div>


				</div>
				<br>
				<div class="form-row">
					<div class="col-sm12">
						<label for="file">Joindre les fichiers :</label>
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
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<h4 class="blue-text text-darken-4" id="listing-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Listing des ODR :</h4>
			<hr>
			<br><br>
		</div>
	</div>
	<div class='row'>
		<div class="col">
			<p><em>listing des ODR par date de début décroissante</em></p>
			<table width="100%" class="table table-bordered table-hover" id="_addFiveTable">
				<thead>
					<tr>
						<th>Nom de l'opération</th>
						<th>Marque</th>
						<th>GT</th>
						<th>date de début</th>
						<th>date de fin</th>
						<th>Fichiers joints</th>
						<th>Modifier</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($oneYear as $one): ?>
					<tr>
						<td><?=$one['operation']?></td>
						<td><?=$one['brand']?></td>
						<td><?=$one['gt']?></td>
						<td><?=$one['startdate']?></td>
						<td><?=$one['enddate']?></td>
						<td><?=	extractLink($one['files'])?></td>
						<td><a href="odr-modify.php?odr=<?=$one['id']?>">modifier</a></td>

					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>

	</div>

</div> <!--fin container -->

