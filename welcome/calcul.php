<?php
function getLaVeille($today){
	if($today->format("N")==1){
		$warning[]="1- on affiche les chiffres du vendredi donc - 3 jours<br>";
		$dayToDisplay=$today->modify('-3 day');
	}else{

		$dayToDisplay=$today->modify('-1 day');
	}
	return $dayToDisplay;
}


function getLastDayOfEachMonth($thisYear){

	$lastYear=$thisYear-1;
	//créa tableau date du 1er de chaque mois pour année en cours, année suivante
	for ($i=1; $i <=12 ; $i++) {
		$strdateActu=$thisYear."/".$i."/01";
		$strdatePrev=$lastYear."/".$i."/01";
		$firstDayActuList[$i]=new DateTimeImmutable($strdateActu);
		$firstDayPrevList[$i]=new DateTimeImmutable($strdatePrev);
	}
// modifie le 1er jour du mois en fonction du jour (lundi, mardi, etc) de ce 1er jour du mois
	for ($i=1; $i <=12 ; $i++) {
		$numJourSemaine=$firstDayPrevList[$i]->format('N');
		switch ($numJourSemaine) {
			case 6:
			// echo " on est sur un samedi";
			// echo "<br>";
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify('-1 day');
			$firstDayPrevList[$i]=$firstDayActuList[$i]->modify('-1 year');
			break;
			case 7:
			// echo " on est sur un dimanche";
			// echo "<br>";
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify('-1 day');
			$firstDayPrevList[$i]=$firstDayPrevList[$i];
			break;


			default:
			// echo " pas de cas particulier, le numéro de jour est " .$numJourSemaine;
			// echo "<br>";
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify('-1 day');
			$firstDayPrevList[$i]=$firstDayActuList[$i]->modify('-1 year');
			break;
		}
		// echo "fin de mois 2021 ".$firstDayActuList[$i]->format("d-m-Y");
		// echo "<br>";

		// echo "fin de mois 2020 ".$firstDayPrevList[$i]->format("d-m-Y");
		// echo "<br>";
		// echo "<br>";

	}
	return [$firstDayActuList,$firstDayPrevList ];
}


function getFirstDayOfEachMonth($array){
	for ($i=1; $i <=count($array) ; $i++) {
		$firstDay= clone $array[$i];
		$firstDay->modify('+ 1 day');
		// on ajoute un si der jour mois = lundi sinon, on fait rien
		if($firstDay->format('N')==1){
			$array[$i]=$firstDay;
		}
	}
	return $array;
}


$forceDay=new DateTimeImmutable("2021/08/01");



$warning=[];
if(isset($forceDay)){
	$today=$forceDay;

}else{
	$today=new DateTime();
}


$dayToDisplay=getLaVeille($today);
echo "<pre>";
print_r($dayToDisplay);
echo '</pre>';

//renvoie le tableau  calculés suivant les regles excel de David - logique reprise mais pas comprise
list($lastDayActuList, $lastDayPrevList)= getLastDayOfEachMonth($dayToDisplay->format('Y'));


$monthForCalcul=$dayToDisplay->format('n');
$yearForCalcul=$dayToDisplay->format('Y');
$lastDayOfMonthActu=$lastDayActuList[$monthForCalcul];
$lastDayOfMonthPrev=$lastDayPrevList[$monthForCalcul];

// on vérfie si on est inférieur ou supèrieur à la date de début de mois


$oneOfMonthActu=new DateTimeImmutable($yearForCalcul.'/'.$monthForCalcul.'/01');
$oneOfMonthPrev=new DateTimeImmutable(($yearForCalcul-1).'/'.$monthForCalcul.'/01');
$jourOneActu=$oneOfMonthActu->format('N');
$jourOnePrev=$oneOfMonthPrev->format('N');
echo "<pre>";
print_r($oneOfMonthPrev);
print_r($oneOfMonthActu);
echo '</pre>';


//  pour avoir le même numéro de jour de la semaine, il faudra tj ajouter dayToAdd à prev
$daysToAdd=$jourOneActu-$jourOnePrev;
echo "nb de jour de différence" .$daysToAdd;
$depuisLePremierDuMois=(date_diff($lastDayOfMonthActu,$dayToDisplay))->days;



	// $depuisLePremierDuMois=(date_diff($lastDayOfMonthActu,$dayToDisplay))->days;
	// echo "nb  jours " .$depuisLePremierDuMois;
$dayToDisplayPrev=$lastDayOfMonthPrev->modify($depuisLePremierDuMois .' day');
$dayToDisplayPrev=$dayToDisplayPrev->modify($daysToAdd .' day');
if($dayToDisplayPrev->format('n')!=$dayToDisplay->format('n')){
	$dayToDisplayPrev="";
}

echo "<pre>";
print_r($dayToDisplay);
print_r($dayToDisplayPrev);
echo '</pre>';





