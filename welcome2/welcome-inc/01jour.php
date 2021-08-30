<div class="row mb-5">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img class="img-fluid max-width height-auto" src="today.png">
			</div>
			<div class="main-text text-right pr-5">
				<?=number_format((float)$jMoinsUnCa,0,'',' ')?><span class="third-text">&euro;</span>
				<br><span class="norm-text-abs"><?=number_format((float)$jMoinsUnPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$jMoinsUnColis,0,'', ' ')?> colis</span>

			</div>

		</div>
	</div>
</div>
<!--  ligne chiffre jour -1-->
<div class="row">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img src="yesterday.png">
			</div>
			<div class="secondary-text text-right pr-5" >
				<?=number_format((float)$jMoinsUnLastYearCa,0,'',' ')?> <span class="third-text">&euro;</span>
				<br><span class="norm-text-abs" style="margin-top: -15px;"><?=number_format((float)$jMoinsUnLastYearPalettes,0,'', ' ')?> palettes  pour <?=number_format((float)$jMoinsUnLastYearColis,0,'', ' ')?> colis</span>

			</div>
		</div>
	</div>
</div>