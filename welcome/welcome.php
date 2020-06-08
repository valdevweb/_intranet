<?php
include('../config/config.inc.php');

define('JOUR',array('dim','lun','mar','mer','jeu','vend','sam'));
define('DAYS', array( 'sun' , 'mon' , 'tue' , 'wed' , 'thu' , 'fri' , 'sat'));
$twentyTwo=[];
$twentyOne=[];
$twenty=['2020-01-01', '2020-01-02', '2020-01-03', '2020-01-04', '2020-01-05', '2020-01-06', '2020-01-07', '2020-01-08', '2020-01-09', '2020-01-10', '2020-01-11', '2020-01-12', '2020-01-13', '2020-01-14', '2020-01-15', '2020-01-16', '2020-01-17', '2020-01-18', '2020-01-19', '2020-01-20', '2020-01-21', '2020-01-22', '2020-01-23', '2020-01-24', '2020-01-25', '2020-01-26', '2020-01-27', '2020-01-28', '2020-01-29', '2020-01-30', '2020-01-31', '2020-02-01', '2020-02-02', '2020-02-03', '2020-02-04', '2020-02-05', '2020-02-06', '2020-02-07', '2020-02-08', '2020-02-09', '2020-02-10', '2020-02-11', '2020-02-12', '2020-02-13', '2020-02-14', '2020-02-15', '2020-02-16', '2020-02-17', '2020-02-18', '2020-02-19', '2020-02-20', '2020-02-21', '2020-02-22', '2020-02-23', '2020-02-24', '2020-02-25', '2020-02-26', '2020-02-27', '2020-02-28', '2020-02-29', '2020-03-01', '2020-03-02', '2020-03-03', '2020-03-04', '2020-03-05', '2020-03-06', '2020-03-07', '2020-03-08', '2020-03-09', '2020-03-10', '2020-03-11', '2020-03-12', '2020-03-13', '2020-03-14', '2020-03-15', '2020-03-16', '2020-03-17', '2020-03-18', '2020-03-19', '2020-03-20', '2020-03-21', '2020-03-22', '2020-03-23', '2020-03-24', '2020-03-25', '2020-03-26', '2020-03-27', '2020-03-28', '2020-03-29', '2020-03-30', '2020-03-31', '2020-04-01', '2020-04-02', '2020-04-03', '2020-04-04', '2020-04-05', '2020-04-06', '2020-04-07', '2020-04-08', '2020-04-09', '2020-04-10', '2020-04-11', '2020-04-12', '2020-04-13', '2020-04-14', '2020-04-15', '2020-04-16', '2020-04-17', '2020-04-18', '2020-04-19', '2020-04-20', '2020-04-21', '2020-04-22', '2020-04-23', '2020-04-24', '2020-04-25', '2020-04-26', '2020-04-27', '2020-04-28', '2020-04-29', '2020-04-30', '2020-05-01', '2020-05-02', '2020-05-03', '2020-05-04', '2020-05-05', '2020-05-06', '2020-05-07', '2020-05-08', '2020-05-09', '2020-05-10', '2020-05-11', '2020-05-12', '2020-05-13', '2020-05-14', '2020-05-15', '2020-05-16', '2020-05-17', '2020-05-18', '2020-05-19', '2020-05-20', '2020-05-21', '2020-05-22', '2020-05-23', '2020-05-24', '2020-05-25', '2020-05-26', '2020-05-27', '2020-05-28', '2020-05-29', '2020-05-30', '2020-05-31', '2020-06-01', '2020-06-02', '2020-06-03', '2020-06-04', '2020-06-05', '2020-06-06', '2020-06-07', '2020-06-08', '2020-06-09', '2020-06-10', '2020-06-11', '2020-06-12', '2020-06-13', '2020-06-14', '2020-06-15', '2020-06-16', '2020-06-17', '2020-06-18', '2020-06-19', '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20', '2020-07-21', '2020-07-22', '2020-07-23', '2020-07-24', '2020-07-25', '2020-07-26', '2020-07-27', '2020-07-28', '2020-07-29', '2020-07-30', '2020-07-31', '2020-08-01', '2020-08-02', '2020-08-03', '2020-08-04', '2020-08-05', '2020-08-06', '2020-08-07', '2020-08-08', '2020-08-09', '2020-08-10', '2020-08-11', '2020-08-12', '2020-08-13', '2020-08-14', '2020-08-15', '2020-08-16', '2020-08-17', '2020-08-18', '2020-08-19', '2020-08-20', '2020-08-21', '2020-08-22', '2020-08-23', '2020-08-24', '2020-08-25', '2020-08-26', '2020-08-27', '2020-08-28', '2020-08-29', '2020-08-30', '2020-08-31', '2020-09-01', '2020-09-02', '2020-09-03', '2020-09-04', '2020-09-05', '2020-09-06', '2020-09-07', '2020-09-08', '2020-09-09', '2020-09-10', '2020-09-11', '2020-09-12', '2020-09-13', '2020-09-14', '2020-09-15', '2020-09-16', '2020-09-17', '2020-09-18', '2020-09-19', '2020-09-20', '2020-09-21', '2020-09-22', '2020-09-23', '2020-09-24', '2020-09-25', '2020-09-26', '2020-09-27', '2020-09-28', '2020-09-29', '2020-09-30', '2020-10-01', '2020-10-02', '2020-10-03', '2020-10-04', '2020-10-05', '2020-10-06', '2020-10-07', '2020-10-08', '2020-10-09', '2020-10-10', '2020-10-11', '2020-10-12', '2020-10-13', '2020-10-14', '2020-10-15', '2020-10-16', '2020-10-17', '2020-10-18', '2020-10-19', '2020-10-20', '2020-10-21', '2020-10-22', '2020-10-23', '2020-10-24', '2020-10-25', '2020-10-26', '2020-10-27', '2020-10-28', '2020-10-29', '2020-10-30', '2020-10-31', '2020-11-01', '2020-11-02', '2020-11-03', '2020-11-04', '2020-11-05', '2020-11-06', '2020-11-07', '2020-11-08', '2020-11-09', '2020-11-10', '2020-11-11', '2020-11-12', '2020-11-13', '2020-11-14', '2020-11-15', '2020-11-16', '2020-11-17', '2020-11-18', '2020-11-19', '2020-11-20', '2020-11-21', '2020-11-22', '2020-11-23', '2020-11-24', '2020-11-25', '2020-11-26', '2020-11-27', '2020-11-28', '2020-11-29', '2020-11-30', '2020-12-01', '2020-12-02', '2020-12-03', '2020-12-04', '2020-12-05', '2020-12-06', '2020-12-07', '2020-12-08', '2020-12-09', '2020-12-10', '2020-12-11', '2020-12-12', '2020-12-13', '2020-12-14', '2020-12-15', '2020-12-16', '2020-12-17', '2020-12-18', '2020-12-19', '2020-12-20', '2020-12-21', '2020-12-22', '2020-12-23', '2020-12-24', '2020-12-25', '2020-12-26', '2020-12-27', '2020-12-28', '2020-12-29', '2020-12-30', '2020-12-31'];

