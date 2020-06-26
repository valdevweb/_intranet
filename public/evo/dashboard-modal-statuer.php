<!-- ./row -->
		<div class="modal fade" id="modal-statuer" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-main-blue">
						<h5 class="modal-title text-white" id="myModalLabel">Objet : <span id="objet"></span></h5>
						<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
							<input type="hidden" name="id_evo" id="id_evo" >
							<div class="row">
								<div class="col">

								</div>
							</div>
							<div class="row">
								<div class="col">
									Accepter ou réfuser la demande :
								</div>
							</div>
							<div class="row py-3">
								<div class="col">
									<div class="form-check form-check-inline">
										<input class="form-check-input faible" type="radio" value="2" id="statut" name="statut">
										<label class="form-check-label font-weight-bold text-green" for="statut">Valider</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input urgent" type="radio" value="5" id="statut" name="statut">
										<label class="form-check-label font-weight-bold text-red" for="statut">Refuser</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="cmt_dd">Commentaires pour le demandeur : </label>
										<textarea class="form-control" name="cmt_dd" id="cmt_dd" row="3"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="cmt_dev">Commentaires pour le développeur :</label>
										<textarea class="form-control" name="cmt_dev" id="cmt_dev" row="3"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									Modification de la priorité :
								</div>
							</div>
							<div class="row mb-3">
								<div class="col">
									<div class="form-check form-check-inline">
										<input class="form-check-input urgent" type="radio" value="1"  name="prio" required>
										<label class="form-check-label pr-5 text-red" for="urgent"><b>urgent</b></label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input normal" type="radio" value="2"  name="prio">
										<label class="form-check-label pr-5 text-main-blue" for="normal"><b>normal</b></label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input faible" type="radio" value="3"  name="prio">
										<label class="form-check-label pr-5 text-green" for="faible"><b>faible</b></label>
									</div>
								</div>
							</div>
							<div class="row">

							</div>
							<div class="row">
								<div class="col-auto">
									Imposer une deadline :

								</div>
								<div class="col-md-6 col-xl-3">

									<div class="form-group">
										<input type="date" class="form-control" name="deadline" id="deadline">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col text-right">
									<button class="btn btn-primary" name="statuer">Envoyer</button>
								</div>
							</div>
						</form>
					</div>
			</div>
		</div>
	</div>