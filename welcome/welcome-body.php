
<!DOCTYPE html>
<html lang="fr">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />
	<link rel="stylesheet" href="welcome.css">
	<link rel="stylesheet" href="http://172.30.92.53/_btlecest/public/css/font.css">

	<link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css">
	<link href="../vendor/fontawesome5/css/all.css" rel="stylesheet">
	<title>Bienvenue à BTLec</title>
</head>
<body>
	<div class="container-fluid">

		<div class="row mb-1">
			<div class="col-auto my-auto">
				<h1 class="open">N'oubliez pas  </h1>
			</div>
			<div class="col ">
				<img class="" src="covid.png">
			</div>
		</div>

		<!-- 1 LIGNE COMPLETE **********************************************************************************************************************************************************-->



		<div class="row mb-1">
			<div class="col align-self-center text-center">
				<img class="" src="../public/img/logo_bt/bt300.jpg">
			</div>

			<!-- COLONNE GAUCHE LIGNE 1 -->
			<div class="col-6">
				<section class="jour">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-jour.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'welcome-inc/01jour.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour orangish">
											<?php include 'welcome-inc/02jour.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<!-- COLONNE DROITE LIGNE 1 -->
			<div class="col"></div>
		</div>
		<!-- FIN 1 LIGNE COMPLETE ********************************************************************************************************************************************* -->
		<!-- 2 LIGNE COMPLETE **********************************************************************************************************************************************************-->
		<div class="row mb-1">
			<!-- COLONNE GAUCHE LIGNE 2 -->
			<div class="col-6">
				<section class="mois">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-encours.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'welcome-inc/03mois.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col-6 bg-pourcentage-mois whitten">
											<?php include 'welcome-inc/04mois.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<!-- COLONNE DROITE LIGNE 2 -->
			<div class="col-6">
				<section class="end">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-findemois.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'welcome-inc/05moisfin.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour darken">
											<?php include 'welcome-inc/06moisfin.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

		<!-- FIN 2 LIGNE COMPLETE ********************************************************************************************************************************************* -->

		<!-- 3 LIGNE COMPLETE **********************************************************************************************************************************************************-->
		<div class="row">
			<!-- COLONNE GAUCHE LIGNE 3 -->
			<div class="col-6">
				<section class="year">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-year.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'welcome-inc/07annee.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour whitten">
											<?php include 'welcome-inc/08annee.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<!-- COLONNE DROITE LIGNE 3 -->
			<div class="col-6">
				<section class="end-year">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-finannee.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'welcome-inc/09anneefin.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour darken">
											<?php include 'welcome-inc/10anneefin.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- FIN 3 LIGNE COMPLETE ********************************************************************************************************************************************* -->



		<div class="row">
			<div class="col">
				<p class="text-center mt-5"><a href="http://172.30.101.8:8089/open/homepage" target="_blank">Badgeuse - Bodet</a></p>
				<p class="text-right">Dernière mise à jour : <?=(new DateTime())->format('H').'h'.(new DateTime())->format('i')?></p>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		var today = new Date().getTime();
		var start=new Date();
		start=start.setHours(4,0,0,0);
		var end=new Date();
		end=end.setHours(12,30,0,0);


		console.log(today);
		console.log(start);
		console.log(end);

		if(today>start && today < end){

			setTimeout(function(){
				window.location.reload(1);
			}, 900000);
		}else{
			console.log("no reload")

		}

	</script>



</body>
</html>