<div class="row my-3">
	<div class="col box-filter px-2">
		<div class="row">
			<div class="col-auto align-self-center  border-right">
				<div class="row">
					<div class="col px-5">
						<i class="fas fa-filter fa-3x text-grey"></i>
						<div class="text-grey mt-2">Filtrer </div>
					</div>
				</div>
			</div>
			<div class="col-auto border-right">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col px-5">
							<p class="text-center text-grey">Statut :</p>
							<button class="stamp-filter btn btn-filter" type="submit" name="pending" value="0" ><i class="fas fa-user-check stamp pending"></i></button>
							<button class="stamp-filter btn btn-filter" type="submit" name="pending" value="1" ><i class="fas fa-user-check stamp validated"></i></button>
							<button class="stamp-filter btn btn-filter" type="submit" name="reset-pending" ><i class="fas fa-user-check stamp reset-pending"></i></button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-auto border-right">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col px-5">
							<p class="text-center text-grey">Livraison :</p>
							<button class="btn btn-filter" type="submit" name="vingtquatre" value="1"><img src="../img/litiges/2448-org-26.png"></button>
							<button class="btn btn-filter" type="submit" name="vingtquatre" value="0"><img src="../img/litiges/2448-barre-26.png"></button>
							<button class="btn btn-filter" type="submit" name="reset-vingtquatre" ><img src="../img/litiges/2448-bw-26.png"></button>
						</div>

					</div>
				</form>
			</div>
			<div class="col-auto  border-right ">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

				<div class="row mb-3">
					<div class="col px-5">
						<p class="text-center text-grey">Messages :</p>
						<button class="btn btn-filter" name="dial_notif"><i class='fas fa-bell text-yellow'></i></button>
						<button class="btn btn-filter" name="action_notif"><i class='fas fa-bell text-green'></i></button>
					</div>
				</div>
				</form>

			</div>
			<div class="col-auto">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col px-5">
							<p class="text-center text-grey">Occasion :</p>
							<button class="btn btn-filter" type="submit" name="occasion" value="1"><img src="../img/litiges/occ-org-26.png"></button>
							<button class="btn btn-filter" type="submit" name="occasion" value="0"><img src="../img/litiges/occ-barre-26.png"></button>
							<button class="btn btn-filter" type="submit" name="reset-occasion" ><img src="../img/litiges/occ-bw-26.png"></button>
						</div>
					</div>
				</form>

			</div>

		</div>
	</div>
</div>