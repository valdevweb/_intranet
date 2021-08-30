<div class="row mb-5">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img class="img-fluid max-width height-auto" src="year.png">
			</div>
			<div class="main-text text-right pr-5">
				<?= number_format((float)$anneeFinCa,0,'',' ') .'<span class="third-text">&euro;</span>'?>
				<br><span class="norm-text-abs"><?=number_format((float)$anneeFinPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$anneeFinColis,0,'', ' ')?> colis</span>


			</div>
		</div>
	</div>
</div>
<!--  ligne chiffre jour -1-->
<div class="row">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img src="lastyear.png">
			</div>
			<div class="secondary-text text-right pr-5">
				<?=number_format((float)$anneeFinLastYearCa,0,'', ' ')?><span class="third-text">&euro;</span>
				<br><span class="norm-text-abs" style="margin-top: -15px;"><?=number_format((float)$anneeFinLastYearPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$anneeFinLastYearColis,0,'', ' ')?> colis</span>

			</div>
		</div>
	</div>
</div>