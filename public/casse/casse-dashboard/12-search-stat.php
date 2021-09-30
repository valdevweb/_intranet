<div class="result-zone bg-grey px-5 pb-2 pt-2 mb-2">
	<div class="row">
		<div class="col">
			<?php if (isset($_POST['search_strg']) && !empty($_POST['search_strg'])): ?>
			<h5 class="text-main-blue pb-3 text-center">Résultat de votre recherche pour : <span class="heavy bg-title patrick-hand px-3"><?=$_POST['search_string']?></span></h5>
		<?php else: ?>
			<h5 class="text-main-blue pb-3 text-center">Résultat de votre recherche  : </h5>
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
				<a href="?field_1=pending"><div class="legend"><img class="pr-3" src="../img/casse/encours.jpg">En cours</div></a>
			</div>
			<div class="col">
				<a href="?field_1=todeliver"><div class="legend"><img class="pr-3" src="../img/casse/livrer.png">A livrer</div></a>
			</div>
			<div class="col">
				<a href="?field_1=clos"><div class="legend"><img  src="../img/casse/livre.png"><img class="pr-3" src="../img/casse/creditcard.png">Clos - facturé</div></a>
			</div>
			<div class="col">
				<a href="?field_1=destroyed"><div class="legend"><img  src="../img/casse/livre.png"><img class="pr-3" src="../img/casse/logo_deee.jpg">Clos - détruit</div></a>
			</div>
		</div>
	</div>
