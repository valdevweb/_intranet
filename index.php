<?php

require('config/autoload.php');
require 'functions/stats.fn.php';

// on connecte l'utilisateur et recup $_SESSION['id']=$id (id web_user) et $_SESSION['user']=$_POST['login'];
require('functions/login.fn.php');

/*---------------------------------------------------------------------*/
/* 						détection navigateur     							*/
/*---------------------------------------------------------------------*/

$thisAGENT = $_SERVER['HTTP_USER_AGENT'];
$findme    = 'MSIE';
$pos1 = stripos($thisAGENT, $findme);
$pos2 = stripos($thisAGENT, 'Trident');

if ($pos1 !== false)
{
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Votre navigateur n\'est pas compatible avec les portails BTLec et SAV.<br>veuillez utiliser Google Chrome</h1><br>';
	echo '<center><img  style="text-align-center" src="public/img/index/chrome.png"></center>';
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Ou Mozilla Firefox</h1>';
	echo '<center><img  style="text-align-center" src="public/img/index/firefox.png"></center>';
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Merci de votre compréhension<br><br></h1>';
	exit;
}
elseif($pos2 !==false)
{
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Votre navigateur n\'est pas compatible avec les portails BTLec et SAV.<br>veuillez utiliser Google Chrome</h1><br>';
	echo '<center><img  style="text-align-center" src="public/img/index/chrome.png"></center>';
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Ou Mozilla Firefox</h1>';
	echo '<center><img  style="text-align-center" src="public/img/index/firefox.png"></center>';
	echo '<h1 style="font-family: arial, sans-serif; text-align: center">Merci de votre compréhension<br><br></h1>';
	exit;
}




