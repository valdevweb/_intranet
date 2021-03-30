<div class="row" id="quatre">
			<div class="col">
				<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-plus-circle pr-3"></i>Ajout de type d'information</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-auto">
				<table class="table table-sm w-auto">
					<thead class="thead-light">
						<tr>
							<th>Catégorie</th>
							<th>Type d'info</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($catBt)): ?>
							<?php foreach ($catBt as $keyBt => $value): ?>
								<tr>
									<td>BTLec</td>
									<td><?=$catBt[$keyBt]?></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
						<?php if (!empty($catGalec)): ?>
							<?php foreach ($catGalec as $keyGalec => $value): ?>
								<tr>
									<td>Galec</td>
									<td><?=$catGalec[$keyGalec]?></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
					</tbody>
				</table>
			</div>
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="main_cat">Catégorie :</label>
								<select class="form-control form-primary" name="main_cat" id="main_cat" required>
									<option value="">Sélectionner</option>
									<option value="1">BTLEC</option>
									<option value="2">GALEC</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cat">Type d'information : </label>
								<input type="text" class="form-control  form-primary" name="cat" id="cat">
							</div>
						</div>
					</div>
					<div class="row pb-5">
						<div class="col text-right">
							<button class="btn btn-primary" name="add_cat">Ajouter</button>
						</div>
					</div>
				</form>
			</div>
		</div>