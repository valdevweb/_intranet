<div class="row mb-5">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img class="img-fluid max-width height-auto" src="thismonth.png">
			</div>
			<div class="main-text text-right pr-5">
				<?=number_format((float)$moisEnCoursCa,0,'',' ')?><span class="third-text">&euro;</span>
				<br><span class="norm-text-abs"><?=$moisEnCoursPalette?> palettes  pour <?=$moisEnCoursLastYearColis?> colis</span>


			</div>
		</div>
	</div>
</div>
<!--  ligne chiffre jour -1-->
<div class="row">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img src="lastmonth.png">
			</div>
			<div class="secondary-text text-right pr-5">
				<?=number_format((float)$moisEnCoursLastYearCa,0,'',' ')?> <span class="third-text">&euro;</span>
				<br><span class="norm-text-abs"><?=$moisEnCoursLastYearPalette?> palettes  pour <?=$moisEnCoursLastYearColis?> colis</span>

			</div>
		</div>
	</div>
</div>