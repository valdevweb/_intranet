<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="form-group">
		<label for="evo">Evo : </label>
		<textarea class="form-control" name="evo" id="evo" row="3" <?=($evo['id_etat']!=1)?"readonly": ""?>><?=$evo['evo']?></textarea>
	</div>
	<div class="form-group">
		<label for="cmt_dd">Commentaire developpeur :</label>
		<textarea class="form-control" name="cmt_dd" id="cmt_dd" row="3"><?=$evo['cmt_dd']?></textarea>
	</div>

	<div class="row">
		<div class="col text-right">
			<button class="btn btn-orange" name="modif_evo">Modifier</button>
		</div>
	</div>
</form>