if(!empty( $_SERVER['QUERY_STRING'])){
		//on met le goto dans champ cahcé du formulaire et la fonction de login recupère la valeur $_POST['goto'] pour la mettre dans session
	$gotoMsg=$_SERVER['QUERY_STRING'];
}
// stats
if(isset($_POST['connexion'])){
	$loginExist=loginExist($pdoUser);
	if($loginExist){
		$pwdOk=checkPwd($loginExist,$pdoMag);
	}else{
		$msg[]="le login n'existe pas";
	}
	if($pwdOk){
		initSession($pdoBt, $pdoSav, $pdoMag, $loginExist);
		if(isset($_SESSION['id_web_user']) && !empty($_SESSION['id_web_user'])){
			$action="user authentification";
			$page=basename(__file__);
			addRecord($pdoStat,$page,$action, "user authentifié");
			// maj mdp nohash si vide
			$dateMajPwd=getDateMajNohash($pdoUser);
			if($dateMajPwd['date_maj_nohash']==null){
				updateNoHash($pdoUser);
			}
			header('Location:'. ROOT_PATH. '/public/home/home.php');
		}
	}else{
		$msg[]="erreur de mot de passe";
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
	$infoRev='<div class="row infos"><div class="col-1"></div>';
	$infoRev.='<i class="pin"></i><div class="col px-5 inside-infos"><p class="center-text pt-2"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></p><h4 class="orange-text text-darken-3 text-center">Reversements disponibles sur docubase</h4><ul class="browser-default text-left">';
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
	$infoRev.='</div><div class="col-1"></div></div>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="public/css/index.css?<?= filemtime('public/css/index.css');?>">
	<link rel="stylesheet" href="public/css/xmas.css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="vendor/materialize/css/materialize.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/w3c/w3c.css">

	<title>Connexion - portail Btlec Est</title>
</head>
<body>
	<div class="container-fluid text-center sky">

		<div class="row">

			<div class="col-sm-12 col-lg-6">
				<p class="text-center mt-5"><img id="img-france" src="public/img/index/france-new.png"></p>
				<h3 class="center text-white">BTLEC, c'est aussi des structures SAV :</h3>
				<br>
				<p class="center"><a href="http://scapsav.fr/"><img class="shadow" src="public/img/logos/sav-logo-200.jpg"></a></p>
				<p>&nbsp;</p>
			</div>


			<!-- photo bt -->
			<div class="col-sm-12 col-lg-6">
				<h1>BTLec Est - Centrale d'Achat</h1>
				<h3 class="mb-4 text-white pb-3">Bazar Technique E.Leclerc </h3>
				<p><img id="photo-bt" class="boxshadow" src="public/img/index/front-bt-800.jpg"></p>
				<?php
				if(!empty($msg)){
					foreach ($msg as $msgStrg)
					{
						echo "<p class='w3-red'>" . $msgStrg ."</p>";
					}
				}
				?>
				<!-- flashs info -->
				<div class="row">
					<div class="col-1"></div>
					<div class="col bg-white text-danger">
						<h5>MISE A JOUR INFORMATION LIVRAISONS du 02/04/2020</h5>
						<h5> BTlec continue d'assurer les livraisons</h5>
						<h5><i class="fa fa-exclamation-circle pr-3"></i>Reprise des livraisons 48h TNT dans la région Grand Est :<br> redémarrage du service lundi 6 avril</h5>
					</div>
					<div class="col-1"></div>


				</div>
				<div class="row align-items-center infos mt-2">
					<div class="col">
						<!-- <div class="slide"><h1 class="text-white">Le salon BTLec Est 2020 </h1></div> -->
						<div class="row">
							<div class="col-4"></div>
							<div class="col">
								<h3  class="text-white text-center">SALON 2020</h3>
								<p class="text-white appear text-center">Cette année, le salon aura lieu mardi 9 et mercredi 10 juin</p>

							</div>
						</div>

					</div>

					<div class="col">
						<p class="text-center"><img class="salon-img"  src="public/img/salon/date_anim.gif"></p>
					</div>


				</div>

				<!-- <div class="row infos">
					<div class="col-1"></div>
					<i class="pin"></i>
					<div class="col px-5 inside-infos">
						<p class="center-text pt-2"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></p>
						<h4 class="orange-text text-darken-3 text-center">INFORMATION LIVRAISON - IMPORTANT</h4>

						<p class="text-left">Compte tenu des informations à notre disposition à cet instant, nous vous informons que les commandes 24/48h00 des mercredi  4 et jeudi 5 décembre sont supprimées.
En fonction de l'évolution de la situation nous vous informerons sur le traitement ou non des commandes du vendredi 6 décembre.


Concernant les livraisons hebdomadaires magasins, nous n'avons pas à l'heure actuelle de confirmation de point de blocage. Par conséquent, nous aviserons en fonction des remontées d'information que nous aurons.</p>
					</div>
					<div class="col-1"></div>
				</div> -->
					<!-- <div class="row infos">
					<div class="col-1"></div>
					<i class="pin"></i>
					<div class="col px-5 inside-infos">
						<p class="center-text pt-2"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></p>
						<h4 class="orange-text text-darken-3 text-center">Attention :</h4>
						<p class="text-center"><b>Les lundi 24 et 31 décembre</b></p>
						<p class="text-left">Pas de commandes 24/48H disponibles en raison du jour férié le lendemain.</p>
					</div>
					<div class="col-1"></div>
				</div>
			-->
			<!-- <div class="row infos">
				<div class="col-2"></div>

				<div class="col inside-infos inside-padding">
					<img class="float-left" src="http://172.30.92.53/upload/flash/vignette-20190722-043439.gif">
					<p class="w3-text-blue"><b>Les books Promo PEM vont être livrés à l'attention du Responsable Bazar Technique et du Directeur </b>par un coursier qui se présentera à l'accueil de votre magasin<br><br>
						Nous vous demandons de prévenir votre personnel à l'accueil et de bien préciser de ne pas refuser ce colis qui est stické des destinataires "Responsable Bazar Technique et Directeur"<br>
						En cas de refus aucun book ne sera renvoyé en magasin<br><br>

						<b>Date de livraison : entre jeudi 25 juillet et mardi 30 juillet</b>

					</p>
				</div>
				<div class="col-2"></div>

			</div> -->

			<!-- fin d'info1 -->
			<!-- flash info auto si reversement -->
			<?php echo isset($infoRev) ? $infoRev:"";?>


		</div>
	</div>
	<!-- 	<div class="row no-gutters bg-white logo-line">
			<div class="col no-gutters">
				<img id="logo-bt" src="public/img/index/bttransfull.png">
			</div>
		</div> -->
	</div>
	<!-- ############################################################################################################################### -->
	<!--  												./container 																			 -->
	<!-- ############################################################################################################################### -->

	<!-- ############################################################################################################################### -->
	<!--  												MODAL CONNEXION FORM 																			 -->
	<!-- ############################################################################################################################### -->

	<div class="modal" id="connexion">
		<div class="modal-content">
			<p class="text-center text-primary">Portails BT et SAV</p>
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
				<div class="modal-form-row text-center">
					<button class="btn waves-effect waves-light light-blue darken-3" type="submit" name="connexion">Connexion
					</button>
				</div>
			</form>
			<p class="identif"><a class="send-mail-to" href="pwd.php"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>Demander mes identifiants</a></p>
		</div>
	<!-- 	<div class="modal-footer">
			<p class=text-center><a href="#!" class="modal-action modal-close">fermer</a></p>
		</div> -->
	</div>
	<!--  Scripts-->
	<script src="vendor/jquery/jquery-3.2.1.js"></script>
	<script src="vendor/materialize/js/materialize.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
		// ouverture fenetre modal en auto : dernier modal s'ouvre en 1er
		$('#connexion').modal();
		$('#connexion').modal('open');







	});



</script>


</body>
</html>