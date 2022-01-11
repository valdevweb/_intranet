<?php
include('../config/config.inc.php');
require '../Class/Db.php';
// require_once '../../vendor/autoload.php';


$db=new Db();
$pdoQlik=$db->getPdo('qlik');
define('JOUR',array('dim','lun','mar','mer','jeu','vend','sam'));
define('DAYS', array( 'sun' , 'mon' , 'tue' , 'wed' , 'thu' , 'fri' , 'sat'));





function getLaVeille($today){
	$dayToDisplay=clone $today;

	if($today->format("N")==1){
		$warning[]="1- on affiche les chiffres du vendredi donc - 3 jours<br>";

		$dayToDisplay=$dayToDisplay->modify('-3 day');
	}else{

		$dayToDisplay=$dayToDisplay->modify('-1 day');
	}
	return $dayToDisplay;
}


function makeDateArray($dayToDisplay){
	$dayToDisplayStr=$dayToDisplay->format('Y-m-d');
	$year=$dayToDisplay->format('Y');
	$lastyear=$year-1;
	$month=$dayToDisplay->format('m');
	$veilleLastyear=date("Y-m-d", strtotime($dayToDisplayStr.'- 364 days'));
	// comme on récupère la date du même jour de la semaine pour l'année dernière, on peut se retrouver avec un date qui nous fait changer de mois
	// exemple si dayToDisplay est mardi 30 nov 2021, le même jour de l'année dernier est mardi 1er décembre
	// dans ces cas là, on ne veut pas afficher le chiffre j-1 de l'année précédente donc on le vide
	//  pour les calculs de mois, on veut rester sur le mois de dayToDisplay donc on récupère le dernier jour du mois en cours de l'année dernière
	if(date('m', strtotime($dayToDisplayStr))!=date('m', strtotime($veilleLastyear))){
		$lastYear=$dayToDisplay->format('Y')-1;
		$month=$dayToDisplay->format('m');
		// peut importe le jour
		$monthLastYear = $lastYear."-".$month."-01";
		$lastdayOfMonthLastyear=date("Y-m-t", strtotime($monthLastYear));

		// $moisLastyear=date("d-m-Y D", strtotime($veilleLastyear.'- 1 days'));
		$dateAr=['veille'=>$dayToDisplay->format('Y-m-d'), "month"=>$month,"year"=>$year, "lastyear"=>$lastyear,"veille_lastyear"=>$veilleLastyear, "veille_lastyear_display"=>"", "fin_mois_lastyear" =>$lastdayOfMonthLastyear];

	}else{
		$dateAr=['veille'=>$dayToDisplay->format('Y-m-d'), "month"=>$month,"year"=>$year, "lastyear"=>$lastyear, "veille_lastyear"=>$veilleLastyear, "veille_lastyear_display"=>$veilleLastyear, "fin_mois_lastyear" =>$veilleLastyear];

	}
	return $dateAr;

}

$today=new DateTime();
// $today=new DateTime("2021-11-30");
$dayToDisplay=getLaVeille($today);

$dateAr=makeDateArray($dayToDisplay);



include 'welcome-fn-new.php';
$jMoinsUnAll=caJour($pdoQlik, $dateAr['veille']);
if($dateAr['veille_lastyear_display']!=""){
	$jMoinsUnLastYearAll=caJour($pdoQlik, $dateAr['veille_lastyear']);
}else{
	$jMoinsUnLastYearAll=['somme'=>"", 'colis'=>'', 'palettes'=>''];
}
$premierJourMois=$dateAr['year'].'-'.$dateAr['month'].'-01';
$premierJourMoisLastYear=$dateAr['lastyear'].'-'.$dateAr['month'].'-01';
$moisEnCoursAll=sommeCaJour($pdoQlik,$premierJourMois,$dateAr['veille']);
$moisEnCoursLastYearAll=sommeCaJour($pdoQlik,$premierJourMoisLastYear,$dateAr['fin_mois_lastyear']);
$moisFinAll=caMois($pdoQlik, $dateAr['month'], $dateAr['year']);
$moisFinLastYearAll=caMois($pdoQlik, $dateAr['month'], $dateAr['lastyear']);
// Alex condition afin d'éviter message d'erreur bool, déclaration à 0 pour le calcul
if(empty($moisFinLastYearAll)){
	$moisFinLastYearAll=['somme'=>"0", 'colis'=>'0', 'palettes'=>'0'];
}
//
$anneeEnCoursAll=caAnnee($pdoQlik,$dateAr['month'], $dateAr['year']);
$anneeEnCoursLastYearAll=caAnnee($pdoQlik,$dateAr['month'], $dateAr['lastyear']);
$anneeFinAll=caAnnee($pdoQlik,12, $dateAr['year']);
$anneeFinLastYearAll=caAnnee($pdoQlik,12, $dateAr['lastyear']);


if($dateAr['veille_lastyear_display']!=""){
	$jMoinsUnDiff= $jMoinsUnAll['somme']-$jMoinsUnLastYearAll['somme'];
	$jMoinsUnPourcent=pourcentage($jMoinsUnAll['somme'],$jMoinsUnLastYearAll['somme'],$jMoinsUnDiff);
}else{
	$jMoinsUnDiff=$jMoinsUnPourcent="";
}



