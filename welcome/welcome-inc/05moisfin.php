<div class="row mb-5">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img class="img-fluid max-width height-auto" src="<?=IMG_DIR?>thismonth.png">
			</div>
			<div class="main-text text-right pr-5">
				<?=  number_format((float)$moisFinCa,0,'',' ')?><span class="third-text">&euro;</span>
				<br><span class="norm-text-abs"><?=number_format((float)$moisFinPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$moisFinColis,0,'', ' ')?> colis</span>

			</div>
		</div>
	</div>
</div>
	<!--  ligne chiffre jour -1-->
	<div class="row">
		<div class="col">
			<div class="d-flex justify-content-between">
				<div class="align-self-center">
					<img src="<?=IMG_DIR?>lastmonth.png">
				</div>
				<div class="secondary-text text-right pr-5">
					<?=number_format((float)$moisFinLastYearCa,0,'', ' ')?><span class="third-text">&euro;</span>
				<br><span class="norm-text-abs"><?=number_format((float)$moisFinLastYearPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$moisFinLastYearColis,0,'', ' ')?> colis</span>

				</div>
			</div>
		</div>
	</div>