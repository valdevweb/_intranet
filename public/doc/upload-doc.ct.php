<div class="container" id="top">
	<h1 class="blue-text text-darken-4">Upload des documents</h1>
	<br><br>
	<div class="row">
		<!-- ****************************************** -->
		<!-- 			SECTION ODR 		 			-->
		<!-- ****************************************** -->
		<!-- colonne gauche -->
		<?php ob_start() ?>
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
				<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>
			<!-- ODR FORM END -->
		</div> <!-- fin col de gauche!-->
		<?php
		$odrSection=ob_get_contents();
		ob_end_clean();
		if($comm || $admin)
		{
			echo $odrSection;
		}
		 ?>

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
					<a class="nav-link" href="#tel-title">Tickets /BRII</a>
				</li>
				<?php ob_start() ?>
				<li class="nav-item">
					<a class="nav-link" href="#assortiment-title">Assortiment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#panier-title">Panier promo</a>
				</li>
				<?php
				$adminOnlyUn=ob_get_contents();
				ob_end_clean();
				if($admin)
				{
					echo $adminOnlyUn;
				}
				 ?>
				<li class="nav-item">
					<a class="nav-link" href="#mdd-title">MDD</a>
				</li>
				<?php ob_start() ?>
				<li class="nav-item">
					<a class="nav-link" href="#gfk-title">GFK</a>
				</li>
				<?php
				$adminOnlyDeux=ob_get_contents();
				ob_end_clean();
				if($admin)
				{
					echo $adminOnlyDeux;
				}
				 ?>
			</ul>
		</div><!--fin side nav-->
	 </div><!-- 	fin 1st row -->
	<br><br>
	<!-- ****************************************** -->
	<!-- 			SECTION TEL / BRII 			-->
	<!-- ****************************************** -->
	<?php ob_start() ?>
	<div class="row">
				<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="tel-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload des tickets / BRII :</h4>
			<hr>
			<br><br>
			<!-- ASSORTIMENT FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#tel-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="teldate">date : </label>
							<input type="date" class="browser-default form-control" id="teldate"  name="date" required>
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
					<?= isset($errorsDisplayTel) ? $errorsDisplayTel : ""; ?>
						<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendTel">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- ASSORTIMENT FORM END -->
			<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>
			<!-- <p class="right-align"><a href="#up" class="blue-link">retour</a></p> -->

		</div>

		<!-- <div class="col-sm-12 col-md-2"> -->
		<!-- </div> -->
	</div>
	<?php
		$telSection=ob_get_contents();
		ob_end_clean();
		if($comm || $admin)
		{
			echo $telSection;
		}
    ?>
	<!-- ****************************************** -->
	<!-- 			SECTION ASSORTIMENT 			-->
	<!-- ****************************************** -->
	<?php ob_start() ?>
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="assortiment-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload de l'assortiment :</h4>
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
			<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>
		</div>

		<!-- <div class="col-sm-12 col-md-2"> -->
	</div>
	<?php
		$assortimentSection=ob_get_contents();
		ob_end_clean();
		if( $admin)
		{
			echo $assortimentSection;
		}
	?>
	<!-- </div> -->
	<!-- ****************************************** -->
	<!-- 			SECTION PANIER PROMO 			-->
	<!-- ****************************************** -->
	<?php ob_start(); ?>
		<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="panier-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload du panier Promo :</h4>
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
			<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>
			<!-- PANIER PROMO FORM END -->
		</div>
	</div>
	<?php
		$panierSection=ob_get_contents();
		ob_end_clean();
		if( $admin)
		{
			echo $panierSection;
		}
	?>
	<!-- ****************************************** -->
	<!-- 			SECTION mdd 			-->
	<!-- ****************************************** -->
	<?php ob_start() ?>
	<div class="row">
		<div class="col-sm-12 col-md-10">
			<h4 class="blue-text text-darken-4" id="mdd-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload des MDD :</h4>
			<hr>
			<br><br>
			<!-- PANIER PROMO FORM START -->
			<div class="box-bd">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>#mdd-title"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col-sm12 col-md-6">
							<label for="mdddate">date : </label>
							<input type="date" class="browser-default form-control" id="mdddate"  name="date" required>
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
					<?= isset($errorsDisplayMdd) ? $errorsDisplayMdd : ""; ?>
						<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="sendMdd">Envoyer</button>
						</div>
					</div>
				</form>
			</div>
			<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>

			<!-- PANIER PROMO FORM END -->
		</div>
	</div>
	<?php
		$mddSection=ob_get_contents();
		ob_end_clean();
		if($comm || $admin)
		{
			echo $mddSection;
		}
	?>
	<!-- ****************************************** -->
	<!-- 			SECTION GFK			 			-->
	<!-- ****************************************** -->
	<?php ob_start(); ?>
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
			<p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p>
			<!-- GFK FORM END -->
		</div>
	<?php
		$gfkSection=ob_get_contents();
		ob_end_clean();
		if($admin)
		{
			echo $gfkSection;
		}
	 ?>
	</div>
<!--*********************************  -->
</div> <!--fin container -->

