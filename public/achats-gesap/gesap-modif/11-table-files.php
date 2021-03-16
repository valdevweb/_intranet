
	<div class="row">
		<div class="col">
			<form method="post" class="form-inline" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">
				<table class="table w-auto table-sm">
					<thead class="thead-light">
						<tr>
							<th class="px-5 text-center">Nom du fichier</th>
							<th class="px-5 text-center">Intitulé du lien</th>
							<th class="pl-5 text-right ">Suppression</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($files as $key => $file): ?>
							<tr>
								<td class="px-5 text-center">
									<a href="<?=URL_UPLOAD.'gsap/'.$file['file']?>"><?= $file['file']?></a><br>
								</td>
								<td>

									<div class="form-group">
										<label for="name"></label>
										<input type="text" class="form-control wider" name="name[<?=$file['id']?>]" id="name" value="<?=(!empty($file['filename'])) ? $file['filename']:''?>">
									</div>
								</td>

								<td class="text-right">
									<button type="submit" class="btn btn-orange" name="delete-file[<?=$file['id']?>]">Supprimer</button>
								</td>

							</tr>

						<?php endforeach ?>
						<tr>
							<td></td>
							<td class="text-right">
								<button class="btn btn-primary" name="save_name_file"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							</td>
							<td></td>
						</tr>

					</tbody>
				</table>

			</form>
		</div>
	</div>