<div class="container">
	<img src="../img/documents/analytics.png">

	<h1 class="blue-text text-darken-4">Vos documents</h1>

	<br><br>
	<!-- ****************************************** -->
	<!-- 			SECTION ODR 		 			-->
	<!-- ****************************************** -->
	<div class="row">
		<!-- colonne gauche -->
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="odr-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>listing des ODR / tickets / BRII :</h4>
			<hr>
			<br><br>
			<!-- ODR FORM START -->
			<div class="box-bd">
			<p><a href="<?= $path .$odr['file']?>" target="_blank">Listing ODR mise à jour du <?= $odr['datefull']?></a> </p>
			</div>
			<!-- ODR FORM END -->
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
					<a class="nav-link" href="#assortiment-title">Assortiment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#panier-title">Panier promo</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#gfk-title">GFK</a>
				</li>
			</ul>
		</div><!--fin side nav-->
	</div> 	<!--fin 1st row-->
	<br><br>
	<!-- ****************************************** -->
	<!-- 			SECTION ASSORTIMENT 			-->
	<!-- ****************************************** -->
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="assortiment-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>L'assortiment :</h4>
			<hr>
			<br><br>
			<!-- ASSORTIMENT FORM START -->
			<div class="box-bd">
			<p><a href="<?= $path .$assortiment['file']?>" target="_blank">Fichier assortiment du <?= $assortiment['datefull']?></a> </p>

			</div>
			<!-- ASSORTIMENT FORM END -->
		</div>
		<div class="col-sm-12 col-md-2">
		</div>
	</div>
	<!-- ****************************************** -->
	<!-- 			SECTION PANIER PROMO 			-->
	<!-- ****************************************** -->
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="panier-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Le panier Promo :</h4>
			<hr>
			<br><br>
			<!-- PANIER PROMO FORM START -->
			<div class="box-bd">
			<p><a href="<?= $path .$panier['file']?>" target="_blank">Panier promo du <?= $panier['datefull']?></a> </p>
			</div>
			<!-- PANIER PROMO FORM END -->
		</div>
	</div>
	<!-- ****************************************** -->
	<!-- 			SECTION GFK			 			-->
	<!-- ****************************************** -->
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="gfk-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>les résultats GFK :</h4>
			<hr>
			<br><br>
			<!-- GFK FORM START -->
			<div class="box-bd">
			<p><a href="<?= $path .$gfk['file']?>" target="_blank">Résultats GFK de <?= $month ." ".$gfk['year'] ?></a> </p>

			</div>
			<!-- GFK FORM END -->
		</div>
	</div>
<!--*********************************  -->
</div> <!--fin container -->

