<?php

require('config/autoload.php');
//$okko= 'version : ' . ROOT_PATH  .', db  : '.$pdo_file;
require 'functions/stats.fn.php';

// on connecte l'utilisateur et recup $_SESSION['id']=$id (id web_user) et $_SESSION['user']=$_POST['login'];
require('functions/login.fn.php');
$err='';
if(!empty( $_SERVER['QUERY_STRING']))
	{
		//on met le goto dans champ cahcé du formulaire et la fonction de login recupère la valeur $_POST['goto'] pour la mettre dans session
		$gotoMsg=$_SERVER['QUERY_STRING'];

	}
// stats
if(isset($_POST['connexion']))
	{

		extract($_POST);
		$err=login($pdoUser, $pdoBt);
		$action="user authentification";
		$page=basename(__file__);
		authStat($pdoStat,$page,$action, $err[0]);
		if($err[0]=="user authentifié")
		{
			 header('Location:'. ROOT_PATH. '/public/home.php');
		}
	}
/*---------------------------------------------------------------------*/
/* 						reversements     							*/
/*---------------------------------------------------------------------*/


function rev($pdoBt)
{
	$today=date('Y-m-d H:i:s');
	$todayMoinsSept=date_sub(date_create($today), date_interval_create_from_date_string('7 days'));
	$todayMoinsSept=date_format($todayMoinsSept,'Y-m-d H:i:s');

	$req=$pdoBt->prepare("SELECT divers, date_rev,doc_type.name, id_type, DATE_FORMAT(date_rev,'%d/%m/%Y') as date_display FROM reversements LEFT JOIN doc_type ON reversements.id_type = doc_type.id WHERE date_rev >= :todayMoinsSept AND date_rev <= :today ");
	$req->execute(array(
		':todayMoinsSept' =>$todayMoinsSept,
		':today'	=>$today
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$revRes=rev($pdoBt);
$nbRev=count($revRes);

if(!empty($revRes))
{
	$infoRev='<div class="row"><div class="col l2"><img src="public/img/icons/new-orange-sm.png"></div>';
	$infoRev.='<div class="col l10"><br><h4 class="orange-text text-darken-3 center">Reversements disponibles sur docubase</h4></div></div><ul class="browser-default">';
	foreach ($revRes as $key => $thisRev)
	{
		if($thisRev['id_type']==18)
		{
			$infoRev.='<li> ' .$thisRev['divers'] . ' du ' .$thisRev['date_display'] .'</li>';

		}
		else
		{
			$infoRev.='<li> ' .$thisRev['name'] . ' du ' .$thisRev['date_display'] .'</li>';
		}
	}
	$infoRev.='</ul>';
}




?>



<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="public/css/index.css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="vendor/materialize/css/materialize.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/w3c/w3c.css">
	<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
	<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
	<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
	<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html class=""> <!--<![endif]-->

	<title>Connexion - portail Btlec Est</title>
</head>
<body>
	<div id="main">
		<header class="w3-container center">
			<img class="resize" src="public/img/index/bttransfull.png">
		</header>
			<!-- carte centrales -->
			<div class="line">
			<div class="sixty">
				<p class="france center"><img class="img-france" src="public/img/index/france-new.png"></p>
				<h3 class="center">BTLEC, c'est aussi des structures SAV :</h3>
				<br>
				<p class="center"><a href="http://scapsav.fr/"><img class="shadow" src="public/img/index/scapsav.png"></a></p>
				<p>&nbsp;</p>
			</div>
			<!-- droite : accueil, photo, connection -->
			<div class="fourty">
				<!-- ici -->

					<h2>BTLec Est - Centrale d'Achat</h2>
					<h3>Bazar Technique E.Leclerc </h3>

					<p><img class="img-max" id="boxshadow" src="public/img/index/front-bt-800.jpg"></p>
					<?php
					if(!empty($err)){
						foreach ($err as $errStrg)
						{
							echo "<p class='w3-red'>" . $errStrg ."</p>";
						}
					}
					?>
					<p class="margin-up">
						<button id="log" class="btn waves-effect waves-default white grey-text text-darken-3 darken-3 modal-trigger" data-target="modal1">Se connecter</button>
					</p>


			</div>
		</div>
<!-- ############################################################################################################################### -->
<!-- 							version mobile : la carte passe en dessous de la partie accueil/connexion 							-->
<!-- ############################################################################################################################### -->

	<div class="mobile">
		<p><img class="img-max" src="public/img/index/france-gris.png"></p>

	</div>

	<div class="index-footer">
		<img class="logo-index-galec" src="public/img/index/leclerc-200.png">

	</div>

</div>
<!-- ############################################################################################################################### -->
<!--  												./main 																			 -->
<!-- ############################################################################################################################### -->

<!-- ############################################################################################################################### -->
<!--  												MODAL CONNEXION FORM 																			 -->
<!-- ############################################################################################################################### -->

<div class="modal" id="connexion">
	<div class="modal-content">
		<h4>Connexion</h4>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>"  method="post">
			<div class="modal-form-row">
				<div class="input-field">
					<input id="login" name="login" placeholder="identifiant" type="text" class="validate" autofocus >
					<label for="login"></label>
				</div>
			</div>
			<div class="modal-form-row">
				<div class="input-field">
					<input id="pwd" name="pwd" placeholder="mot de passe" type="password">
					<label for="pwd"></label>
				</div>
			</div>
			<input type='hidden' name='goto' value='<?php if(!empty($gotoMsg)){echo $gotoMsg;} ?>'>
			<div class=".modal-form-row">
				<button class="btn waves-effect waves-light light-blue darken-3" type="submit" name="connexion">Connexion
				</button>
			</div>
		</form>
		 <p><a class="send-mail-to" href="pwd.php">Demander mes identifiants</a></p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div>
<!-- ############################################################################################################################### -->
<!--  												MODAL INFOS																		 -->
<!-- ############################################################################################################################### -->
<!-- id info => permet de tester si on doit ou non afficher le modal -->
<div class="modal" id="modal-new">
	<div class="modal-content" id="info">

		<!-- info 1 -->
		<div class="row">
			<div class="col l2">
				<img src="public/img/icons/new-orange-sm.png">
			</div>
			<div class="col l10">
				<br>
				<h4 class="orange-text text-darken-3 center">Date des reversements</h4>
				<h5 class="center">(à l'attention des services comptabilité)</h5>
			</div>
		</div>
		<p>Vous pouvez retrouver les dates des virements avec le type de reversement dans le menu : documents/Compta/reversements</p>
		<!-- fin d'info1 -->

		 <?php echo isset($infoRev) ? $infoRev:"";?>

		<!-- modal footer -->
		<div class="modal-footer">
			<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
		</div>
	</div>
</div>

<!--  Scripts-->
<script src="vendor/jquery/jquery-3.2.1.js"></script>
<script src="vendor/materialize/js/materialize.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// menu hamburger
		$(".button-collapse").sideNav();

		// ouverture fenetre modal en auto : dernier modal s'ouvre en 1er
		$('#connexion').modal();
		$('#connexion').modal('open');

		// condition d'ouverture du modal d'info : existance de div de class row dans la div id info
		// var modalInfo=$('#info div.row').length
		// if(modalInfo == 0)
		// {
		// 	// console.log("rien");
		// }
		// else
		// {
		// 	$('#modal-new').modal();
		// 	$('#modal-new').modal('open');

		// }
		$(".dropdown-button").dropdown();
		//infos bulles (navbar)
		 $('.tooltipped').tooltip({delay: 50});




	});
</script>


</body>
</html>