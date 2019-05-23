<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="../css/font.css">
	<!-- nouvelle page pour style commun qui remplacera main  05/02/2019 -->
	<link rel="stylesheet" href="../css/commun.css?<?= filemtime('../css/commun.css');?>">
	<link rel="stylesheet" href="../css/nav.css">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<!-- style propre  -->
	<?php
	if(isset($cssFile))
	{
		// echo $cssFile;
		$explodedCssfile=explode('/',$cssFile);

		$cssFileName=$explodedCssfile[count($explodedCssfile)-1];
		// $cssFileName='../css/'.$cssFileName;


		if(file_exists('../css/'.$cssFileName)){
			echo '<link rel="stylesheet" href="' .$cssFile .'?'.filemtime('../css/commun.css').'">';
		}
	}
	?>

	<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.css">
	<!-- new fontawesome -->
	<link href="../../vendor/fontawesome5/css/all.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="../../vendor/jquery/jquery-3.2.1.min_ex.js"></script>

	<title>Portail BTLec</title>
</head>
<body>


