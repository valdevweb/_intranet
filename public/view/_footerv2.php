<?php
require('../../config/autoload.php');
echo $okko;
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?= SITE_ADDRESS?>/public/css/nav.css">
	<link rel="stylesheet" href="<?=isset($cssFile)? $cssFile: ''?>">
	<link rel="stylesheet" href="<?= SITE_ADDRESS ?>/public/css/main.css">
	<!-- <link rel="stylesheet" href="<?=SITE_ADDRESS?>/vendor/materialize/css/materialize.css"> -->
	<link rel="stylesheet" href="<?=SITE_ADDRESS?>/vendor/bootstrap/css/bootstrap.min.css">

	<link rel="stylesheet" href="<?=SITE_ADDRESS?>/vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=SITE_ADDRESS?>/vendor/w3c/w3c.css">
	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="<?=SITE_ADDRESS ?>/vendor/jquery/jquery-3.2.1.js"></script>
	<title><?= isset($title) ? 'Portail BTLec' .' - '. $title : 'Portail BTLec';?></title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<p> ATTENTION HEADER V2</p>

</div>


<footer class="footer">
	<div class="row">
		<div class="col"></div>
		<div class="col"><img  class="logoBt rounded mx-auto d-block" src="<?=$img ?>navbar/logo-bt-mini.jpg"></div>
		<div class="col"></div>
	</div>
	<div class="row bluebg">

		<div class="container">
			<div class="row">
				<div class="col">
					<h5 class="white-text">BTLEC EST</h5>
					<p>2 rue des Moissons - Parc d'activité Witry Caurel</p>
					<p>51420 Witry les Reims</p>
					<p class="logo-footer"> <img src="<?= $img ?>footer/eleclercblue.jpg"></p>

				</div>
				<div class="col">
					<h5 class="white-text">Nous contacter</h5>
					<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp; 03 26 89 86 88<br></p>
					<p><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp;<a class="white-text" href="<?= ROOT_PATH?>/public/mag/main-contact.php">Envoyer un mail à BTlec</a>

					</div>
					<div class="col">
						<h5 class="white-text">Plus d'infos</h5>
						<p>
							<i class="fa fa-globe" aria-hidden="true"></i>&nbsp; &nbsp;<a class="white-text" href="<?=ROOT_PATH?>/public/mag/google-map.php">Venir à BTlec</a>
						</p>
					</div>
				</div>


			</div>
		</div>
	</footer>
<!--  Scripts-->
<script src="<?= $md_js ?>"></script>
<script src="<?= $main_js ?>"></script>
<script src="<?= $dashboard_js ?>"></script>
<script src="<?= $sorttable_js?>"></script>

</body>
</html>