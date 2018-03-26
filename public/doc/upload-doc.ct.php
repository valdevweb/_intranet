<div class="container">
	<h1 class="blue-text text-darken-4">Upload des documents</h1>
	<br><br>
	<!-- ****************************************** -->
	<!-- 			SECTION ODR 		 			-->
	<!-- ****************************************** -->
	<div class="row">
		<!-- colonne gauche -->
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="odr-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload d'ODR :</h4>
			<hr>
			<br><br>
			<!-- ODR FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#odr-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="odrdate">date : </label>
							<input type="date" class="browser-default form-control" id="odrdate"  name="date" required>
						</div>

					</div>
					<br>
					<div class="form-row">
						<div class="col-sm12">
							<label for="file">Joindre les fichiers :</label>
							<p><input type="file" name="file_1" class='input-file'></p>
						</div>
					</div>
					<br>
					<!-- affichage des erreurs -->
					<?= isset($errorsDisplayOdr) ? $errorsDisplayOdr : ""; ?>
					<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendOdr">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- ODR FORM END -->
		</div> <!-- fin col de gauche!-->
		<!-- ****************************************** -->
		<!-- 			SIDE NAV	 		 			-->
		<!-- ****************************************** -->
		<div class="col-sm-12 col-md-2">
			<h3 class="mb-4">Aller à : </h3>

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
			<h4 class="blue-text text-darken-4" id="assortiment-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload assortiment :</h4>
			<hr>
			<br><br>
			<!-- ASSORTIMENT FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#assortiment-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="assortdate">date : </label>
							<input type="date" class="browser-default form-control" id="assortdate"  name="date" required>
						</div>

					</div>
					<br>
					<div class="form-row">
						<div class="col-sm12">
							<!-- <label for="file">Joindre les fichiers :</label> -->
							<p><input type="file" name="file_1" class='input-file'></p>
						</div>
					</div>
					<br>
					<!-- affichage des erreurs -->
					<?= isset($errorsDisplayAssort) ? $errorsDisplayAssort : ""; ?>
						<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendAssort">Envoyer</button>
						</div>
					</div>
				</form>
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
			<h4 class="blue-text text-darken-4" id="panier-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload panier Promo :</h4>
			<hr>
			<br><br>
			<!-- PANIER PROMO FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#panier-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="panierdate">date : </label>
							<input type="date" class="browser-default form-control" id="panierdate"  name="date" required>
						</div>

					</div>
					<br>
					<div class="form-row">
						<div class="col-sm12">
							<label for="file">Joindre les fichiers :</label>
							<p><input type="file" name="file_1" class='input-file'></p>
						</div>
					</div>
					<br>
					<!-- affichage des erreurs -->
					<?= isset($errorsDisplayPanier) ? $errorsDisplayPanier : ""; ?>
						<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendPanier">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- PANIER PROMO FORM END -->
		</div>
	</div>
	<!-- ****************************************** -->
	<!-- 			SECTION GFK			 			-->
	<!-- ****************************************** -->
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="gfk-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload GFK :</h4>
			<hr>
			<br><br>
			<!-- GFK FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#gfk-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="assortgfk">date : </label>
							<input type="date" class="browser-default form-control" id="assortgfk"  name="date" required>
						</div>

					</div>
					<br>
					<div class="form-row">
						<div class="col-sm12">
							<label for="file">Joindre les fichiers :</label>
							<p><input type="file" name="file_1" class='input-file'></p>
						</div>
					</div>
					<br>
					<!-- affichage des erreurs -->
					<?= isset($errorsDisplayGfk) ? $errorsDisplayGfk : ""; ?>
						<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendGfk">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- GFK FORM END -->
		</div>
	</div>
<!--*********************************  -->
</div> <!--fin container -->