$nineteen=['2019-01-02', '2019-01-03', '2019-01-04', '2019-01-05', '2019-01-06', '2019-01-07', '2019-01-08', '2019-01-09', '2019-01-10', '2019-01-11', '2019-01-12', '2019-01-13', '2019-01-14', '2019-01-15', '2019-01-16', '2019-01-17', '2019-01-18', '2019-01-19', '2019-01-20', '2019-01-21', '2019-01-22', '2019-01-23', '2019-01-24', '2019-01-25', '2019-01-26', '2019-01-27', '2019-01-28', '2019-01-29', '2019-01-30', '2019-01-31', 'NULL', '2019-02-02', '2019-02-03', '2019-02-04', '2019-02-05', '2019-02-06', '2019-02-07', '2019-02-08', '2019-02-09', '2019-02-10', '2019-02-11', '2019-02-12', '2019-02-13', '2019-02-14', '2019-02-15', '2019-02-16', '2019-02-17', '2019-02-18', '2019-02-19', '2019-02-20', '2019-02-21', '2019-02-22', '2019-02-23', '2019-02-24', '2019-02-25', '2019-02-26', '2019-02-27', '2019-02-28', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-03-01', '2019-03-02', '2019-03-03', '2019-03-04', '2019-03-05', '2019-03-06', '2019-03-07', '2019-03-08', '2019-03-09', '2019-03-10', '2019-03-11', '2019-03-12', '2019-03-13', '2019-03-14', '2019-03-15', '2019-03-16', '2019-03-17', '2019-03-18', '2019-03-19', '2019-03-20', '2019-03-21', '2019-03-22', '2019-03-23', '2019-03-24', '2019-03-25', '2019-03-26', '2019-04-03', '2019-04-04', '2019-04-05', '2019-04-06', '2019-04-07', '2019-04-08', '2019-04-09', '2019-04-10', '2019-04-11', '2019-04-12', '2019-04-13', '2019-04-14', '2019-04-15', '2019-04-16', '2019-04-17', '2019-04-18', '2019-04-19', '2019-04-20', '2019-04-21', '2019-04-22', '2019-04-23', '2019-04-24', '2019-04-25', '2019-04-26', '2019-04-27', '2019-04-28', '2019-04-29', '2019-04-30', 'NULL', 'NULL', '2019-05-03', '2019-05-04', '2019-05-05', '2019-05-06', '2019-05-07', '2019-05-08', '2019-05-09', '2019-05-10', '2019-05-11', '2019-05-12', '2019-05-13', '2019-05-14', '2019-05-15', '2019-05-16', '2019-05-17', '2019-05-18', '2019-05-19', '2019-05-20', '2019-05-21', '2019-05-22', '2019-05-23', '2019-05-24', '2019-05-25', '2019-05-26', '2019-05-27', '2019-05-28', '2019-05-29', '2019-05-30', '2019-05-31', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-06-01', '2019-06-02', '2019-06-03', '2019-06-04', '2019-06-05', '2019-06-06', '2019-06-07', '2019-06-08', '2019-06-09', '2019-06-10', '2019-06-11', '2019-06-12', '2019-06-13', '2019-06-14', '2019-06-15', '2019-06-16', '2019-06-17', '2019-06-18', '2019-06-19', '2019-06-20', '2019-06-21', '2019-06-22', '2019-06-23', '2019-06-24', '2019-06-25', '2019-07-03', '2019-07-04', '2019-07-05', '2019-07-06', '2019-07-07', '2019-07-08', '2019-07-09', '2019-07-10', '2019-07-11', '2019-07-12', '2019-07-13', '2019-07-14', '2019-07-15', '2019-07-16', '2019-07-17', '2019-07-18', '2019-07-19', '2019-07-20', '2019-07-21', '2019-07-22', '2019-07-23', '2019-07-24', '2019-07-25', '2019-07-26', '2019-07-27', '2019-07-28', '2019-07-29', '2019-07-30', '2019-07-31', 'NULL', 'NULL', '2019-08-03', '2019-08-04', '2019-08-05', '2019-08-06', '2019-08-07', '2019-08-08', '2019-08-09', '2019-08-10', '2019-08-11', '2019-08-12', '2019-08-13', '2019-08-14', '2019-08-15', '2019-08-16', '2019-08-17', '2019-08-18', '2019-08-19', '2019-08-20', '2019-08-21', '2019-08-22', '2019-08-23', '2019-08-24', '2019-08-25', '2019-08-26', '2019-08-27', '2019-08-28', '2019-08-29', '2019-08-30', '2019-08-31', 'NULL', 'NULL', '2019-09-03', '2019-09-04', '2019-09-05', '2019-09-06', '2019-09-07', '2019-09-08', '2019-09-09', '2019-09-10', '2019-09-11', '2019-09-12', '2019-09-13', '2019-09-14', '2019-09-15', '2019-09-16', '2019-09-17', '2019-09-18', '2019-09-19', '2019-09-20', '2019-09-21', '2019-09-22', '2019-09-23', '2019-09-24', '2019-09-25', '2019-09-26', '2019-09-27', '2019-09-28', '2019-09-29', '2019-09-30', 'NULL', 'NULL', '2019-10-03', '2019-10-04', '2019-10-05', '2019-10-06', '2019-10-07', '2019-10-08', '2019-10-09', '2019-10-10', '2019-10-11', '2019-10-12', '2019-10-13', '2019-10-14', '2019-10-15', '2019-10-16', '2019-10-17', '2019-10-18', '2019-10-19', '2019-10-20', '2019-10-21', '2019-10-22', '2019-10-23', '2019-10-24', '2019-10-25', '2019-10-26', '2019-10-27', '2019-10-28', '2019-10-29', '2019-10-30', '2019-10-31', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '2019-11-01', '2019-11-02', '2019-11-03', '2019-11-04', '2019-11-05', '2019-11-06', '2019-11-07', '2019-11-08', '2019-11-09', '2019-11-10', '2019-11-11', '2019-11-12', '2019-11-13', '2019-11-14', '2019-11-15', '2019-11-16', '2019-11-17', '2019-11-18', '2019-11-19', '2019-11-20', '2019-11-21', '2019-11-22', '2019-11-23', '2019-11-24', '2019-11-25', '2019-12-03', '2019-12-04', '2019-12-05', '2019-12-06', '2019-12-07', '2019-12-08', '2019-12-09', '2019-12-10', '2019-12-11', '2019-12-12', '2019-12-13', '2019-12-14', '2019-12-15', '2019-12-16', '2019-12-17', '2019-12-18', '2019-12-19', '2019-12-20', '2019-12-21', '2019-12-22', '2019-12-23', '2019-12-24', '2019-12-25', '2019-12-26', '2019-12-27', '2019-12-28', '2019-12-29', '2019-12-30', '2019-12-31', 'NULL', 'NULL'];

