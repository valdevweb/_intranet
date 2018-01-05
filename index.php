<?php

require('config/autoload.php');
//$okko= 'version : ' . ROOT_PATH  .', db  : '.$pdo_file;
require 'functions/stats.fn.php';

// on connecte l'utilisateur et recup $_SESSION['id']=$id (id web_user) et $_SESSION['user']=$_POST['login'];
require('functions/login.fn.php');
$err='';
if(!empty( $_SERVER['QUERY_STRING']))
	{
		$gotoMsg=$_SERVER['QUERY_STRING'];

	}

if(isset($_POST['connexion']))
	{

		extract($_POST);
		$err=login($pdoUser);
		$action="user authentification";
		$page=basename(__file__);
		authStat($pdoStat,$page,$action, $err);
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
	<title>Connexion - portail Btlec Est</title>
</head>
<body>
	<div id="main"><header class="w3-container center">
					<img class="resize" src="public/img/index/bttransfull.png">


				<!-- <h1>BTLEC Est</h1> -->

	<!-- 		<div id="w3-col l4"><img class="logo-index" src="public/img/index/bt300.jpg"></div> -->


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

					<p><img class="img-max" id="boxshadow" src="public/img/index/bt-front-office-optimized.jpg"></p>
					<?php if(!empty($err)): ?>
						<p class="w3-red"><?= $err ?></p>
					<?php endif; ?>
					<p class="margin-up-and-down">
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




	</div><!--main-->

<!-- MODAL FORM  MAG-->
<div class="modal" id="modal1">
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
		<!-- <p><a class="send-mail-to" href="#"> Réinitialiser votre mot de passe</a></p> -->
		 <p><a class="send-mail-to" href="help.php">Contacter le service technique</a></p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div>





<!--  Scripts-->
<script src="vendor/jquery/jquery-3.2.1.js"></script>
<script src="vendor/materialize/js/materialize.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// menu hamburger
		$(".button-collapse").sideNav();
		// ouverture fenetre modal
		$('.modal').modal();
		$(".dropdown-button").dropdown();
		//infos bulles (navbar)
		 $('.tooltipped').tooltip({delay: 50});
	});
</script>


</body>
</html>