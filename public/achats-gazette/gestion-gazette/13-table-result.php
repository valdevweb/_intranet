		<div class="row">
			<div class="col border px-5 py-3">
				<div class="row">
					<div class="col text-center text-main-blue">
						Résultat de votre recherche :

						<?=(!empty($_POST['strg']))? "<span class='badge badge-primary'>mot clé : ".$_POST['strg']."</span>" : "";?>
						<?=(!empty($_POST['date_start']))?"<span class='badge badge-warning'>du ".date("d-m-Y", strtotime($_POST['date_start'])).'</span>':"";?>
						<?php if (!empty($_POST['date_start'])): ?>
							<span class='badge badge-warning'>au
						<?= (!empty($_POST['date_end']))? date("d-m-Y", strtotime($_POST['date_start'])):date('d-m-Y');?>
							</span>
						<?php endif ?>



					</div>
				</div>
				<?php if (!empty($searchResults)): ?>
					<table class="table table-sm mt-3">
						<thead class="thead-dark">
							<tr>
								<th>Date publication</th>
								<th>Catégorie</th>
								<th>Type d'information</th>
								<th>Titre</th>
								<th>Description</th>
								<th>Fichiers joints</th>
								<th>Liens</th>
								<th colspan="2">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($searchResults as $key => $gazette): ?>
								<tr>
									<td><?=date('d-m', strtotime($gazette['date_start']))?></td>
									<td><?=($mainCat[$gazette['main_cat']])??""?></td>
									<td><?=($listCat[$gazette['cat']])??""?></td>
									<td><?=$gazette['titre']?></td>
									<td><?=$gazette['description']?></td>
									<td>
										<?php if (isset($searchResultsFiles[$gazette['id']])): ?>
											<?php foreach ($searchResultsFiles[$gazette['id']] as $key => $file): ?>
												<a href="<?=URL_UPLOAD.'gazette\\'.$file['file']?>"><?=($file['filename'])??'<i class="fas fa-file pb-3"></i>'?></a><br>
											<?php endforeach ?>
										<?php endif ?>
									</td>
									<td>
										<?php if (isset($searchResultsLink[$gazette['id']])): ?>
											<?php foreach ($searchResultsLink[$gazette['id']] as $key => $link): ?>
												<a href="<?=$link['link']?>"><?=($link['linkname'])??$link['link']?></a><br>
											<?php endforeach ?>
										<?php endif ?>
									</td>
									<td><a href="modif-gazette.php?id=<?=$gazette['id']?>" title="modifier"><i class="fas fa-edit"></i></a></td>
									<td><a href="delete-gazette.php?id=<?=$gazette['id']?>" title="supprimer"><i class="fas fa-trash-alt"></i></a></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<?php else: ?>
						Aucun résultat trouvé pour votre recherche
					<?php endif ?>

				</div>
			</div>