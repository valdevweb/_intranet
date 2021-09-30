			<div class="row mb-3 focusing">
				<div class="col  py-5">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
						<div class="form-row ">
							<div class="col-4">
								<div class="form-group">
									<label class="font-weight-bold">Palette :</label>
									<input class="form-control mr-5 pr-5" placeholder="article, palette" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de début :</label>
									<input type="date" name="date_start" id="date_start" class="form-control" value="<?=$start?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de fin :</label>
									<input type="date" name="date_end" id="date_end" class="form-control" value="<?=$today?>">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Statut : </label>
									<select name="statut" id="statut" class="form-control">
										<option value="">Tout statut</option>
										<option value="0">En cours</option>
										<option value="4919">En stock - à expédier</option>
										<option value="1">Positionnée - à  expédier</option>
										<option value="5">Clos - facturé</option>
										<option value="6">Clos - detruit</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-row text-right">

							<div class="col">

								<button class="btn btn-black " type="submit" id="" name="vpalette"><i class="fas fa-search pr-2"></i>Rechercher une palette</button>
								<button class="btn secTwo" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>


							</div>

						</div>
					</form>
				</div>
			</div>