		<div class="row">
			<div class="col">
				<h6 class="text-main-blue"><i class="fas fa-filter text-orange pr-2"></i>Filtrer les opérations à afficher</h6>
			</div>
		</div>

	<div class="row ">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col">
							<label for="select_op">Sélectionnez une ou plusieurs opérations  : </label>
							<select class="form-control" name="select_op[]" id="select_op"  multiple >
								<option value="">Sélectionner</option>
								<?php foreach ($listOpAVenir as $key => $op): ?>
								<option value="<?=$op['code_op']?>"><?=$op['code_op'] . ' '.$op['operation']?></option>

								<?php endforeach ?>
							</select>
					</div>
					<div class="col align-self-end">
						<button class="btn btn-primary" type="submit" name="filter_op">Filtrer</button>
					</div>
				</div>
			</form>
		</div>
	</div>