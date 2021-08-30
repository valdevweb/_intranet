<!-- ./row -->
<div class="modal fade" id="modal-cloturer" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-main-blue">
				<h5 class="modal-title text-white" id="myModalLabel">Objet : <span id="objet_cloture"></span></h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				$queryStrg=isset($_GET['id'])?'?id='.$_GET['id']:"";
				?>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).$queryStrg?>" method="post">
					<input type="hidden" name="id_evo" id="id_evo_cloture" >
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cmt_resp">Commentaires pour les responsables : </label>
								<textarea class="form-control" name="cmt_resp"  row="3"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cmt_dd">Commentaires pour le demandeur :</label>
								<textarea class="form-control" name="cmt_dd"  row="3"></textarea>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col text-right">
							<button class="btn btn-primary" name="cloturer">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>