<?php

require('config/autoload.php');
require 'config/db-connect.php';
require 'functions/stats.fn.php';
require('Class/CmRdvDao.php');
require('Class/FlashDao.php');

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
	echo "<pre>";
	print_r($loginExist);
	echo '</pre>';


	if($loginExist){
		$pwdOk=checkPwd($loginExist,$pdoMag, $pdoUser);
	}else{
		$msg[]="le login n'existe pas";
	}
	if($pwdOk){
		initSession($pdoBt, $pdoSav, $pdoMag, $pdoCm, $pdoUser, $loginExist);
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

$flashDao=new FlashDao($pdoBt);
$listFlash=$flashDao->getListFlashBySite((new DateTime())->format('Y-m-d'),'portail_bt');


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
	<link href="vendor/fontawesome5/css/all.css" rel="stylesheet">

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
				<?php if (isset($listFlash) && !empty($listFlash)): ?>
				<?php foreach ($listFlash as $key => $flash): ?>

					<div class="row infos">
						<div class="col-1"></div>
						<i class="pin"></i>
						<div class="col px-5 inside-infos">
							<p class="center-text pt-2"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></p>
							<h4 class="orange-text text-darken-3 text-center"><?=$flash['title']?></h4>
							<p class="text-left"><?=$flash['content']?></p>
						</div>
						<div class="col-1"></div>
					</div>
				<?php endforeach ?>

			<?php endif ?>

			<!-- fin d'info1 -->
			<!-- flash info auto si reversement -->
			<?php echo isset($infoRev) ? $infoRev:"";?>


		</div>
	</div>

</div>
<!-- ############################################################################################################################### -->
<!--  												./container 																			 -->
<!-- ############################################################################################################################### -->

<!-- ############################################################################################################################### -->
<!--  												MODAL CONNEXION FORM 																			 -->
<!-- ############################################################################################################################### -->


	<!-- 	<div class="modal-footer">
			<p class=text-center><a href="#!" class="modal-action modal-close">fermer</a></p>
		</div> -->
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
		</div>
		<!--  Scripts-->
		<script src="vendor/jquery/jquery-3.2.1.js"></script>
		<script src="vendor/materialize/js/materialize.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#connexion').modal();
				$('#connexion').modal('open');
			});
		</script>
	</body>
	</html>