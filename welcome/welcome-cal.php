<?php
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


function getFirstDayOfEachMonth($thisYear){

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
		// if(($firstDayActuList[$i]->format('N')==7)  ){
		// 	// echo "jour de la semaine" .$firstDayPrevList[$i]->format('N');
		// 	$diff=-(1-$firstDayPrevList[$i]->format('N'));
		// 	$firstDayActuList[$i]=$firstDayActuList[$i]->modify('+ '.$diff.'day');
		// 	// echo"cas 1 pour le mois de ".$i ." on a ".$firstDayActuList[$i]->format('d-m-Y');
		// 	// echo "<br>";


		// }else
		if($firstDayPrevList[$i]->format('N')==6){
			// echo "mois ".$i."cas 1";
			$diff=-($firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N'));
			$firstDayActuList[$i]=$firstDayActuList[$i]->modify(' '.$diff.'day');
			// 		echo " cas 2 pour le mois de ".$i ." on a ".$firstDayActuList[$i]->format('d-m-Y');
			// echo "<br>";
		}elseif($firstDayPrevList[$i]->format('N')==7){
			// echo "mois ".$i."cas 2";

			$diff=$firstDayActuList[$i]->format('N')- 0;
			$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');
		}else{
			// echo "mois ".$i."cas 3";

			$diff=$firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N');
			$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');
		}
		echo "<br>";


	}
	return [$firstDayActuList,$firstDayPrevList ];
}

// $forceDay=new DateTimeImmutable("2020/12/07");



$warning=[];
if(isset($forceDay)){
	$today=$forceDay;
	echo "ici";

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



// if($dayToDisplay<$firstDayOfMonthActu){


// 	// echo "jour à afficher inférieur au 1er jour du mois calculé";
// 	// echo $dayToDisplay;
// 	$dayToDisplayPrev="";

// }else{


	// calcul du nb de jourss passé depuis le 1er du mois du tableau
	// $depuisLePremierDuMois=(date_diff($dayToDisplay,$firstDayOfMonthActu))->format('%d');
	// $depuisLePremierDuMois=(date_diff($firstDayOfMonthActu,$dayToDisplay))->format('d');
	// $depuisLePremierDuMois=(date_diff($firstDayOfMonthActu,$dayToDisplay))->days;
	// echo $depuisLePremierDuMois;
	// 	echo "<pre>";
	// 	print_r($firstDayOfMonthActu);
	// 	print_r($dayToDisplay);
	// 	echo '</pre>';


	// echo "nb  jours " .$depuisLePremierDuMois;
	// $dayToDisplayPrev=$firstDayOfMonthPrev->modify($depuisLePremierDuMois .' day');


	$dayToDisplayPrev=clone $dayToDisplay;
	$dayToDisplayPrev=$dayToDisplayPrev->modify('-364 day');

		echo "<pre>";
	print_r($dayToDisplay);
	print_r($dayToDisplayPrev);
	echo '</pre>';

// }


// if($dayToDisplayPrev->format('m')!=$dayToDisplay->format('m')){
// 	$dayToDisplayPrev="";

// 	// echo "true";

// }




// echo "firstDayPrevList";

// 	echo "<pre>";
// 	print_r($firstDayPrevList);
// 	echo '</pre>';
