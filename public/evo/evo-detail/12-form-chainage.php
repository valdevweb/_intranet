<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="row">
		<div class="col-2">
			<div class="form-group">
				<label for="parent">L'évo :</label>
				<input type="text" class="form-control" name="parent" id="parent" value="<?=$_GET['id']?>" >
			</div>
		</div>
		<div class="col-2">
			<div class="form-group">
				<label for="enfant">Devient :</label>
				<input type="text" class="form-control" name="enfant" id="enfant" placeholder="numéro de l'évo">
			</div>
		</div>
		<div class="col-auto mt-4 pt-2">
			<button class="btn btn-orange" name="add_chainage">Valider</button>
		</div>
	</div>
</form>