/* ------------------------------------------------------------------
			FONCTIONS RECUP CHIFFRE
			---------------------------------------------------------------------*/

			function caJour($pdoQlik,$date){
				$req=$pdoQlik->prepare("SELECT  sum(CAl) as somme, sum(Colis1) as colis, sum(Palettes1) as palettes FROM statscajour WHERE DateCA= :DateCA");
				$req->execute([
					':DateCA'=>$date->format('Y-m-d')

				]);
				$data=$req->fetch(PDO::FETCH_ASSOC);
				if(!empty($data)){
					return 	$data;
				}
				return 0;
			}
			function formatCa($ca){
				return number_format((float)$ca,0,'',' ');
			}

			function sommeCaJour($pdoQlik,$start,$end){
				$req=$pdoQlik->prepare("SELECT sum(CAl) as somme, sum(Colis1) as colis, sum(Palettes1) as palettes FROM statscajour WHERE DateCA BETWEEN :start AND :end");
				$req->execute([
					':start'			=>$start->format('Y-m-d'),
					':end'				=>$end->format('Y-m-d')
				]);
				$data=$req->fetch(PDO::FETCH_ASSOC);
				if(!empty($data)){
					return 	$data;
				}
				return 0;
			}
			function caMois($pdoQlik,$month,$year){
				$req=$pdoQlik->prepare("SELECT CAl as somme, Colisl as colis, Palettesl as palettes FROM statscamois WHERE AnneeCA= :AnneeCA AND MoisCA= :MoisCA");
				$req->execute([
					':AnneeCA'			=>$year,
					':MoisCA'			=>$month

				]);
				$data=$req->fetch(PDO::FETCH_ASSOC);

				return 	$data;


			}






			function caAnnee($pdoQlik,$lastMonth,$year){
				$req=$pdoQlik->prepare("SELECT sum(CAl) as somme, sum(Colisl) as colis, sum(Palettesl) as palettes FROM statscamois WHERE AnneeCA= :AnneeCA AND (MoisCA>= 1 AND MoisCA<=:MoisCA)");
				$req->execute([
					':AnneeCA'			=>$year,
					':MoisCA'			=>$lastMonth

				]);
				$data=$req->fetch(PDO::FETCH_ASSOC);
				if(!empty($data)){
					return 	$data;
				}
				return 0;
			}

			function pourcentage($chiffreActuel,$chiffrePrecedent,$diff){
				if($diff!=0 && $chiffrePrecedent!=0){
					return $pourcentage=($diff*100)/$chiffrePrecedent;
				}
				return 0;

			}

			/* ------------------------------------------------------------------
			FONCTIONS CALCUL DATE
			---------------------------------------------------------------------*/
			function getJour($today){
				//si on est lundi, on prend vendredi
				$todayCalc=clone $today;
				if($todayCalc->format('w')==1){
					return	$todayCalc->modify('- 3 day');
				}elseif($todayCalc->format('w')==0){
					return $todayCalc->modify('- 2 day');
				}
				return $todayCalc->modify('- 1 day');
			}

			// jour à comparer de l'année dernière
			function getJourLastYear($index, $arrDateLastYear){
				$prev=$arrDateLastYear[$index];
				if($prev=='NULL'){
					return $prev;
				}else{
					return new DateTime($prev);

				}
			}

			function getJourLastYearFake($now){
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

			//initialisation de toute les variables
			// pour pouvoir faire des simulations, on met la date du jour dans une variable que l'on utilise dans toutes les fonction plutôt dque de faire un new datetime
			$today=new DateTime();
			$jMoinsUn=getJour($today);
			$annee=$jMoinsUn->format('Y');
			$moisActuel=$jMoinsUn->format('n');
			$anneeLastYear='';
			$moisLastYear='';
			// tableau de date utilisés
			$arrDateThisYear=[];
			$arrDateLastYear=[];
			$nomArrDateThisYear='';
			$nomArrDateLastYear='';
			$indexDate=0;
			// date j moins l'année dernière dans tableau david
			$jMoinsUnLastYear='';
			$jMoinsUnCa='';
			$jMoinsUnLastYearCa='';
			$jMoinsUnDiff='';
			$jMoinsUnPourcent='';

			$premierJourMois='';
			$premierJourMoisLastYear='';

			$dayColorClass ='';
			$icoDay='';
			$monthColorClass='';
			$icoMonth='';



			// chiffre d'affaire du mois en cours jusqu'à aujourd'hui

			// 1- détermine quels tableau de date on doit prendre
			switch ($annee) {
				case '2020':
				$arrDateThisYear=$twenty;
				$arrDateLastYear=$nineteen;
				$nomArrDateThisYear='twenty';
				$nomArrDateLastYear='nineteen';
				break;
				case '2021':
				$arrDateThisYear=$twentyOne;
				$arrDateLastYear=$twenty;
				$nomArrDateThisYear='twentyone';
				$nomArrDateLastYear='twenty';
				break;
				case '2022':
				$arrDateThisYear=$twentyTwo;
				$arrDateLastYear=$twentyOne;
				$nomArrDateThisYear='twentyTwo';
				$nomArrDateLastYear='twentyOne';
				break;
				default:
				$arrDateThisYear=$twenty;
				$arrDateLastYear=$nineteen;
				$nomArrDateThisYear='twenty';
				$nomArrDateLastYear='nineteen';
				break;
			}
			// emplacement de jmoinsun dans le tableau de l'année en cours
			$indexDate=array_search($jMoinsUn->format('Y-m-d'),$arrDateThisYear);
			// peut retourner null NULL
			$jMoinsUnLastYear=getJourLastYear($indexDate, $arrDateLastYear);



			/*							chiffre J moins 1			 */


			/*							chiffre mois en cours			 */

			$premierJourMois=clone $jMoinsUn;
			$premierJourMois=$premierJourMois->modify('first day of this month');
			// si pas de jour l'année dernière, on met le ca à 0 et on calcul les autres dates à partir d'un fake
			if($jMoinsUnLastYear!="NULL"){
				$jMoinsUnLastYearAll=caJour($pdoQlik, $jMoinsUnLastYear);
				$jMoinsUnLastYearCa=$jMoinsUnLastYearAll['somme'];
				$jMoinsUnLastYearPalettes=$jMoinsUnLastYearAll['palette'];
				$jMoinsUnLastYearColis=$jMoinsUnLastYearAll['colis'];
				$anneeLastYear=$jMoinsUnLastYear->format('Y');
				$moisLastYear=$jMoinsUnLastYear->format('n');
				$premierJourMoisLastYear=clone $jMoinsUnLastYear;
				$premierJourMoisLastYear=$premierJourMoisLastYear->modify('first day of this month');
				$moisEnCoursLastYearAll=sommeCaJour($pdoQlik,$premierJourMoisLastYear,$jMoinsUnLastYear);
				$moisEnCoursLastYearCa=$moisEnCoursLastYearAll['somme'];
				$moisEnCoursLastYearPalette=$moisEnCoursLastYearAll['palette'];
				$moisEnCoursLastYearColis=$moisEnCoursLastYearAll['colis'];




			}else{
				$jMoinsUnLastYearCa=0;
				$jMoinsUnLastYearPalettes=0;
				$jMoinsUnLastYearColis=0;
				$fakeJMoinsUnLastYear=getJourLastYearFake(clone $jMoinsUn);
				$anneeLastYear=$fakeJMoinsUnLastYear->format('Y');
				$moisLastYear=$fakeJMoinsUnLastYear->format('n');
				$premierJourMoisLastYear=clone $fakeJMoinsUnLastYear;
				$premierJourMoisLastYear=$premierJourMoisLastYear->modify('first day of this month');
				$moisEnCoursLastYearAll=0;
				$moisEnCoursLastYearCa=0;
				$moisEnCoursLastYearPalette=0;
				$moisEnCoursLastYearColis=0;



			}







			$jMoinsUnAll=caJour($pdoQlik, $jMoinsUn);
			$jMoinsUnCa=$jMoinsUnAll['somme'];
			$jMoinsUnPalettes=$jMoinsUnAll['palettes'];
			$jMoinsUnColis=$jMoinsUnAll['colis'];
			// $jMoinsUnColis=$jMoinsUnAll['Colisl'];
			// $jMoinsUnPalette=$jMoinsUnAll['Palettesl'];

			$jMoinsUnDiff= $jMoinsUnCa-$jMoinsUnLastYearCa;
			$jMoinsUnPourcent=pourcentage($jMoinsUnCa,$jMoinsUnLastYearCa,$jMoinsUnDiff);



			$moisEnCoursAll=sommeCaJour($pdoQlik,$premierJourMois,$jMoinsUn);
			$moisEnCoursCa=$moisEnCoursAll['somme'];
			$moisEnCoursPalette=$moisEnCoursAll['palettes'];
			$moisEnCoursColis=$moisEnCoursAll['colis'];
			$moisEnCoursDiff=$moisEnCoursCa-$moisEnCoursLastYearCa;
			$moisEnCoursPourcent=pourcentage($moisEnCoursCa,$moisEnCoursLastYearCa,$moisEnCoursDiff);

			// rapelle => le ca mois est un cumul donc on cherche juste le ca du mois
			$moisFinAll=caMois($pdoQlik, $moisActuel, $annee);
			$moisFinCa=$moisFinAll['somme'];
			$moisFinColis=$moisFinAll['colis'];
			$moisFinPalettes=$moisFinAll['palettes'];

			$moisFinLastYearAll=caMois($pdoQlik, $moisLastYear, $anneeLastYear);
			$moisFinLastYearCa=$moisFinLastYearAll['somme'];
			$moisFinLastYearColis=$moisFinLastYearAll['colis'];
			$moisFinLastYearPalettes=$moisFinLastYearAll['palettes'];
			$moisFinDiff=$moisFinCa-$moisFinLastYearCa;
			$moisFinPourcent=pourcentage($moisFinCa,$moisFinLastYearCa,$moisFinDiff);

			$anneeEnCoursAll=caAnnee($pdoQlik,$moisActuel, $annee);
			$anneeEnCoursCa=$anneeEnCoursAll['somme'];
			$anneeEnCoursPalettes=$anneeEnCoursAll['palettes'];
			$anneeEnCoursColis=$anneeEnCoursAll['colis'];


			$anneeEnCoursLastYearAll=caAnnee($pdoQlik,$moisLastYear, $anneeLastYear);
			$anneeEnCoursLastYearCa=$anneeEnCoursLastYearAll['somme'];
			$anneeEnCoursLastYearPalettes=$anneeEnCoursLastYearAll['palettes'];
			$anneeEnCoursLastYearColis=$anneeEnCoursLastYearAll['colis'];
			$anneeEnCoursDiff=$anneeEnCoursCa-$anneeEnCoursLastYearCa;
			$anneeEnCoursPourcent=pourcentage($anneeEnCoursCa,$anneeEnCoursLastYearCa,$anneeEnCoursDiff);



			$anneeFinAll=caAnnee($pdoQlik,12, $annee);
			$anneeFinCa=$anneeFinAll['somme'];
			$anneeFinPalettes=$anneeFinAll['palettes'];
			$anneeFinColis=$anneeFinAll['colis'];


			$anneeFinLastYearAll=caAnnee($pdoQlik,12, $anneeLastYear);
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
					<?php if (VERSION=='c'): ?>

						<div class="row">
							<div class="col">
								<table class="table table-sm table-striped">

									<tr>
										<td>aujourd'hui</td>
										<td>J-j</td>
										<td>J-j lastYear</td>
										<td>Index tableau date</td>
									</tr>
									<tr>
										<td><?= JOUR[$today->format('w')] .' ' .$today->format('d-m-Y') ?></td>
										<td><?= JOUR[$jMoinsUn->format('w')] .' ' .$jMoinsUn->format('d-m-Y') ?></td>
										<td><?= ($jMoinsUnLastYear!="NULL")?JOUR[$jMoinsUnLastYear->format('w')].' '. $jMoinsUnLastYear->format('d-m-Y') :"NULL" ?></td>
										<td><?= $indexDate ?></td>

									</tr>
									<tr>
										<td>ca j-1</td>
										<td>ca j-1 année dernière</td>
										<td>différence</td>
										<td>%</td>
									</tr>
									<tr>
										<td><?=formatCa($jMoinsUnCa)?></td>
										<td><?=formatCa($jMoinsUnLastYearCa)?></td>
										<td><?=formatCa($jMoinsUnDiff)?></td>
										<td><?=$jMoinsUnPourcent?></td>
									</tr>
									<tr>
										<td>1er jour mois cette année</td>
										<td>1er jour mois année dernière</td>
										<td></td>
										<td>%</td>
									</tr>
									<tr>
										<td><?= JOUR[$premierJourMois->format('w')] .' ' .$premierJourMois->format('d-m-Y') ?></td>
										<td><?= JOUR[$premierJourMoisLastYear->format('w')] .' ' .$premierJourMoisLastYear->format('d-m-Y') ?></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><?= formatCa($moisEnCoursCa) ?></td>
										<td><?= formatCa($moisEnCoursLastYearCa) ?></td>
										<td><?= $moisEnCoursDiff ?></td>
										<td><?= $moisEnCoursPourcent?></td>
									</tr>
								</table>
							</div>
							<div class="col"></div>

						</div>
					<?php endif ?>

					<div class="row mb-1">
						<div class="col-auto my-auto">
							<h1 class="open">N'oubliez pas  </h1>
						</div>
						<div class="col ">
							<img class="" src="covid.png">
						</div>
					</div>

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
														<?php include 'welcome-inc/01jour.php' ?>

													</div>
													<!-- col pourcentage -->
													<div class="col bg-pourcentage-jour orangish">
														<?php include 'welcome-inc/02jour.php' ?>
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
														<?php include 'welcome-inc/03mois.php' ?>

													</div>
													<!-- col pourcentage -->
													<div class="col-6 bg-pourcentage-mois whitten">
														<?php include 'welcome-inc/04mois.php' ?>
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
														<?php include 'welcome-inc/05moisfin.php' ?>

													</div>
													<!-- col pourcentage -->
													<div class="col bg-pourcentage-jour darken">
														<?php include 'welcome-inc/06moisfin.php' ?>
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
														<?php include 'welcome-inc/07annee.php' ?>

													</div>
													<!-- col pourcentage -->
													<div class="col bg-pourcentage-jour whitten">
														<?php include 'welcome-inc/08annee.php' ?>
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
														<?php include 'welcome-inc/09anneefin.php' ?>

													</div>
													<!-- col pourcentage -->
													<div class="col bg-pourcentage-jour darken">
														<?php include 'welcome-inc/10anneefin.php' ?>
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