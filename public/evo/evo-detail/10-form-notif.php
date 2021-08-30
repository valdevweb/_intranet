<div class="row">
	<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
		Saisir une notification :
	</div>
</div>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="row">
		<div class="col-8">
			<div class="form-group">
				<label for="title">Objet :</label>
				<input type="text" class="form-control" name="title" id="title">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="date_notif">Date d'envoi</label>
				<input type="date" class="form-control" name="date_notif" id="date_notif">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="notif">Détail :</label>
				<textarea class="form-control" name="notif" id="notif" row="3"></textarea>
			</div>
		</div>
	</div>
		<div class="row">
		<div class="col text-right">
			<button class="btn btn-orange" name="add_notif">Créer</button>

		</div>
	</div>

</form>
