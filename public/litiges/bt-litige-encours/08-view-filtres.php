<div class="row mt-3">
	<div class="col">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="row">
				<div class="col-auto">
					<p class="text-red heavy">Filtrer par statut :</p>
				</div>

				<div class="col">
					<button class="stamp pending" type="submit" name="pending" value="0" ><i class="fas fa-user-check"></i></button>
					<button class="stamp validated" type="submit" name="pending" value="1" ><i class="fas fa-user-check"></i></button>
					<button class="stamp reset-pending" type="submit" name="reset-pending" ><i class="fas fa-user-check"></i></button>
				</div>
				<div class="col"></div>
			</div>
		</form>
	</div>
	<div class="col text-right">
		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row justify-content-end">
						<div class="col"></div>

						<div class="col-auto">
							<p class="text-red heavy">Filtrer par type de livraison :</p>
						</div>

						<div class="col-auto">
							<button class="no-btn" type="submit" name="vingtquatre" value="1"><img src="../img/litiges/2448_ico.png"></button>
							<button class="no-btn" type="submit" name="vingtquatre" value="0"><img src="../img/litiges/2448_no_ico.png"></button>
							<button class="no-btn" type="submit" name="reset-vingtquatre" ><img src="../img/litiges/2448_reset_ico.png"></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row justify-content-end">
						<div class="col"></div>

						<div class="col-auto">
							<p class="text-red heavy">Filtrer les litiges occasion :</p>
						</div>

						<div class="col-auto">
							<button class="no-btn" type="submit" name="occasion" value="1"><img src="../img/logos/leclerc-occasion-circle-mini.gif"></button>
							<button class="no-btn" type="submit" name="occasion" value="0"><img src="../img/logos/leclerc-occasion-none.png"></button>
							<button class="no-btn" type="submit" name="reset-occasion" ><img src="../img/logos/leclerc-occasion-reset.png"></button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>