<div class="row mt-4">
	<div class="col text-center norm-text-plus shadow-text">
		<?= $yearEndText?>
	</div>
</div>
<div class="row <?= $yearEndColorClass?>">
	<div class="col">
		<div class="d-flex justify-content-between ">
			<i class="fas <?= $icoEndYear?> secondary-text align-items-end align-self-end pb-2 pl-5"></i>

			<div class="secondary-text align-items-end align-self-end text-right pr-3 shadow-text txt-bg">
				<?= number_format((float)$anneeFinDiff,0,'',' ')?><span class="third-text">&euro;</span>
			</div>
		</div>
		<div class="d-flex justify-content-between my-3">
			<i class="fas <?= $icoEndYear?> third-text align-items-end align-self-end pb-2 pl-5"></i>
			<div class="third-text align-items-end align-self-end text-right pr-3 shadow-text txt-bg"><?= $anneeFinPourcent?><span class="norm-text">%</span><br></div>
		</div>

	</div>
</div>