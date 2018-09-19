<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>


	<link rel="stylesheet" href="<?= ROOT_PATH?>/public/css/main.css">
	<link rel="stylesheet" href="<?=ROOT_PATH?>/public/css/nav.css">
	<link rel="stylesheet" type="text/css" href="<?= ROOT_PATH?>/public/css/footer.css">
	<?php
	if(isset($cssFile)){
		echo '<link rel="stylesheet" href="' .$cssFile .'">';
	}
	?>

	<link rel="stylesheet" href="<?=ROOT_PATH?>/vendor/bootstrap/css/bootstrap.css">


	<link rel="stylesheet" href="<?=ROOT_PATH?>/vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= ROOT_PATH ?>/vendor/w3c/w3c.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="<?=$jquery ?>"></script>
	<title>Portail BTLec</title>
</head>
<body>


