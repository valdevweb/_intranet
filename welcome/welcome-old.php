<?php
include('../config/config.inc.php');
include('../config/db-connect.php');

define('JOUR',array('dim','lun','mar','mer','jeu','vend','sam'));
define('DAYS', array( 'sun' , 'mon' , 'tue' , 'wed' , 'thu' , 'fri' , 'sat'));



/* ------------------------------------------------------------------
FONCTIONS RECUP CHIFFRE
---------------------------------------------------------------------*/

include 'welcome-fn.php';

// pour faire des simulation de date initialiser forceDay
// $forceDay=new DateTimeImmutable("2020/12/01");
include 'welcome-cal.php';

// tous les calcul de date partent de ces 2 variables
$jMoinsUn=$dayToDisplay;
$jMoinsUnLastYear=$dayToDisplayPrev;




	// echo "<pre>";
	// // print_r($dayToDisplay);
	// // print_r($jMoinsUn);
	// print_r($jMoinsUnLastYear);
	// echo '</pre>';



//variable qui seront videq si $jMoinsUnLastYear vide
$jMoinsUnLastYearAll="";
$moisEnCoursLastYearAll="";
$premierJourMoisLastYear="";



$annee=$jMoinsUn->format('Y');
$anneeLastYear=$annee -1;
$mois=$jMoinsUn->format('n');


$premierJourMois=clone $jMoinsUn;
$premierJourMois=$premierJourMois->modify('first day of this month');



if($jMoinsUnLastYear!=""){
	$premierJourMoisLastYear=clone $jMoinsUnLastYear;
	$premierJourMoisLastYear=$premierJourMoisLastYear->modify('first day of this month');
	$jMoinsUnLastYearAll=caJour($pdoQlik, $jMoinsUnLastYear);
	$moisEnCoursLastYearAll=sommeCaJour($pdoQlik,$premierJourMoisLastYear,$jMoinsUnLastYear);
}
if($jMoinsUnLastYearAll!=""){
	$jMoinsUnLastYearCa=$jMoinsUnLastYearAll['somme'];
	$jMoinsUnLastYearPalettes=$jMoinsUnLastYearAll['palettes'];
	$jMoinsUnLastYearColis=$jMoinsUnLastYearAll['colis'];
}else{
	$jMoinsUnLastYearCa=0;
	$jMoinsUnLastYearPalettes=0;
	$jMoinsUnLastYearColis=0;
}
if($moisEnCoursLastYearAll!=""){
	$moisEnCoursLastYearCa=$moisEnCoursLastYearAll['somme'];
	$moisEnCoursLastYearPalette=$moisEnCoursLastYearAll['palettes'];
	$moisEnCoursLastYearColis=$moisEnCoursLastYearAll['colis'];

}else{
	$moisEnCoursLastYearCa=0;
	$moisEnCoursLastYearPalette=0;
	$moisEnCoursLastYearColis=0;
}




// récup données db
$jMoinsUnAll=caJour($pdoQlik, $jMoinsUn);
$moisEnCoursAll=sommeCaJour($pdoQlik,$premierJourMois,$jMoinsUn);
$moisFinAll=caMois($pdoQlik, $mois, $annee);

$moisFinLastYearAll=caMois($pdoQlik, $mois, $anneeLastYear);
$anneeEnCoursAll=caAnnee($pdoQlik,$mois, $annee);
$anneeEnCoursLastYearAll=caAnnee($pdoQlik,$mois, $anneeLastYear);
$anneeFinAll=caAnnee($pdoQlik,12, $annee);
$anneeFinLastYearAll=caAnnee($pdoQlik,12, $anneeLastYear);



//JOUR
$jMoinsUnCa=$jMoinsUnAll['somme'];
$jMoinsUnPalettes=$jMoinsUnAll['palettes'];
$jMoinsUnColis=$jMoinsUnAll['colis'];




$jMoinsUnDiff= $jMoinsUnCa-$jMoinsUnLastYearCa;
$jMoinsUnPourcent=pourcentage($jMoinsUnCa,$jMoinsUnLastYearCa,$jMoinsUnDiff);



$moisEnCoursCa=$moisEnCoursAll['somme'];
$moisEnCoursPalette=$moisEnCoursAll['palettes'];
$moisEnCoursColis=$moisEnCoursAll['colis'];



$moisEnCoursDiff=$moisEnCoursCa-$moisEnCoursLastYearCa;
$moisEnCoursPourcent=pourcentage($moisEnCoursCa,$moisEnCoursLastYearCa,$moisEnCoursDiff);
if(!empty($moisFinAll)){
	$moisFinCa=$moisFinAll['somme'];
	$moisFinColis=$moisFinAll['colis'];
	$moisFinPalettes=$moisFinAll['palettes'];
}else{
	$moisFinCa=$moisFinColis=$moisFinPalettes=0;
}

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
	$jMoinsUnPourcent=round($jMoinsUnPourcent,2) ;
	$icoDay="fa-caret-down";
	$dayColorClass="negatif";
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
?>

