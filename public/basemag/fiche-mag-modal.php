<!-- ************************************************************
	*			MODAL
	*
	****************************************************************-->

	<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-body">
					<h5 class="text-center text-violet">Modifier l'observation :</h5>
					<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'].'?id='.$_GET['id'])?>" method ="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Observations : </label>
									<textarea class="form-control" name="cmt-mod" id="cmt-mod"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<p><input type="file" name="files[]" class='form-control-file' multiple=""></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col text-right">
								<input type="hidden" class="form-control" id="cmt-id" name="cmt-id">
								<button class="btn btn-primary" name="submit-mod-cmt">Modifier</button>

							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
				</div>
			</div>
		</div>
	</div>



	<div class="modal fade" id="modal-new-cmt" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-body">
					<h5 class="text-center text-violet">Ajouter une observation :</h5>
					<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'].'?id='.$_GET['id'])?>" method ="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Observations : </label>
									<textarea class="form-control" name="cmt-mod" id="cmt-mod"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<p><input type="file" name="files[]" class='form-control-file' multiple=""></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col text-right">
								<button class="btn btn-primary" name="submit-mod-add">Ajouter</button>
							</div>
						</div>



					</form>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
				</div>
			</div>
		</div>
	</div>