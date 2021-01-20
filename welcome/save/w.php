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
$twenty=['2020-01-01', '2020-01-02', '2020-01-03', '2020-01-04', '2020-01-05', '2020-01-06', '2020-01-07', '2020-01-08', '2020-01-09', '2020-01-10', '2020-01-11', '2020-01-12', '2020-01-13', '2020-01-14', '2020-01-15', '2020-01-16', '2020-01-17', '2020-01-18', '2020-01-19', '2020-01-20', '2020-01-21', '2020-01-22', '2020-01-23', '2020-01-24', '2020-01-25', '2020-01-26', '2020-01-27', '2020-01-28', '2020-01-29', '2020-01-30', '2020-01-31', '2020-02-01', '2020-02-02', '2020-02-03', '2020-02-04', '2020-02-05', '2020-02-06', '2020-02-07', '2020-02-08', '2020-02-09', '2020-02-10', '2020-02-11', '2020-02-12', '2020-02-13', '2020-02-14', '2020-02-15', '2020-02-16', '2020-02-17', '2020-02-18', '2020-02-19', '2020-02-20', '2020-02-21', '2020-02-22', '2020-02-23', '2020-02-24', '2020-02-25', '2020-02-26', '2020-02-27', '2020-02-28', '2020-02-29', '2020-03-01', '2020-03-02', '2020-03-03', '2020-03-04', '2020-03-05', '2020-03-06', '2020-03-07', '2020-03-08', '2020-03-09', '2020-03-10', '2020-03-11', '2020-03-12', '2020-03-13', '2020-03-14', '2020-03-15', '2020-03-16', '2020-03-17', '2020-03-18', '2020-03-19', '2020-03-20', '2020-03-21', '2020-03-22', '2020-03-23', '2020-03-24', '2020-03-25', '2020-03-26', '2020-03-27', '2020-03-28', '2020-03-29', '2020-03-30', '2020-03-31', '2020-04-01', '2020-04-02', '2020-04-03', '2020-04-04', '2020-04-05', '2020-04-06', '2020-04-07', '2020-04-08', '2020-04-09', '2020-04-10', '2020-04-11', '2020-04-12', '2020-04-13', '2020-04-14', '2020-04-15', '2020-04-16', '2020-04-17', '2020-04-18', '2020-04-19', '2020-04-20', '2020-04-21', '2020-04-22', '2020-04-23', '2020-04-24', '2020-04-25', '2020-04-26', '2020-04-27', '2020-04-28', '2020-04-29', '2020-04-30', '2020-05-01', '2020-05-02', '2020-05-03', '2020-05-04', '2020-05-05', '2020-05-06', '2020-05-07', '2020-05-08', '2020-05-09', '2020-05-10', '2020-05-11', '2020-05-12', '2020-05-13', '2020-05-14', '2020-05-15', '2020-05-16', '2020-05-17', '2020-05-18', '2020-05-19', '2020-05-20', '2020-05-21', '2020-05-22', '2020-05-23', '2020-05-24', '2020-05-25', '2020-05-26', '2020-05-27', '2020-05-28', '2020-05-29', '2020-05-30', '2020-05-31', '2020-06-01', '2020-06-02', '2020-06-03', '2020-06-04', '2020-06-05', '2020-06-06', '2020-06-07', '2020-06-08', '2020-06-09', '2020-06-10', '2020-06-11', '2020-06-12', '2020-06-13', '2020-06-14', '2020-06-15', '2020-06-16', '2020-06-17', '2020-06-18', '2020-06-19', '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20', '2020-07-21', '2020-07-22', '2020-07-23', '2020-07-24', '2020-07-25', '2020-07-26', '2020-07-27', '2020-07-28', '2020-07-29', '2020-07-30', '2020-07-31', '2020-08-01', '2020-08-02', '2020-08-03', '2020-08-04', '2020-08-05', '2020-08-06', '2020-08-07', '2020-08-08', '2020-08-09', '2020-08-10', '2020-08-11', '2020-08-12', '2020-08-13', '2020-08-14', '2020-08-15', '2020-08-16', '2020-08-17', '2020-08-18', '2020-08-19', '2020-08-20', '2020-08-21', '2020-08-22', '2020-08-23', '2020-08-24', '2020-08-25', '2020-08-26', '2020-08-27', '2020-08-28', '2020-08-29', '2020-08-30', '2020-08-31', '2020-09-01', '2020-09-02', '2020-09-03', '2020-09-04', '2020-09-05', '2020-09-06', '2020-09-07', '2020-09-08', '2020-09-09', '2020-09-10', '2020-09-11', '2020-09-12', '2020-09-13', '2020-09-14', '2020-09-15', '2020-09-16', '2020-09-17', '2020-09-18', '2020-09-19', '2020-09-20', '2020-09-21', '2020-09-22', '2020-09-23', '2020-09-24', '2020-09-25', '2020-09-26', '2020-09-27', '2020-09-28', '2020-09-29', '2020-09-30', '2020-10-01', '2020-10-02', '2020-10-03', '2020-10-04', '2020-10-05', '2020-10-06', '2020-10-07', '2020-10-08', '2020-10-09', '2020-10-10', '2020-10-11', '2020-10-12', '2020-10-13', '2020-10-14', '2020-10-15', '2020-10-16', '2020-10-17', '2020-10-18', '2020-10-19', '2020-10-20', '2020-10-21', '2020-10-22', '2020-10-23', '2020-10-24', '2020-10-25', '2020-10-26', '2020-10-27', '2020-10-28', '2020-10-29', '2020-10-30', '2020-10-31', '2020-11-01', '2020-11-02', '2020-11-03', '2020-11-04', '2020-11-05', '2020-11-06', '2020-11-07', '2020-11-08', '2020-11-09', '2020-11-10', '2020-11-11', '2020-11-12', '2020-11-13', '2020-11-14', '2020-11-15', '2020-11-16', '2020-11-17', '2020-11-18', '2020-11-19', '2020-11-20', '2020-11-21', '2020-11-22', '2020-11-23', '2020-11-24', '2020-11-25', '2020-11-26', '2020-11-27', '2020-11-28', '2020-11-29', '2020-11-30', '2020-12-01', '2020-12-02', '2020-12-03', '2020-12-04', '2020-12-05', '2020-12-06', '2020-12-07', '2020-12-08', '2020-12-09', '2020-12-10', '2020-12-11', '2020-12-12', '2020-12-13', '2020-12-14', '2020-12-15', '2020-12-16', '2020-12-17', '2020-12-18', '2020-12-19', '2020-12-20', '2020-12-21', '2020-12-22', '2020-12-23', '2020-12-24', '2020-12-25', '2020-12-26', '2020-12-27', '2020-12-28', '2020-12-29', '2020-12-30', '2020-12-31'];

