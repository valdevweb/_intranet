<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
	<div class="row">
		<div class="col">Estimation du temps :</div>
		<div class="col">
			<?php foreach ($listLevel as $keyLevel => $value): ?>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" value="<?=$keyLevel?>"  <?= ($keyLevel==$evo['id_chrono'])?"checked" :""?> name="chrono" required id="<?=$listLevel[$keyLevel]['class']?>">
					<label class="form-check-label pr-5 text-<?=$listLevel[$keyLevel]['class']?>" ><b><?=$listLevel[$keyLevel]['chrono']?></b></label>
				</div>
			<?php endforeach ?>

		</div>
	</div>
	<div class="form-group">
		<label for="evo">Evo : </label>
		<textarea class="form-control" name="evo" id="evo" row="3" ><?=$evo['evo']?></textarea>
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