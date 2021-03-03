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


function getFirstDayOfEachMonth($thisYear){

	$lastYear=$thisYear-1;
	for ($i=1; $i <=12 ; $i++) {
		if($i<10){
			$strdateActu=$thisYear."/0".$i."/01";
			$strdatePrev=$lastYear."/0".$i."/01";

		}else{
			$strdateActu=$thisYear."/".$i."/01";
			$strdatePrev=$lastYear."/".$i."/01";
		}

		$firstDayActuList[$i]=new DateTimeImmutable($strdateActu);
		$firstDayPrevList[$i]=new DateTimeImmutable($strdatePrev);

	}

	for ($i=1; $i <=12 ; $i++) {
		if(($firstDayActuList[$i]->format('N')==7)  ){
			$diff=-(0-$firstDayPrevList[$i]->format('N'));
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify('+ '.$diff.'day');
		}elseif($firstDayPrevList[$i]->format('N')==6){
			$diff=-($firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N'));
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify('+ '.$diff.'day');
		}elseif($firstDayPrevList[$i]->format('N')==7){
			$diff=$firstDayActuList[$i]->format('N')- 0;
			$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');
		}
		else{
			$diff=$firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N');
			$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');
		}

	}
	return [$firstDayActuList,$firstDayPrevList ];
}

// $forceDay=new DateTimeImmutable("2020/12/07");



$warning=[];
if(isset($forceDay)){
	$today=$forceDay;

}else{
	$today=new DateTime();
}


$dayToDisplay=getLaVeille($today);
			//renvoie le tableau des 1er jours du mois calculés suivant les regles excel de David - logique reprise mais pas comprise

list($firstDayActuList, $firstDayPrevList)= getFirstDayOfEachMonth($dayToDisplay->format('Y'));
$monthForCalcul=$dayToDisplay->format('n');
$firstDayOfMonthActu=$firstDayActuList[$monthForCalcul];
$firstDayOfMonthPrev=$firstDayPrevList[$monthForCalcul];

// on vérfie si on est inférieur ou supèrieur à la date de début de mois

if($dayToDisplay<$firstDayOfMonthActu){
	$dayToDisplayPrev="";
}else{
	// calcul du nb de jourss passé depuis le 1er du mois du tableau
	$depuisLePremierDuMois=(date_diff($dayToDisplay,$firstDayOfMonthActu))->format('%d');
	$dayToDisplayPrev=$firstDayOfMonthPrev->modify($depuisLePremierDuMois .' day');

}