$nineteen=['2019-01-02', '2019-01-03', '2019-01-04', '2019-01-05', '2019-01-06', '2019-01-07', '2019-01-08', '2019-01-09', '2019-01-10', '2019-01-11', '2019-01-12', '2019-01-13', '2019-01-14', '2019-01-15', '2019-01-16', '2019-01-17', '2019-01-18', '2019-01-19', '2019-01-20', '2019-01-21', '2019-01-22', '2019-01-23', '2019-01-24', '2019-01-25', '2019-01-26', '2019-01-27', '2019-01-28', '2019-01-29', '2019-01-30', '2019-01-31', 'NULL', '2019-02-02', '2019-02-03', '2019-02-04', '2019-02-05', '2019-02-06', '2019-02-07', '2019-02-08', '2019-02-09', '2019-02-10', '2019-02-11', '2019-02-12', '2019-02-13', '2019-02-14', '2019-02-15', '2019-02-16', '2019-02-17', '2019-02-18', '2019-02-19', '2019-02-20', '2019-02-21', '2019-02-22', '2019-02-23', '2019-02-24', '2019-02-25', '2019-02-26', '2019-02-27', '2019-02-28', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-03-01', '2019-03-02', '2019-03-03', '2019-03-04', '2019-03-05', '2019-03-06', '2019-03-07', '2019-03-08', '2019-03-09', '2019-03-10', '2019-03-11', '2019-03-12', '2019-03-13', '2019-03-14', '2019-03-15', '2019-03-16', '2019-03-17', '2019-03-18', '2019-03-19', '2019-03-20', '2019-03-21', '2019-03-22', '2019-03-23', '2019-03-24', '2019-03-25', '2019-03-26', '2019-04-03', '2019-04-04', '2019-04-05', '2019-04-06', '2019-04-07', '2019-04-08', '2019-04-09', '2019-04-10', '2019-04-11', '2019-04-12', '2019-04-13', '2019-04-14', '2019-04-15', '2019-04-16', '2019-04-17', '2019-04-18', '2019-04-19', '2019-04-20', '2019-04-21', '2019-04-22', '2019-04-23', '2019-04-24', '2019-04-25', '2019-04-26', '2019-04-27', '2019-04-28', '2019-04-29', '2019-04-30', 'NULL', 'NULL', '2019-05-03', '2019-05-04', '2019-05-05', '2019-05-06', '2019-05-07', '2019-05-08', '2019-05-09', '2019-05-10', '2019-05-11', '2019-05-12', '2019-05-13', '2019-05-14', '2019-05-15', '2019-05-16', '2019-05-17', '2019-05-18', '2019-05-19', '2019-05-20', '2019-05-21', '2019-05-22', '2019-05-23', '2019-05-24', '2019-05-25', '2019-05-26', '2019-05-27', '2019-05-28', '2019-05-29', '2019-05-30', '2019-05-31', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-06-01', '2019-06-02', '2019-06-03', '2019-06-04', '2019-06-05', '2019-06-06', '2019-06-07', '2019-06-08', '2019-06-09', '2019-06-10', '2019-06-11', '2019-06-12', '2019-06-13', '2019-06-14', '2019-06-15', '2019-06-16', '2019-06-17', '2019-06-18', '2019-06-19', '2019-06-20', '2019-06-21', '2019-06-22', '2019-06-23', '2019-06-24', '2019-06-25', '2019-07-03', '2019-07-04', '2019-07-05', '2019-07-06', '2019-07-07', '2019-07-08', '2019-07-09', '2019-07-10', '2019-07-11', '2019-07-12', '2019-07-13', '2019-07-14', '2019-07-15', '2019-07-16', '2019-07-17', '2019-07-18', '2019-07-19', '2019-07-20', '2019-07-21', '2019-07-22', '2019-07-23', '2019-07-24', '2019-07-25', '2019-07-26', '2019-07-27', '2019-07-28', '2019-07-29', '2019-07-30', '2019-07-31', 'NULL', 'NULL', '2019-08-03', '2019-08-04', '2019-08-05', '2019-08-06', '2019-08-07', '2019-08-08', '2019-08-09', '2019-08-10', '2019-08-11', '2019-08-12', '2019-08-13', '2019-08-14', '2019-08-15', '2019-08-16', '2019-08-17', '2019-08-18', '2019-08-19', '2019-08-20', '2019-08-21', '2019-08-22', '2019-08-23', '2019-08-24', '2019-08-25', '2019-08-26', '2019-08-27', '2019-08-28', '2019-08-29', '2019-08-30', '2019-08-31', 'NULL', 'NULL', '2019-09-03', '2019-09-04', '2019-09-05', '2019-09-06', '2019-09-07', '2019-09-08', '2019-09-09', '2019-09-10', '2019-09-11', '2019-09-12', '2019-09-13', '2019-09-14', '2019-09-15', '2019-09-16', '2019-09-17', '2019-09-18', '2019-09-19', '2019-09-20', '2019-09-21', '2019-09-22', '2019-09-23', '2019-09-24', '2019-09-25', '2019-09-26', '2019-09-27', '2019-09-28', '2019-09-29', '2019-09-30', 'NULL', 'NULL', '2019-10-03', '2019-10-04', '2019-10-05', '2019-10-06', '2019-10-07', '2019-10-08', '2019-10-09', '2019-10-10', '2019-10-11', '2019-10-12', '2019-10-13', '2019-10-14', '2019-10-15', '2019-10-16', '2019-10-17', '2019-10-18', '2019-10-19', '2019-10-20', '2019-10-21', '2019-10-22', '2019-10-23', '2019-10-24', '2019-10-25', '2019-10-26', '2019-10-27', '2019-10-28', '2019-10-29', '2019-10-30', '2019-10-31', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-11-01', '2019-11-02', '2019-11-03', '2019-11-04', '2019-11-05', '2019-11-06', '2019-11-07', '2019-11-08', '2019-11-09', '2019-11-10', '2019-11-11', '2019-11-12', '2019-11-13', '2019-11-14', '2019-11-15', '2019-11-16', '2019-11-17', '2019-11-18', '2019-11-19', '2019-11-20', '2019-11-21', '2019-11-22', '2019-11-23', '2019-11-24', '2019-11-25', '2019-12-03', '2019-12-04', '2019-12-05', '2019-12-06', '2019-12-07', '2019-12-08', '2019-12-09', '2019-12-10', '2019-12-11', '2019-12-12', '2019-12-13', '2019-12-14', '2019-12-15', '2019-12-16', '2019-12-17', '2019-12-18', '2019-12-19', '2019-12-20', '2019-12-21', '2019-12-22', '2019-12-23', '2019-12-24', '2019-12-25', '2019-12-26', '2019-12-27', '2019-12-28', '2019-12-29', '2019-12-30', '2019-12-31', 'NULL', 'NULL'];


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



function getLastYearDayDavid($index, $nineteen){
	$prev=$nineteen[$index];
	if($prev=='NULL'){
		return false;
	}else{
		return new DateTime($prev);

	}
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
// AVANT modifi et utilisation tableau david
// meme jour de la semaine, l'année dernière
$prev=getLastYearDay($now);
if($now->format('n') !=$prev->format('n')){
	$prev=$prev->modify("-1 day");
}
$index=array_search($now->format('Y-m-d'),$twenty);
$prevDavid=getLastYearDayDavid($index,$nineteen);


$lastDayOfMonth=isLastDayOfMonth($now);


/*

1er encart avec chiffre du jour

 */


// chiffre d'affaire du jour (veille)
$dayNow=caJour($pdoQlik, $now);
// chiffre d'affaire même jour même semaine, l'année dernière
if(!$prevDavid){
	$dayPrev=0;
	$dayDiff=$dayNow['CAl'];

}else{
	$dayPrev=caJour($pdoQlik,$prevDavid);
	$dayDiff=$dayNow['CAl'] - $dayPrev['CAl'];
	$dayPrev=round($dayPrev['CAl']);
}



$dayNow=round($dayNow['CAl']);

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
$monthPrev=caSumJour($pdoQlik, $firstDay, $prevDavid);


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