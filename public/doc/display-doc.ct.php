<div class="container">
	<div class="row">
		<div class="col">
			<img class="img-fluid" src="../img/documents/analytics.png">
		</div>
	</div>
	<div class="row">

		<div class="col"></div>
		<!-- colonne gauche -->
		<div class="col-auto p-5">
			<h1 class="text-main-blue  text-center">Vos documents</h1>
			<h5 class="text-main-blue" id="odr-title"><i class="far fa-hand-point-right pr-3"></i>listing des ODR :</h5>
			<p>- <a href="<?= $path .$odr['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Listing ODR mise à jour du <?= $odr['datefull']?></a> </p>
			<h5 class="text-main-blue" id="tel-title"><i class="far fa-hand-point-right pr-3"></i>Les tickets et BRII :</h5>
			<p>- <a href="<?= $path .$tel['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Tickets et BRII du <?= $tel['datefull']?></a> </p>
			<?php
			ob_start()
			?>
			<h5 class="text-main-blue" id="assortiment-title"><i class="far fa-hand-point-right pr-3"></i>L'assortiment et le panier promo :</h5>
			<p>- <a href="<?= $path .$assortiment['file']."?".date("Y-m-d H:i:s")?>" target="_blank">l'assortiment et le panier promo du <?= $assortiment['datefull']?></a> </p>
			<?php
			$assortimentDisplay=ob_get_contents();
			ob_end_clean();
			if(isset($assortiment['file'])){
				echo $assortimentDisplay;
			}
			?>
			<h5 class="text-main-blue" id="mdd-title"><i class="far fa-hand-point-right pr-3"></i>Le point stock MDD (Elsay, Eco+, Hoé, etc ):</h5>
			<p>- <a href="<?= $path .$mdd['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Point stock MDD du <?= $mdd['datefull']?></a> </p>
			<h5 class="text-main-blue" id="gfk-title"><i class="far fa-hand-point-right pr-3"></i>les résultats GFK :</h5>
			<p>- <a href="<?= $path .$gfk['file']."?".date("Y-m-d H:i:s") ?>" target="_blank">Résultats GFK de <?= $month ." ".$gfk['year'] ?></a> </p>

		</div> <!-- fin col de gauche!-->
		<div class="col"></div>
	</div> 	<!--fin 1st row-->




	<!--*********************************  -->
</div> <!--fin container -->

