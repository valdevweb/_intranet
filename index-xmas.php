<?php

require('config/autoload.php');
require 'functions/stats.fn.php';

// on connecte l'utilisateur et recup $_SESSION['id']=$id (id web_user) et $_SESSION['user']=$_POST['login'];
require('functions/login.fn.php');
$err='';
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




if(!empty( $_SERVER['QUERY_STRING']))
{
		//on met le goto dans champ cahcé du formulaire et la fonction de login recupère la valeur $_POST['goto'] pour la mettre dans session
	$gotoMsg=$_SERVER['QUERY_STRING'];

}
// stats
if(isset($_POST['connexion']))
{

	extract($_POST);
	$err=login($pdoUser, $pdoBt, $pdoSav);


	$action="user authentification";
	$page=basename(__file__);
	addRecord($pdoStat,$page,$action, $err[0]);

	if($err[0]=="user authentifié")
	{
		// echo $pwd;
		$dateMajPwd=getDateMajNohash($pdoUser);
		if($dateMajPwd['date_maj_nohash']==null)
		{
			updateNoHash($pdoUser);
		}
		header('Location:'. ROOT_PATH. '/public/home/home.php');
	}
}






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
		<div class="row justify-content-center">
			<div class="col-auto">
				<?=include('xmas.php')?>
			</div>
			<div class="col-auto">
   				 <h2 class="happy">Toute l'équipe de <span class="basic-font">BTLec </span><br>et la direction,<br> vous souhaite de merveilleuses <br>fêtes de fin d'année</h2>
			</div>

		</div>

				<?php
				if(!empty($err)){
					foreach ($err as $errStrg)
					{
						echo "<p class='w3-red'>" . $errStrg ."</p>";
					}
				}
				?>
				<!-- flashs info -->
				<div class="row infos mt-5">
					<div class="col">
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

	<div class="modal" id="connexion" data-backdrop=”false” >
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
// $("#connexion").modal({backdrop: false});







 var COUNT = 300;
  var masthead = document.querySelector('.sky');
  var canvas = document.createElement('canvas');
  var ctx = canvas.getContext('2d');
  var width = masthead.clientWidth;
  var height = masthead.clientHeight;
  var i = 0;
  var active = false;

  function onResize() {
    width = masthead.clientWidth;
    height = masthead.clientHeight;
    canvas.width = width;
    canvas.height = height;
    ctx.fillStyle = '#FFF';

    var wasActive = active;
    active = width > 600;

    if (!wasActive && active)
      requestAnimFrame(update);
  }

  var Snowflake = function () {
    this.x = 0;
    this.y = 0;
    this.vy = 0;
    this.vx = 0;
    this.r = 0;

    this.reset();
  }

  Snowflake.prototype.reset = function() {
    this.x = Math.random() * width;
    this.y = Math.random() * -height;
    this.vy = 1 + Math.random() * 3;
    this.vx = 0.5 - Math.random();
    this.r = 1 + Math.random() * 2;
    this.o = 0.5 + Math.random() * 0.5;
  }

  canvas.style.position = 'absolute';
  canvas.style.left = canvas.style.top = '0';

  var snowflakes = [], snowflake;
  for (i = 0; i < COUNT; i++) {
    snowflake = new Snowflake();
    snowflake.reset();
    snowflakes.push(snowflake);
  }

  function update() {

    ctx.clearRect(0, 0, width, height);

    if (!active)
      return;

    for (i = 0; i < COUNT; i++) {
      snowflake = snowflakes[i];
      snowflake.y += snowflake.vy;
      snowflake.x += snowflake.vx;

      ctx.globalAlpha = snowflake.o;
      ctx.beginPath();
      ctx.arc(snowflake.x, snowflake.y, snowflake.r, 0, Math.PI * 2, false);
      ctx.closePath();
      ctx.fill();

      if (snowflake.y > height) {
        snowflake.reset();
      }
    }

    requestAnimFrame(update);
  }

  // shim layer with setTimeout fallback
  window.requestAnimFrame = (function(){
    return  window.requestAnimationFrame       ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame    ||
            function( callback ){
              window.setTimeout(callback, 1000 / 60);
            };
  })();

  onResize();
  window.addEventListener('resize', onResize, false);

  masthead.appendChild(canvas);









	});



</script>


</body>
</html>