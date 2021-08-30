
<!DOCTYPE html>
<html lang="fr">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Khand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />
	<link rel="stylesheet" href="welcome.css">
	<link rel="stylesheet" href="http://172.30.92.53/_btlecest/public/css/font.css">

	<link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css">
	<link href="../vendor/fontawesome5/css/all.css" rel="stylesheet">
	<title>Bienvenue à BTLec</title>
</head>
<body>
	<div class="container-fluid" style="margin-top:100px;">
		<div class="row">
			<div class="col-1"></div>
			<div class="col-10" style="border: solid #1976D2 15px; border-radius: 10px;">

				<div class="row mb-1">
					<div class="col"></div>
					
					<!-- COLONNE GAUCHE LIGNE 1 -->
					<div class="col-6">
						<section class="jour">
							<div class="row bg">
								<div class="col">
									<div class="row">
										<!-- col jour -->
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
												<div class="col bg-pourcentage-jour orangish" >
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
					<div class="col" style="margin-top: -30px;">
						<p class="text-center mt-5" ><a href="http://172.30.101.8:8089/open/homepage" target="_blank">Badgeuse - Bodet</a></p>
						<p class="text-right">Dernière mise à jour : <?=(new DateTime())->format('H').'h'.(new DateTime())->format('i')?></p>
					</div>
				</div>

			</div>
			<div class="col-1"></div>
		</div>

		<!-- <div class="row">
			<div class="col-1"></div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/badgeuse.jpg">
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/fleur-de-lotus.jpg">
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/economie-energie.jpg">
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/journal-interne.jpg">
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/protegeons-nous.jpg">
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px;">
				<img class="img-fluid" style="height: 150px;" src="../public/img/je-signale.jpg">
			</div>
			<div class="col-1"></div>
		</div>
		<div class="row">
			<div class="col-1"></div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="http://172.30.101.8:8089/open/login">Badget</a>
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="#">Prenez soin de vous, au quotidien</a>
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="#">Faites des économies d'énergie</a>
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="#">Le journal interne</a>
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="#">COVID 19 - Protégeons-nous les uns les autres</a>
			</div>
			<div class="col align-self-center text-center" style="margin-top: 20px; font-size: 1.2vw;">
				<a href="#">Sécurité, environnement, énergie</a>
			</div>
			<div class="col-1"></div>
		</div> -->






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