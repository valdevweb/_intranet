<input type="hidden" name="choice" value="<?=$choice?>" id="choice">

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Convention et Salon BTLEC Est 2020</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php if ($_SESSION['type']=='mag'): ?>
					<h5 class="text-center text-violet">Vos badges sont disponibles !</h5>
					<p>Cette année, afin d'assurer la sécurité de tous et de limiter les regroupements de personnes notamment au niveau de l'accueil, nous mettons à votre disposition un document pdf vous permettant d'imprimer vos badges. Ces badges sont munis d'un qrcode qu'il vous suffira de scanner à l'accueil du salon.</p>
					<p><b>Aucun bagde ne sera délivré sur le salon, en revanche, nous vous fournirons le support de badge</b></p>
					<p>Avant d'imprimer, pensez à vérifier vos options d'impression, l'échelle doit être sur "défaut" (ce paramètre se trouve dans les options avancées)</p>
					<p> Pour télécharger le fichier pdf, il vous suffit de cliquer sur le lien ci-dessous</p>
					<div class="text-center">
						<a href="../salon/pdf-badges-multiple.php">Imprimer mon badge</a>
					</div>
					<?php else: ?>
						<h5 class="text-center text-violet">Badges</h5>
						<p>Pour générer votre badge et l'imprimer, veuillez cliquer sur le lien si dessous</p>
						<div class="text-center">
							<a href="../salon/pdf-badges-bt.php">Imprimer mon badge</a>
						</div>
					<?php endif ?>

					<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="input_choice" name="input_choice" onchange='this.form.submit()'>
							<label class="form-check-label" for="input_choice">Ne plus afficher ce message</label>
						</div>

					</form>
				</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-violet" data-dismiss="modal">Fermer</button>
			</div> -->
		</div>
	</div>
</div>
