<div class="container">
	<img src="../img/documents/analytics.png">

	<h1 class="blue-text text-darken-4">Vos documents</h1>
	<div class="row">
		<!-- colonne gauche -->
		<div class="col-sm-12 col-md-10">
			<!-- ****************************************** -->
			<!-- 			SECTION ODR 		 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="odr-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>listing des ODR :</h4>
			<!-- <hr> -->
			<!-- <br> -->
			<p>- <a href="<?= $path .$odr['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Listing ODR mise à jour du <?= $odr['datefull']?></a> </p>
			<!-- ****************************************** -->
			<!-- 			SECTION TEL/BRII 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="tel-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Les tickets et BRII :</h4>
			<!-- <hr> -->
			<!-- <br> -->
			<p>- <a href="<?= $path .$tel['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Tickets et BRII du <?= $tel['datefull']?></a> </p>
			<!-- ****************************************** -->
			<!-- 			SECTION ASSORTIMENT 			-->
			<!-- ****************************************** -->
			<?php
				ob_start()
			?>
			<h4 class="blue-text text-darken-4" id="assortiment-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>L'assortiment :</h4>
			<p>- <a href="<?= $path .$assortiment['file']."?".date("Y-m-d H:i:s")?>" target="_blank">l'assortiment du <?= $assortiment['datefull']?></a> </p>
			<?php
				$assortimentDisplay=ob_get_contents();
				ob_end_clean();
				 if(isset($assortiment['file'])){
				 	echo $assortimentDisplay;
				 }
			 ?>
			<!-- ****************************************** -->
			<!-- 			SECTION PANIER PROMO 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="panier-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Le panier promo :</h4>
			<p>- <a href="<?= $path .$panier['file']."?".date("Y-m-d H:i:s")?>" target="_blank">le panier promo du <?= $panier['datefull']?></a> </p>
			<!-- ****************************************** -->
			<!-- 			SECTION MDD 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="mdd-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Le point stock MDD (Elsay, Eco+, Hoé, etc ):</h4>
			<!-- <hr> -->
			<!-- <br> -->
			<p>- <a href="<?= $path .$mdd['file']."?".date("Y-m-d H:i:s")?>" target="_blank">Point stock MDD du <?= $mdd['datefull']?></a> </p>

			<!-- ****************************************** -->
			<!-- 			SECTION GFK			 			-->
			<!-- ****************************************** -->

			<h4 class="blue-text text-darken-4" id="gfk-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>les résultats GFK :</h4>
			<!-- <hr> -->
			<!-- <br> -->
			<p>- <a href="<?= $path .$gfk['file']."?".date("Y-m-d H:i:s") ?>" target="_blank">Résultats GFK de <?= $month ." ".$gfk['year'] ?></a> </p>

		</div> <!-- fin col de gauche!-->

		<!-- ****************************************** -->
		<!-- 			SIDE NAV	 		 			-->
		<!-- ****************************************** -->
		<div class="col-sm-12 col-md-2">
			<h3 class="mb-4">Sections : </h3>

			<ul class="nav flex-column nav-pills">
				<li class="nav-item">
					<a class="nav-link active" href="#odr-title">ODR</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#tel-title">Tickets / BRII</a>
				</li>
				<!--  ASSORTIMENT -->
			<?php
				ob_start()
			?>
				<li class="nav-item">
					<a class="nav-link" href="#assortiment-title">Assortiment</a>
				</li>
			<?php
				$assortimentTitle=ob_get_contents();
				ob_end_clean();
				if(isset($assortiment['file'])){
				 	echo $assortimentTitle;
				}
			 ?>
				<li class="nav-item">
					<a class="nav-link" href="#panier-title">Panier promo</a>
				</li>
				<!-- END EN ATTENTE -->
				<li class="nav-item">
					<a class="nav-link" href="#mdd-title">Point stock MDD</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#gfk-title">GFK</a>
				</li>
			</ul>
		</div><!--fin side nav-->

	</div> 	<!--fin 1st row-->




<!--*********************************  -->
</div> <!--fin container -->

