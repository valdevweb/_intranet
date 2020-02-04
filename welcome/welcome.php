<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){

	define("VERSION",'_');
}
else{
	define("VERSION",'');
}

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}
$pdoQlik= connectToDb('qlik');


function caJour($pdoQlik,$date){
	$req=$pdoQlik->prepare("SELECT * FROM statscajour WHERE DateCA= :DateCA");
	$req->execute([
		':DateCA'			=>$date->format('Y-m-d')

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function caSumJour($pdoQlik,$start,$end){
	$req=$pdoQlik->prepare("SELECT sum(CAl) as somme FROM statscajour WHERE DateCA BETWEEN :start AND :end");
	$req->execute([
		':start'			=>$start->format('Y-m-d'),
		':end'				=>$end->format('Y-m-d')
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function caMois($pdoQlik,$month,$year){
	$req=$pdoQlik->prepare("SELECT * FROM statscamois WHERE AnneeCA= :AnneeCA AND MoisCA= :MoisCA");
	$req->execute([
		':AnneeCA'			=>$year,
		':MoisCA'			=>$month

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function caAnnee($pdoQlik,$lastMonth,$year){
	$req=$pdoQlik->prepare("SELECT sum(CAl) as somme FROM statscamois WHERE AnneeCA= :AnneeCA AND (MoisCA>= 1 AND MoisCA<=:MoisCA)");
	$req->execute([
		':AnneeCA'			=>$year,
		':MoisCA'			=>$lastMonth

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
// ca total année 2019
// 396 958 494.39


define('JOUR',array('dim','lun','mar','mer','jeu','vend','sam'));
define('DAYS', array( 'sun' , 'mon' , 'tue' , 'wed' , 'thu' , 'fri' , 'sat'));

function getYesterday(){
	$today=new DateTimeImmutable();
	//si on est lundi, on prend samedi
	if($today->format('w')==1){
		$now=$today->modify('- 3 day');
	}else{
		// $now=clone $today;
		$now=$today->modify('- 1 day');
	}
	return $now;
}

function getLastYearDay($now){
	// $nowNDay= clone $now;
	$nowNDay=$now->format('w');
// ww = num semaine
	$nowNWeek=$now->format('W');
	// $lastYear=clone $now;
	$lastYear=$now->modify('last year');
	// $last=new DateTime();
	// renvoie le 1er jour de la semaine en question
	$last=(new DateTimeImmutable())->setISODate($lastYear->format('Y'),$nowNWeek);
	// tel jour (chaine de caractère UK) de la semaine

	$last=$last->modify( DAYS[$nowNDay] .' this week');


	return $last;
}

function isLastDayOfMonth($now){
	// $now=new DateTime('2019-10-31');
	$lastDayOfMonth=$now->modify('last day of this month');

	// on verifie si la fin de mois tombe un dimanche, on prend le samedi
	if($lastDayOfMonth->format('w')==0){
		$lastDayOfMonth=$lastDayOfMonth->modify("- 1 day");
	}
	$diff=date_diff($now,$lastDayOfMonth);
	$interval=$diff->format('%a');
	if($interval==0){
		return true;
	}
	return false;
}

function isPositif($calcul){
	if($calcul < 0){
		return [-$calcul,false, "Reste à faire :"];
	}
	return [$calcul,true, "En avance de :"];
}


// dates
$today=new DateTimeImmutable();
// ca de la veille
$now=getYesterday();
// meme jour de la semaine, l'année dernière
$prev=getLastYearDay($now);


if($now->format('n') !=$prev->format('n')){
	$prev=$prev->modify("-1 day");

}
$lastDayOfMonth=isLastDayOfMonth($now);


/*

1er encart avec chiffre du jour

 */


// chiffre d'affaire du jour (veille)
$dayNow=caJour($pdoQlik, $now);
// chiffre d'affaire même jour même semaine, l'année dernière
$dayPrev=caJour($pdoQlik,$prev);
$dayDiff=$dayNow['CAl'] - $dayPrev['CAl'];

$dayNow=round($dayNow['CAl']);
$dayPrev=round($dayPrev['CAl']);

if($dayDiff!=0 && $dayPrev!=0){
	$dayPer=($dayDiff*100)/$dayPrev;
}
else{
	$dayPer=0;
}





// chiffre d'affaire du mois en cours jusqu'à aujourd'hui
// $monthNow=caSumJour($pdoQlik, $now->modify('first day of this month'), $now->modify('last day of this month'));
// chiffre d'affaire du même mois l'année dernière jusqu'au même mois, même jour, même semaine l'année dernière
$firstDay= clone $prev;
$firstDay=$firstDay->modify('first day of this month');
$monthPrev=caSumJour($pdoQlik, $firstDay, $prev);


// chiffre d'affaire total du mois en cours
$endMonthNow=caMois($pdoQlik, $now->format('n'), $now->format('Y'));

// chiffre d'affaire tatal de meêm mois l'année dernière
$endMonthPrev=caMois($pdoQlik,$prev->format('n'), $prev->format('Y'));


$endMonthNow=round($endMonthNow['CAl']);
$endMonthPrev=round($endMonthPrev['CAl']);
$monthNow=$endMonthNow;


//ca année en cours jusqu'à aujourd'hui => on peut prendre le ca du mois en coèurs pour l'année actuelle
$yearNow=caAnnee($pdoQlik,$now->format('n'), $now->format('Y'));

 // pour l'année précédente, il faut prendre la somme des ca mois jusqu'au mois précédent et ajouter le ca du même jour de l'année précédente (ca du mois en cours et non du mois fini)
 // qd 1er mois de l'année, on ne prend que le ca du mois en cours
 if(($prev->format('n'))=="1"){
	$yearPrev=$monthPrev['somme'];

 }else{
	$yearPrev=caAnnee($pdoQlik,$prev->modify('-1 month')->format('n'), $prev->format('Y'));
	$yearPrev=$yearPrev['somme']+$monthPrev['somme'];

 }

$yearEndNow=caAnnee($pdoQlik,12, $now->format('Y'));
$yearEndPrev=caAnnee($pdoQlik,12, $prev->format('Y'));

$yearDiff=$yearNow['somme']-$yearPrev;
$yearEndDiff=$yearEndNow['somme']-$yearEndPrev['somme'];

$monthDiff=$monthNow- $monthPrev['somme'];
$endMonthDiff=$endMonthNow - $endMonthPrev;
list($endMonthDiff,$finMoisOkko,$endMonthText)=isPositif($endMonthDiff);


$monthPrev=round($monthPrev['somme']);
$yearNow=round($yearNow['somme']);
$yearPrev=round($yearPrev);
$yearEndNow=round($yearEndNow['somme']);
$yearEndPrev=round($yearEndPrev['somme']);





list($yeaEndDiff,$finAnneeOkko,$yearEndText)=isPositif($yearEndDiff);




if($monthDiff!=0 && $monthPrev!=0){
	$monthPer=($monthDiff*100)/$monthPrev;
}else{
	$monthPer=0;
}

if($endMonthDiff!=0 && $endMonthPrev!=0){
	$endMonthPer=($endMonthDiff*100)/$endMonthPrev;
}else{
	$endMonthPer=0;
}

if($yearEndDiff!=0 && $yearEndPrev!=0){
	$yearEndPer=($yearEndDiff*100)/$yearEndPrev;
}else{
	$yearEndPer=0;
}
if($yearDiff!=0 && $yearPrev!=0){
	$yearPer=($yearDiff*100)/$yearPrev;

}else{
	$yearPer=0;
}



if($dayPer>=0){
	$dayPer='+'.round($dayPer,2) .'<span class="norm-text"> %</span>';
	$icoDay="fa-caret-up";
	$dayColorClass="positif";
}else{
	// pas besoin d'ajouter le -, il y est déjà
	$dayPer=round($dayPer,2) .'<span class="norm-text"> %</span>';
	$icoDay="fa-caret-down";
	$dayColorClass="negatif";
}


if($monthPer>=0){
	$monthPer='+'.round($monthPer,2) .'<span class="norm-text"> %</span>';
	$icoMonth="fa-caret-up";
	$monthColorClass="positif";
}else{
	$monthPer=round($monthPer,2) .'<span class="norm-text"> %</span>';
	$icoMonth="fa-caret-down";
	$monthColorClass="negatif";
}




if($yearPer>=0){
	$yearPer='+'.round($yearPer,2) .'<span class="norm-text"> %</span>';
	$icoYear="fa-caret-up";
	$yearColorClass="positif";
}else{
	// pas besoin d'ajouter le -, il y est déjà
	$yearPer=round($yearPer,2) .'<span class="norm-text"> %</span>';
	$icoYear="fa-caret-down";
	$yearColorClass="negatif";
}
if($yearEndDiff>=0){
	$yearEndDiff='+'.round($yearEndDiff,2) .'<span class="norm-text"> %</span>';
	$icoEndYear="fa-caret-up";
	$yearEndColorClass="positif";
}else{
	// pas besoin d'ajouter le -, il y est déjà
	$yearEndDiff=round($yearEndDiff,2) .'<span class="norm-text"> %</span>';
	$icoEndYear="fa-caret-down";
	$yearEndColorClass="negatif";
}


if(!$finMoisOkko){
	$endColorClass="negatif";
	$icoEndMonth="fa-caret-down";

}else{
	$endColorClass="positif";
	$icoEndMonth="fa-caret-up";


}


?>
<!DOCTYPE html>
<html lang="fr">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />
	<link rel="stylesheet" href="welcome.css">
	<link rel="stylesheet" href="http://172.30.92.53/_btlecest/public/css/font.css">

	<link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css">
	<link href="../vendor/fontawesome5/css/all.css" rel="stylesheet">
	<title>Bienvenue à BTLec</title>
</head>
<body>
	<div class="container-fluid">

		<header>
			<div class="row">
			</div>
		</header>


		<!-- 1 LIGNE COMPLETE **********************************************************************************************************************************************************-->
		<div class="row mb-1">
			<div class="col align-self-center text-center">
				<img class="" src="../public/img/logo_bt/bt300.jpg">
			</div>

			<!-- COLONNE GAUCHE LIGNE 1 -->
			<div class="col-6">
				<section class="jour">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-jour.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'w-day.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour orangish">
											<?php include 'w-varday.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<!-- COLONNE DROITE LIGNE 1 -->
			<div class="col"></div>
		</div>
		<!-- FIN 1 LIGNE COMPLETE ********************************************************************************************************************************************* -->
		<!-- 2 LIGNE COMPLETE **********************************************************************************************************************************************************-->
		<div class="row mb-1">
			<!-- COLONNE GAUCHE LIGNE 2 -->
			<div class="col-6">
				<section class="mois">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-encours.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'w-month.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col-6 bg-pourcentage-mois whitten">
											<?php include 'w-varmonth.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<!-- COLONNE DROITE LIGNE 2 -->
			<div class="col-6">
				<section class="end">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-findemois.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'w-monthend.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour darken">
											<?php include 'w-varmonthend.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- FIN 2 LIGNE COMPLETE ********************************************************************************************************************************************* -->

		<!-- 3 LIGNE COMPLETE **********************************************************************************************************************************************************-->
		<div class="row">
			<!-- COLONNE GAUCHE LIGNE 3 -->
			<div class="col-6">
				<section class="year">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-year.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'w-year.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour whitten">
											<?php include 'w-varyear.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<!-- COLONNE DROITE LIGNE 3 -->
			<div class="col-6">
				<section class="end-year">
					<div class="row bg">
						<div class="col">
							<div class="row">
								<!-- col jou -->
								<div class="tagging">
									<img src="tag-finannee.png" class="img-fluid">
								</div>

								<!-- col chiffres et pourcentage-->

								<div class="col p-3">
									<div class="row mr-1">
										<div class="col-6">
											<?php include 'w-yearend.php' ?>

										</div>
										<!-- col pourcentage -->
										<div class="col bg-pourcentage-jour darken">
											<?php include 'w-varyearend.php' ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- FIN 3 LIGNE COMPLETE ********************************************************************************************************************************************* -->



		<div class="row">
			<div class="col">
				<p class="text-center mt-5"><a href="http://172.30.101.8:8089/open/homepage" target="_blank">Badgeuse - Bodet</a></p>
				<p class="text-right">Dernière mise à jour : <?=(new DateTime())->format('H').'h'.(new DateTime())->format('i')?></p>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		var today = new Date().getTime();
		var start=new Date();
		start=start.setHours(4,0,0,0);
		var end=new Date();
		end=end.setHours(12,30,0,0);

	// start=start.getHours();
	console.log(today);
	console.log(start);
	console.log(end);

	if(today>start && today < end){
		// on reload tout les 1/4 h entre 4h et 9h30
		setTimeout(function(){
			window.location.reload(1);
		}, 900000);
	}else{
		console.log("no reload")

	}

</script>



</body>
</html>