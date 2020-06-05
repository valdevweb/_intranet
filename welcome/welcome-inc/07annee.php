<div class="row mb-5">
	<div class="col">
		<div class="d-flex justify-content-between">
			<div class="align-self-center">
				<img class="img-fluid max-width height-auto" src="year.png">
			</div>
			<div class="main-text text-right pr-5">
				<?=number_format((float)$anneeEnCoursCa,0,'',' ')?><span class="third-text">&euro;</span></div>
				<br><span class="norm-text-abs">pour  XX palettes et xxx colis</span>

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
					<?=number_format((float)$anneeEnCoursLastYearCa,0,'',' ')?> <span class="third-text">&euro;</span>
				</div>
			</div>
		</div>
	</div>