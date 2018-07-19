<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="<?= $tweakcss ?>">
	<link rel="stylesheet" href="<?= $nav ?>">
	<?php
	if(isset($cssFile)){
		echo '<link rel="stylesheet" href="' .$cssFile .'">';
	}

	?>
	<link rel="stylesheet" href="<?= $bootstrap?>">

	<link rel="stylesheet" href="<?= $md_css?>">
	<link rel="stylesheet" href="<?=$awesome ?>">
	<link rel="stylesheet" href="<?= $w3c ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="<?=$jquery ?>"></script>
	<title>Portail BTLec</title>
</head>
<body>

