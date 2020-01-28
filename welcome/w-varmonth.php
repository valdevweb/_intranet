<div class="row mt-4">
	<div class="col text-center norm-text-plus shadow-text">Variations : </div>
</div>
<div class="row <?= $monthColorClass?>">
	<div class="col">
		<div class="d-flex justify-content-between ">
			<i class="fas <?= $icoMonth?> secondary-text align-items-end align-self-end pb-2 pl-5"></i>
			<div class="secondary-text align-items-end align-self-end text-right pr-3 shadow-text txt-bg"><?= number_format((float)$monthDiff,0,'',' ') ?><span class="third-text">&euro;</span><br></div>
		</div>
		<div class="d-flex justify-content-between my-3">
			<i class="fas <?= $icoMonth?> third-text align-items-end align-self-end pb-2 pl-5"></i>
			<div class="third-text align-items-end align-self-end text-right pr-3 shadow-text txt-bg"><?=$monthPer?><br></div>
		</div>
	</div>
</div>