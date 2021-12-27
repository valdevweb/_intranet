	<div class="row">
		<div class="col">
			<?php if (isset($_POST['search_string']) && !empty($_POST['search_string'])): ?>
			<h6 class="text-main-blue pb-3 text-center">Résultat de votre recherche pour : <span class="heavy bg-title patrick-hand px-3"><?=$_POST['search_string']?></span></h6>
		<?php endif ?>
	</div>
</div>
<div class="row">
	<div class="col text-center">

		<h6 class="qtext-center bg-title p-3 d-inline-block rounded-more"><?= $nbpalette ?> palettes pour un montant total de <span class="under"><?= number_format((float)$sumTot,2,'.',' ')?>&euro;</span></h6>

	</div>
</div>
<div class="row">
	<div class="col">
		<ul class="leaders">
			<?php $lig=1 ?>
			<?php foreach ($arMagSum as $galec => $mt): ?>
				<?php if ($lig<=$nbMagCol): ?>
					<li>
						<span class="mag-txt heavy"><?= ($galec!="")? MagHelpers::deno($pdoMag,$galec) : "Non positionné" ?></span>
						<span class="text-right sum-txt font-weight-bold">
							<?= number_format((float)$mt,2,'.',' ') ?>&euro;</span>						</li>
							<?php $lig++?>
						<?php else: ?>
							<?php $lig=1?>
						</ul>
					</div>
					<div class="col">
						<ul class="leaders">
							<li>
								<span class="mag-txt heavy"><?=MagHelpers::deno($pdoMag,$galec)?></span>
								<span class="text-right sum-txt font-weight-bold">
									<?= number_format((float)$mt,2,'.',' ') ?>&euro;
								</span>
							</li>
						<?php endif ?>

					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="legend" data-statut="0"><img class="pr-3" src="../img/casse/encours.jpg">En cours</div>
			</div>
			<div class="col">
				<div class="legend" data-statut="1"><img class="pr-3" src="../img/casse/livrer.png">A livrer</div>
			</div>
			<div class="col">
				<div class="legend" data-statut="2"><img  src="../img/casse/livre.png">Clos</div>
			</div>
			<div class="col">
				<div class="form-group">

					<select class="form-control" name="affectation" id="affectation">
						<option value="">Affectation</option>
						<?php foreach ($listAffectation as $keyAffectation => $value): ?>
							<option value="<?=$keyAffectation?>" <?=(isset($_GET['field_2']) && $_GET['field_2']==$keyAffectation)?"selected":""?>><?=$listAffectation[$keyAffectation]?></option>
						<?php endforeach ?>
					</select>
				</div>

			</div>
		</div>
