<div class="row mt-3">
	<div class="col">
		<h5 class="khand text-main-blue pb-3">Echange avec le magasin</h5>
	</div>
</div>

<div class="row">
	<div class="col">
		<?php
		if(isset($dials) && count($dials)>0){
			include('echanges.php');
		}
		?>
	</div>
</div>