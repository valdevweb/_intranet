<div class="modal fade" id="edit-palette" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal-label">Modifier la palette</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="palette-modal">Numéro de palette :</label>
								<input type="text" class="form-control" name="palette_modal" id="palette-modal">
								<input type="hidden" class="form-control" name="id_palette" id="id-palette-modal">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col text-right">
							<button class="btn btn-primary" name="update_palette"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>