$jMoinsUnCa=$jMoinsUnAll['somme'];
$jMoinsUnPalettes=$jMoinsUnAll['palettes'];
$jMoinsUnColis=$jMoinsUnAll['colis'];
$jMoinsUnLastYearCa=$jMoinsUnLastYearAll['somme'];
$jMoinsUnLastYearPalettes=$jMoinsUnLastYearAll['palettes'];
$jMoinsUnLastYearColis=$jMoinsUnLastYearAll['colis'];

$moisEnCoursLastYearCa=$moisEnCoursLastYearAll['somme'];
$moisEnCoursLastYearPalette=$moisEnCoursLastYearAll['palettes'];
$moisEnCoursLastYearColis=$moisEnCoursLastYearAll['colis'];


$moisEnCoursCa=$moisEnCoursAll['somme'];
$moisEnCoursPalette=$moisEnCoursAll['palettes'];
$moisEnCoursColis=$moisEnCoursAll['colis'];
$moisEnCoursDiff=$moisEnCoursCa-$moisEnCoursLastYearCa;
$moisEnCoursPourcent=pourcentage($moisEnCoursCa,$moisEnCoursLastYearCa,$moisEnCoursDiff);

$moisFinCa=$moisFinAll['somme'];
$moisFinColis=$moisFinAll['colis'];
$moisFinPalettes=$moisFinAll['palettes'];
$moisFinLastYearCa=$moisFinLastYearAll['somme'];
$moisFinLastYearColis=$moisFinLastYearAll['colis'];
$moisFinLastYearPalettes=$moisFinLastYearAll['palettes'];
$moisFinDiff=$moisFinCa-$moisFinLastYearCa;
$moisFinPourcent=pourcentage($moisFinCa,$moisFinLastYearCa,$moisFinDiff);
$anneeEnCoursCa=$anneeEnCoursAll['somme'];
$anneeEnCoursPalettes=$anneeEnCoursAll['palettes'];
$anneeEnCoursColis=$anneeEnCoursAll['colis'];
$anneeEnCoursLastYearCa=$anneeEnCoursLastYearAll['somme'];
$anneeEnCoursLastYearPalettes=$anneeEnCoursLastYearAll['palettes'];
$anneeEnCoursLastYearColis=$anneeEnCoursLastYearAll['colis'];
$anneeEnCoursDiff=$anneeEnCoursCa-$anneeEnCoursLastYearCa;
$anneeEnCoursPourcent=pourcentage($anneeEnCoursCa,$anneeEnCoursLastYearCa,$anneeEnCoursDiff);
$anneeFinCa=$anneeFinAll['somme'];
$anneeFinPalettes=$anneeFinAll['palettes'];
$anneeFinColis=$anneeFinAll['colis'];

$anneeFinLastYearCa=$anneeFinLastYearAll['somme'];
$anneeFinLastYearPalettes=$anneeFinLastYearAll['palettes'];
$anneeFinLastYearColis=$anneeFinLastYearAll['colis'];
$anneeFinDiff=$anneeFinCa-$anneeFinLastYearCa;
$anneeFinPourcent=pourcentage($anneeFinCa,$anneeFinLastYearCa,$anneeFinDiff);


if($jMoinsUnPourcent>=0){
	$jMoinsUnPourcent='+'.round($jMoinsUnPourcent,2);
	$icoDay="fa-caret-up";
	$dayColorClass="positif";
}else{
				// pas besoin d'ajouter le -, il y est déjà
	if($jMoinsUnPourcent!=""){
		$jMoinsUnPourcent=round($jMoinsUnPourcent,2) ;
		$icoDay="fa-caret-down";
		$dayColorClass="negatif";

	}

}

if($moisEnCoursPourcent>=0){
	$moisEnCoursPourcent='+'.round($moisEnCoursPourcent,2);
	$icoMonth="fa-caret-up";
	$monthColorClass="positif";
}else{
	$moisEnCoursPourcent=round($moisEnCoursPourcent,2);
	$icoMonth="fa-caret-down";
	$monthColorClass="negatif";
}

if($moisFinPourcent>=0){
	$moisFinPourcent='+'.round($moisFinPourcent,2);
	$endColorClass="positif";
	$icoEndMonth="fa-caret-up";
	$endMonthText="En avance de :";
}else{

	$moisFinPourcent=round($moisFinPourcent,2);
	$endColorClass="negatif";
	$icoEndMonth="fa-caret-down";
	$endMonthText= "Reste à faire :";
}

if($anneeEnCoursPourcent>=0){

	$anneeEnCoursPourcent='+'.round($anneeEnCoursPourcent,2);
	$icoYear="fa-caret-up";
	$yearColorClass="positif";
}else{
	// pas besoin d'ajouter le -, il y est déjà

	$anneeEnCoursPourcent=round($anneeEnCoursPourcent,2);
	$icoYear="fa-caret-down";
	$yearColorClass="negatif";
}

if($anneeFinPourcent>=0){
	$anneeFinPourcent='+'.round($anneeFinPourcent,2) ;
	$icoEndYear="fa-caret-up";
	$yearEndColorClass="positif";
	$yearEndText="En avance de :";
}else{
	// pas besoin d'ajouter le -, il y est déjà
	$anneeFinPourcent=round($anneeFinPourcent,2);
	$icoEndYear="fa-caret-down";
	$yearEndColorClass="negatif";
	$yearEndText="Reste à faire :";
}

$valoStock=valoStock($pdoQlik);
include 'welcome-body.php';
