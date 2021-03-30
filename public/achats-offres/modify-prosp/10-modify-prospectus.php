
<?php if (isset($prosp) && !empty($prosp)): ?>
<div class="row">
	<div class="col">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-lg-3">
					<div class="form-group">
						<label for="code_op">Code op : </label>
						<input type="text" class="form-control form-primary" name="code_op" id="code_op" value="<?=$prosp['code_op']?>">
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label for="prospectus">Prospectus : </label>
						<input type="text" class="form-control form-primary" name="prospectus" value="<?=$prosp['prospectus']?>" id="prospectus" >
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label>Date de début : </label>
						<input type="date" class="form-control form-primary" name="date_start" value="<?=$prosp['date_start']?>" >
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label>Date de fin : </label>
						<input type="date" class="form-control form-primary" name="date_end" value="<?=$prosp['date_end']?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="link">Liens :</label>
						<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour ajouter plusieurs liens, veuillez les séparer par une virgule et un espace</div>
						<?php if (isset($listLinks) && !empty($listLinks)): ?>

						<?php
						$links=implode(', ',array_map(function($value){ return $value['link'];}, $listLinks));
						?>
					<?php endif ?>

					<input type="text" class="form-control form-primary" name="link" id="link" value="<?=($links)??""?>" >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="label pb-3">Fichier ficWOPC :</div>
			</div>
		</div>
		<div class="row ml-1">
			<div class="col-lg-6 bg-blue-input rounded pt-2">
				<div class="row">
					<div class="col" id="file-name-mod">
						<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>
					</div>
					<div class="col-auto">

						<div class="form-group">
							<label class="btn btn-upload-primary btn-file">
								<input type="file" name="fic-mod" class='form-control-file'>
								<i class="fas fa-file pr-3"></i>Sélectionner
							</label>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col" id="file-msg-mod"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="label pb-3">Autres fichiers joints :</div>
			</div>
		</div>
		<div class="row ml-1">
			<div class="col bg-blue-input rounded pt-2">
				<div class="row">
					<div class="col"  id="filename-other">
						<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>
					</div>
					<div class="col-auto">
						<div class="form-group">
							<label class="btn btn-upload-primary btn-file text-center">
								<input type="file" name="file_other[]" class='form-control-file' multiple>
								Sélectionner
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col" id="file-other-msg"></div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-4" id="zone-noms"></div>
			<div class="col-3" id="zone-ordre"></div>
			<div class="col"></div>
		</div>
		<div class="row my-3">
			<div class="col text-right">
				<button class="btn btn-primary" name="modify_prosp">Valider</button>

			</div>
		</div>
	</form>
</div>
</div>

<div class="bg-separation mb-5"></div>
<div class="row">
	<div class="col" id="fileformtitle">
		<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Nommer / supprimer les fichiers </h5>
	</div>
</div>
<div class="row">
	<div class="col" id="modif-link">
		<?php if (!empty($listLinks)): ?>
			<form method="post" class="form-inline" id="linkform" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">

				<table class="table w-auto table-sm">
					<thead class="thead-light">
						<tr>
							<th class="px-5 text-center">Liens</th>
							<th class="px-5 text-center">Nom</th>
							<th class="px-5 text-center">Supprimer</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($listLinks as $key => $link): ?>

							<tr>
								<td><a href="<?=$link['link']?>"><?=$link['link']?></a></td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control wider" name="linkname[<?=$link['id']?>]" value="<?=(!empty($link['linkname'])) ? $link['linkname']:''?>">
									</div>
								</td>
								<td>
									<a href="offre-delete.php?link=<?=$link['id'].'&id-prosp='.$_GET['id']?>" class="btn btn-orange" onclick="return confirm('Etes vous sûr de vouloir supprimer ce lien ?')">Supprimer</a>

								</td>

							</tr>
						<?php endforeach ?>
						<tr>
							<td colspan="2"></td>
							<td class="text-right">
								<button class="btn btn-primary" name="modif_link"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<?php else: ?>
				<div class="alert alert-primary">Pas de lien à afficher</div>

			<?php endif ?>
		</div>
	</div>
	<div class="row">
		<div class="col" id="fileformtitle">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Nommer / supprimer les fichiers </h5>
		</div>
	</div>

	<div class="row">
		<div class="col" id="modif-file">
			<?php if (!empty($listFiles)): ?>
				<form method="post" class="form-inline" id="fileform" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">

					<table class="table w-auto table-sm">
						<thead class="thead-light">
							<tr>
								<th class="px-5 text-center">Fichiers</th>
								<th class="px-5 text-center">Nom</th>
								<th>Ordre</th>
								<th class="px-5 text-right">Supprimer</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listFiles as $key => $file): ?>

								<tr>
									<td><a href="<?=$file['file']?>" target="_blank"><?=$file['file']?></a></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control wider" name="filename[<?=$file['id']?>]" value="<?=(!empty($file['filename'])) ? $file['filename']:''?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control" name="ordre[<?=$file['id']?>]" value="<?=(!empty($file['ordre'])) ? $file['ordre']:''?>">
										</div>
									</td>
									<td class="text-right">
										<a href="offre-delete.php?file=<?=$file['id'].'&id-prosp='.$_GET['id']?>" class="btn btn-orange" onclick="return confirm('Etes vous sûr de vouloir supprimer ce fichier ?')"><i class="fas fa-trash-alt"></i></a>

									</td>

								</tr>
							<?php endforeach ?>
							<tr>
								<td colspan="4" class="text-center bg-light-grey py-3">
									<button class="btn btn-primary" name="modif_file"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				<?php else: ?>
					<div class="alert alert-primary">Pas de fichiers joints à afficher</div>

				<?php endif ?>
			</div>
		</div>

		<?php else: ?>
			<div class="alert alert-danger">Ce prospectus n'existe pas</div>
		<?php endif ?>
