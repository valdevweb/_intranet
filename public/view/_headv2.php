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

<p> ATTENTION HEADER V2</p>