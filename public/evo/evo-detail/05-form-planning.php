	<div class="row">
	<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
		Planifier :
	</div>
</div>

	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
				<div class="row">
					<div class="col">
						Informer les demandeurs :
						<div class="form-check">
							<input class="form-check-input" type="radio" value="1" id="oui" name="envoi" checked>
							<label class="form-check-label" for="oui">Oui</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="0" id="non" name="envoi">
							<label class="form-check-label" for="non">Non</label>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label for="date_start">Date de début :</label>
							<input type="date" class="form-control" name="date_start" id="date_start">
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label for="date_end">Date de fin :</label>
							<input type="date" class="form-control" name="date_end" id="date_end">
						</div>
					</div>
					<div class="col-auto mt-4 pt-2">
						<button class="btn btn-orange" name="add_planning">Placer</button>
					</div>
				</div>

			</form>
		</div>
	</div>