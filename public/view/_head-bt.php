<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="../css/font.css">
	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />

	<!-- nouvelle page pour style commun qui remplacera main  05/02/2019 -->
	<link rel="stylesheet" href="../css/commun.css">
	<link rel="stylesheet" href="../css/_box.css">
	<link rel="stylesheet" href="../css/_btn.css">
	<link rel="stylesheet" href="../css/_font.css">
	<link rel="stylesheet" href="../css/_txt.css?<?= filemtime('../css/commun.css');?>">
    <link rel="stylesheet" type="text/css" href="../css/datatables.css">

	<link rel="stylesheet" href="../css/nav.css?<?= filemtime('../css/nav.css');?>">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<!-- style propre  -->
	<?php
	if(isset($cssFile))
	{

		$hexplodedCssfile=explode('/',$cssFile);

		$hcssFileName=$hexplodedCssfile[count($hexplodedCssfile)-1];
		$hcssUrl='../css/'.$hcssFileName;

		if(file_exists($hcssUrl)){
			echo '<link rel="stylesheet" href="' .$hcssUrl .'?'.filemtime($hcssUrl).'">';
		}
	}
	?>

	<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../../vendor/jquery-ui/jquery-ui.css">
	<link href="../../vendor/fontawesome5/css/all.css" rel="stylesheet">

	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="../../vendor/jquery/jquery-3.2.1.min_ex.js"></script>
	<script src="../../vendor/jquery-ui/jquery-ui.js"></script>
	<script src="../../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../js/nav.js"></script>
	<script src="../../vendor/igorescobar/jquery-mask-plugin/src/jquery.mask.js"></script>

	<title>Portail BTLec</title>
</head>
<body>


