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
<!-- Demande de retrait information transport par David le 14/02/2018 -->
<!-- 					<p class="w3-panel w3-red" ><i class="fa fa-warning fa-4x" ></i><br>Les arrêtés préfectoraux tombant plus vite que la neige, nous subissons depuis quelques jours un nombre croissant d'interdiction de circulation notamment sur la Région Parisienne étendue depuis ce matin à l'Oise, l' Aisne et la Marne sur les grands axes.<br>
					Nous mettons tout en oeuvre avec nos partenaires transports afin de trouver des solutions pour vous livrer.<br>Néanmoins, un certain nombre de destination ne peuvent être expédiées mais vos commandes sont malgré tout prise en compte et traitées afin de pouvoir vous les livrer dès que la situation va se débloquer.<br>
					Les transporteurs sont en charge de vous avertir individuellement lorsqu'ils ne peuvent pas effectuer la livraison.</p> -->


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
		 <p><a class="send-mail-to" href="pwd.php">Demander mes identifiants</a></p>
		 <!-- <p><a class="send-mail-to" href="help.php">Contacter le service technique</a></p> -->

	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div>

<div class="modal" id="modal-new">
	<div class="modal-content">
		<div class="row">
			<div class="col s2 m2 l2">
			<img src="/_btlecest/public/img/icons/new-orange-sm.png">
			</div>
			<div class="col s10 m10 l10">
				<br>
						<h4 class="orange-text text-darken-3 center">Nouveau sur votre portail !</h4>
					</div>
		</div>
		<!-- <p><a class="send-mail-to" href="#"> Réinitialiser votre mot de passe</a></p> -->
		 <p>Mise à disposition d'une PLV pour la livraison 24/48h : <a href="public/infos/plv-livraison-24-48h.pdf" class="blue-link">télécharger</a></p>
		 <p>Ajout d'une rubrique "documents" où vous trouverez : </p>
	 	 <ul class="browser-default">
		 	<li>les listing des ODR,</li>
		 	<li>les tickets et BRII</li>
		 	<li>le point stock MDD</li>
		 	<li>les résultats GFK</li>
		 </ul>
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
		// ouverture fenetre modal en auto
		$('#modal1').modal();
		$('#modal1').modal('open');
		$('#modal-new').modal();
		$('#modal-new').modal('open');

		$(".dropdown-button").dropdown();
		//infos bulles (navbar)
		 $('.tooltipped').tooltip({delay: 50});
	});
</script>


</body>
</html>