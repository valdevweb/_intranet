<div class="row">
	<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
		Saisir une note :
	</div>
</div>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="note">Note :</label>
				<textarea class="form-control" name="note" id="note" row="3"></textarea>
			</div>
		</div>
	</div>
		<div class="row">
		<div class="col text-right">
			<button class="btn btn-orange" name="add_note">Ajouter</button>
		</div>
	</div>

</form>
