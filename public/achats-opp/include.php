<h1 class="text-main-blue pt-5 pb-3">Saisie et administration des offres spéciales</h1>
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col">
		<?php
		include('../view/_errors.php');

		?>
	</div>
	<div class="col-lg-1"></div>
</div>
<div class="row">
	<div class="col">
		<div class="mini-nav text-center">
			<ul>
				<li><i class="fab fa-ravelry pr-3"></i><a href="#active">En cours</a></li>
				<li><a href="#futur">A venir</a></li>
				<li><a href="#add">Ajout</a><i class="fab fa-ravelry pl-3"></i></li>
			</ul>
		</div>
	</div>
</div>

<hr>

<div class="row mb-3 mt-5">
	<div class="col">
		<h4 class="text-main-blue" id="active">Liste des offres spéciales en cours</h4>
	</div>
</div>
<div class="row my-5">
	<div class="col-md-1"></div>
	<div class="col">
		<?php if (!empty($listActiveOpp)): ?>
			<table class="table table-sm light-shadow">
				<thead class="thead-light">
					<tr>
						<th>Titre</th>
						<th>Date limite</th>
						<th>GT</th>
						<th>Voir</th>
						<th>Modifier</th>
						<th>Supprimer</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($listActiveOpp as $key => $activeOpp): ?>
						<tr>
							<td><?=$activeOpp['title']?></td>
							<td><?=date('d/m/Y', strtotime($activeOpp['date_end']))?></td>
							<td><?=($activeOpp['gt']==1)? "Multimédia" : "blanc"?></td>
							<td><a href="opp-preview.php?id=<?=$activeOpp['id']?>" class="btn btn-primary">Voir</a></td>
							<td><a href="opp-edit.php?id=<?=$activeOpp['id']?>" class="btn btn-primary">Modifier</a></td>
							<td><a href="opp-delete.php?id=<?=$activeOpp['id'] ?>" class="btn btn-danger delete-opp" >Supprimer</a></td>

						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php else: ?>
			<div class="alert alert-secondary">Aucune offre spéciale n'est en cours</div>
		<?php endif ?>
	</div>
	<div class="col-md-1"></div>
</div>

<?php if (!empty($listFuturOpp)): ?>
	<div class="row mb-3">
		<div class="col">
			<h4 class="text-main-blue" id="futur">Liste des offres spéciales à venir</h4>
		</div>
	</div>
	<div class="row my-5">
		<div class="col-md-1"></div>
		<div class="col">
			<table class="table table-sm light-shadow">
				<thead class="thead-light">
					<tr>
						<th>Titre</th>
						<th>Date limite</th>
						<th>GT</th>
						<th>Voir</th>
						<th>Modifier</th>
						<th>Supprimer</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($listFuturOpp as $key => $futurOpp): ?>
						<tr>
							<td><?=$futurOpp['title']?></td>
							<td><?=date('d/m/Y', strtotime($futurOpp['date_end']))?></td>
							<td><?=($futurOpp['gt']==1)? "Multimédia" : "blanc"?></td>
							<td><a href="opp-preview.php?id=<?=$futurOpp['id']?>" class="btn btn-primary">Voir</a></td>
							<td><a href="opp-edit.php?id=<?=$futurOpp['id']?>" class="btn btn-primary">Modifier</a></td>
							<td><a href="opp-delete.php?id=<?=$futurOpp['id'] ?>" class="btn btn-danger delete-opp" >Supprimer</a></td>

						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

		</div>
		<div class="col-md-1"></div>
	</div>
<?php endif ?>

<hr>
<div class="row mb-3">
	<div class="col">
		<h4 class="text-main-blue" id="add">Ajouter une opportunité</h4>
	</div>
</div>



<div class="row pb-5">
	<div class="col border rounded light-shadow p-3 text-main-blue">
		<form method="post" enctype="multipart/form-data" name="new_opp" id="new_opp">
			<div class="row">
				<div class="col-xl-6">
					<div class="form-group">
						<label for="title">Titre de l'opportunité :</label>
						<input type="text" class="form-control" name="title" id="title" value="<?=FormHelpers::restorePost('title')?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="date_start">Date de début (mise en ligne)</label>
						<input type="date" class="form-control date-width" name="date_start" id="date_start"
						value="<?=(!isset($_POST['date_start']))? date('Y-m-d'): $_POST['date_start']?>">
					</div>
				</div>

				<div class="col">
					<div class="form-group">
						<label for="date_end">Date de fin (fin de remontée)</label>
						<input type="date" class="form-control date-width" name="date_end" id="date_end"
						value="<?=FormHelpers::restorePost('date_end')?>"
						>
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col">
					<div class="form-group">
						<label for="salon">Numéro de salon :</label>
						<input type="text" class="form-control" name="salon" id="salon"
						value="<?=FormHelpers::restorePost('salon')?>"
						>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="cata">Catalogue :</label>
						<input type="text" class="form-control" name="cata" id="cata"
						value="<?=FormHelpers::restorePost('cata')?>"
						>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="dispo">Dispo entrepôt :</label>
						<input type="text" class="form-control" name="dispo" id="dispo"
						value="<?=FormHelpers::restorePost('dispo')?>"
						>
					</div>
				</div>
				<div class="col">
					<label>GT :</label>
					<div class="form-check">
						<input class="form-check-input" type="radio" value="0" id="gt-blanc" name="gt" required <?=FormHelpers::checkChecked(0,'gt')?>>
						<label class="form-check-label" for="gt-blanc">Blanc</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" value="1" id="gt-blanc" name="gt" <?=FormHelpers::checkChecked(1,'gt')?>>
						<label class="form-check-label" for="gt-blanc">Multimédia</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="descr">Description :</label>
						<textarea class="form-control" name="descr" id="descr" row="3"><?=FormHelpers::restorePost('descr')?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<p class="heavy pt-2">Ajouter des icônes :</p>

					<?php for($i=0; $i< count($ico); $i++):?>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="<?=$i?>" id="icon1" name="icons[]"
							<?=FormHelpers::checkCheckedArray($i,'icons')?>>
							<label class="form-check-label" for="icon1"><?= $ico[$i]?></label>
						</div>
					<?php endfor ?>
				</div>

			</div>

			<div class="row">
				<div class="col">
					<div class="row">
						<div class="col">
							<p class="heavy pt-2">Fichiers de l'opportunité :</p>
						</div>
					</div>
					<!--  -->
					<div class="row">
						<div class="col" id="opp_files">
							<input type="file" name="file[]" class="dragndropfile" multiple="multiple">
							<div class="upload-area uploadfile">
								<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
							</div>
							<div class="filename"></div>
							<div class="readablename"></div>
						</div>
					</div>

				</div>
				<div class="col">
					<div class="row">
						<div class="col mr-3">
							<p class="heavy pt-2">Pièces jointes :</p>
						</div>
					</div>
					<!--  -->
					<div class="row">
						<div class="col" id="addons_files">
							<input type="file" name="file[]" class="dragndropfile" multiple="multiple">
							<div class="upload-area uploadfile">
								<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
							</div>
							<div class="filename"></div>
							<div class="readablename"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col"></div>
				<div class="col text-center">
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="add_new" id="add_new" value="Enregistrer">
					</div>
				</div>
				<div class="col pt-2">
					<div id="wait"></div>
				</div>
			</div>

		</form>
	</div>
</div>