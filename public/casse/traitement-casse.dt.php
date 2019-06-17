<div class="row">
		<div class="col">

			<div class="row">
				<div class="col">
					<p class="text-main-blue">Simulation calcul décote : </p>
					<table class="table table-sm table-bordered">
						<tr>
							<th>Décote 50%</th>
							<th>Décote 40%</th>
							<th>Décote 30%</th>
							<th>Décote 20%</th>
							<th>Décote 10%</th>
						</tr>
						<tr class="text-right">
							<td><?=$casseInfo['valo'] * 0.5?>&euro;</td>
							<td><?=$casseInfo['valo'] * 0.4?>&euro;</td>
							<td><?=$casseInfo['valo'] * 0.3?>&euro;</td>
							<td><?=$casseInfo['valo'] * 0.2?>&euro;</td>
							<td><?=$casseInfo['valo'] * 0.1?>&euro;</td>
						</tr>

					</table>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<p class="text-main-blue"><span class="ico-circle">Ou</span> Vente Magasin : </p>
				</div>
			</div>
			<div class="row border pt-3">
				<div class="col">
					<?php
					ob_start();

					?>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >

						<div class="row">
							<div class="col-4">
								<div class="form-group">
									<label>Montant à facturer au magasin : </label>
									<input type="text" class="form-control" name="mt_mag">
								</div>
							</div>
							<div class="col"></div>

						</div>
						<div class="row">
							<div class="col-4">
								<div class="form-group">
									<label>Montant de la décote : </label>
									<input type="text" class="form-control" name="mt_decote">
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-4">
								<div class="form-group">
									<label>Montant de la note de débit fournisseur : </label>
									<input type="text" class="form-control" name="mt_ndd">
								</div>
							</div>
							<div class="col-4">
								<div class="form-group">
									<label>Numéro de la note de débit :</label>
									<input type="text" class="form-control" name="num_ndd">
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-8 text-right">
								<button class="btn btn-primary" name="submit_mag">Enregistrer</button>
							</div>
							<div class="col"></div>
						</div>

					</form>
					<?php
					$infoFiForm=ob_get_contents();
					ob_end_clean();

					$expInfo=getThisExp($pdoCasse,$casseInfo['id_palette']);

					if($expInfo==false)
					{
						echo '<p class="alert alert-warning">Vous ne pouvez pas saisir de vente magasin : la palette de contremarque n\'a pas encore été crée et affectée.<br> Merci de faire le nécessaire <a href="detail-palette.php?id='.$casseInfo['id_palette'].'">en cliquant ici</a></p>';
					}else
					{
						echo $infoFiForm;
					}


					?>


				</div>
			</div>

			<div class="row mt-3">
				<div class="col">
					<p class="text-main-blue"><span class="ico-circle">Ou</span> Reprise fournisseur / destruction : </p>
				</div>
			</div>
			<div class="row border pt-2 mb-5">
				<div class="col">
					<div class="row">
						<div class="col">Le ou les produits ont été : </div>
					</div>
					<div class="row">
						<div class="col">
							<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" class="form-inline">
								<div class="custom-control custom-radio custom-control-inline  mr-2">
									<input type="radio" class="custom-control-input" name="motif" id="repris" value="repris">
									<label for="repris" class="custom-control-label">Repris</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" class="custom-control-input" name="motif" id="detruit" value="detruit">
									<label for="detruit" class="custom-control-label">Détruits</label>
								</div>
								<div class="input-group ml-5">
									<div class="input-group-prepend">
										<div class="input-group-text">Le : </div>
									</div>
									<input type="date" class="form-control" name="date_clos" value="<?= date('Y-m-d')?>">
								</div>
								<button type="submit" class="btn btn-primary ml-3" name="submit_clos">Valider</button>
							</form>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>