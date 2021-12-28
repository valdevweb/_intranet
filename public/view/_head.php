<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />
	<link rel="stylesheet" href="<?=ROOT_PATH ."/public/css/main.css"?>">
    <link rel="stylesheet" href="<?= ROOT_PATH ."/public/css/nav.css"?>">
	<?php
	if(isset($cssFile)){
		$hexplodedCssfile=explode('/',$cssFile);
		$hcssFileName=$hexplodedCssfile[count($hexplodedCssfile)-1];
		$hcssUrl='../css/'.$hcssFileName;
		if(file_exists($hcssUrl)){
			echo '<link rel="stylesheet" href="' .$hcssUrl .'?'.filemtime($hcssUrl).'">';
		}
	}
	?>
	<link rel="stylesheet" href="<?= ROOT_PATH ."/vendor/materialize/css/materialize.css"?>">
	<link rel="stylesheet" href="<?=ROOT_PATH . "/vendor/fontawesome/css/font-awesome.min.css" ?>">
    <link rel="stylesheet" href="<?=ROOT_PATH ."/vendor/w3c/w3c.css"?>">
	<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.css">


	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="<?=ROOT_PATH."/vendor/jquery/jquery-3.2.1.js" ?>"></script>
	<script src="../js/nav.js"></script>

	<title>Portail BTLec</title>
</head>
<body>

