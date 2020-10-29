	<aside id="modal1" class="vm-modal" aria-hidden="true" role="modal"  style="display: none;">
		<div class="vm-modal-wrapper">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="form-group">
					<label class="text-main-blue">Commentaire :</label>
					<textarea class="form-control" name="cmt" rows="3" id="cmtarea"></textarea>
				</div>
				<div class="form-group">

					<input type="hidden" class="form-control" name="iddossier" id="hiddeninput">
				</div>
				<button class="btn btn-primary" name="validate">Valider</button>
				<button class="btn btn-red" id="annuler">Annuler</button>
			</form>
		</div>
	</aside>

