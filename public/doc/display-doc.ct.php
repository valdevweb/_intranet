<div class="container">
	<img src="../img/documents/analytics.png">

	<h1 class="blue-text text-darken-4">Vos documents</h1>

	<br>

	<div class="row">
		<!-- colonne gauche -->
		<div class="col-sm-12 col-md-10">
			<!-- ****************************************** -->
			<!-- 			SECTION ODR 		 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="odr-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>listing des ODR :</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$odr['file']?>" target="_blank">Listing ODR mise à jour du <?= $odr['datefull']?></a> </p>
			<br>
			<!-- ****************************************** -->
			<!-- 			SECTION TEL/BRII 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="tel-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Les tickets et BRII :</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$tel['file']?>" target="_blank">Tickets et BRII du <?= $tel['datefull']?></a> </p>
			<br>
			<!-- ****************************************** -->
			<!-- 			SECTION ASSORTIMENT 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="assortiment-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>L'assortiment :</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$assortiment['file']?>" target="_blank">Fichier assortiment du <?= $assortiment['datefull']?></a> </p>
			<br>
			<!-- ****************************************** -->
			<!-- 			SECTION PANIER PROMO 			-->
			<!-- ****************************************** -->
			<h4 class="blue-text text-darken-4" id="panier-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Le panier Promo :</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$panier['file']?>" target="_blank">Panier promo du <?= $panier['datefull']?></a> </p>
			<br>
			<!-- ****************************************** -->
			<!-- 			SECTION MDD 			-->
			<!-- ****************************************** -->

			<h4 class="blue-text text-darken-4" id="mdd-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Le point stock MDD (Elsay, Eco+, Hoé, etc ):</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$mdd['file']?>" target="_blank">Point stock MDD du <?= $mdd['datefull']?></a> </p>
			<br>
			<!-- ****************************************** -->
			<!-- 			SECTION GFK			 			-->
			<!-- ****************************************** -->

			<h4 class="blue-text text-darken-4" id="gfk-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>les résultats GFK :</h4>
			<hr>
			<br>
			<p>- <a href="<?= $path .$gfk['file']?>" target="_blank">Résultats GFK de <?= $month ." ".$gfk['year'] ?></a> </p>
			<br>
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
				<li class="nav-item">
					<a class="nav-link" href="#assortiment-title">Assortiment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#panier-title">Panier promo</a>
				</li>
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

