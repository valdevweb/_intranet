<?php
for ($i=1; $i <=12 ; $i++) {
	if($i<10){
		$strdateActu="2020/0".$i."/01";
		$strdatePrev="2019/0".$i."/01";

	}else{
		$strdateActu="2020/".$i."/01";
		$strdatePrev="2019/".$i."/01";
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




	// echo "<pre>";
	// print_r($firstDayPrevList);
	// echo '</pre>';




$first=new DateTimeImmutable("2020/01/04");
?>

<table style="border-collapse: collapse">
	<thead class="thead-dark">
		<tr>
			<th>date du jour</th>
			<th>date CA</th>
			<th>date CA last year</th>
			<th>Warning</th>
		</tr>
	</thead>
	<tbody>


		<?php
		for($d=0;$d<=365;$d++){
			$warning=[];
			$today=$first->modify($d .' day ');
			if($today->format("N")==1){
				// $warning[]="1- on affiche les chiffres du vendredi donc - 3 jours<br>";
				$dayToDisplay=$today->modify('-3 day');
			}else{

				$dayToDisplay=$today->modify('-1 day');
			}

//  on vérifie tout d'abord que le j-1 ou -3 ne fzait pas changer de mois

			if($today->format('n')!=$dayToDisplay->format('n')){
				$warning[]="2- attention, on change de mois </br>";

			}
			$monthToDisplay2=$dayToDisplay->format('F');
			$monthToDisplay=$dayToDisplay->format('n');

			// list($firstDayOfMonthActu2,$firstDayOfMonthPrev2)=checkFirstOfMonth($dayToDisplay,$monthToDisplay2);


			list($firstDayActuList, $firstDayPrevList)= getFirstDayOfEachMonth($dayToDisplay->format('Y'));

	// echo "<pre>";
	// print_r($firstDayPrevList2);
	// echo '</pre>';


			$firstDayOfMonthActu=$firstDayActuList[$monthToDisplay];
			$firstDayOfMonthPrev=$firstDayPrevList[$monthToDisplay];

// on vérfie si on est inférieur ou supèrieur à la date de début de mois

			if($dayToDisplay<$firstDayOfMonthActu){
				$warning[]="3- attention la date est inférieure à la date du 1er jour du mois donc on ne peut pas afficher la correspondance de l'année précédente<br>" .$firstDayOfMonthActu->format('d-m-Y').' '.$firstDayOfMonthActu->format('d-m-Y').' ' .$dayToDisplay->format('d-m-Y');
				$dayToDisplayStr=$dayToDisplay->format('d-m-Y');;
				$dayToDisplayPrevStr="";
			}else{
	// calcul du nb de jourss passé depuis le 1er du mois du tableau
				$depuisLePremierDuMois=(date_diff($dayToDisplay,$firstDayOfMonthActu))->format('%d');
				$dayToDisplayPrev=$firstDayOfMonthPrev->modify($depuisLePremierDuMois .' day');
				$dayToDisplayPrevStr=$dayToDisplayPrev->format("D d-m-Y");

				if($dayToDisplayPrev->format('n')!=$dayToDisplay->format('n')){
					$warning[]="3- on ne peut pas afficher les chiffres des l'année passée on a dépassé le mois<br>".$firstDayOfMonthPrev->format('d-m-Y').' '.$firstDayOfMonthPrev->format('d-m-Y').' ' .$dayToDisplay->format('d-m-Y');
					$dayToDisplayPrevStr="RIEN";
				}
				$dayToDisplayStr=$dayToDisplay->format('D d-m-Y');

			}


	// 		if($dayToDisplay<$firstDayOfMonthActu2){
	// 			$warning[]="3- attention la date est inférieure à la date du 1er jour du mois donc on ne peut pas afficher la correspondance de l'année précédente bis<br>";
	// 			$dayToDisplayStr="";
	// 			$dayToDisplayPrevStr="";
	// 		}else{
	// // calcul du nb de jourss passé depuis le 1er du mois du tableau
	// 			$depuisLePremierDuMois=(date_diff($dayToDisplay,$firstDayOfMonthActu2))->format('%d');
	// 			$dayToDisplayPrev=$firstDayOfMonthPrev2->modify($depuisLePremierDuMois .' day');
	// 			$dayToDisplayPrevStr=$dayToDisplayPrev->format("D d-m-Y");

	// 			if($dayToDisplayPrev->format('n')!=$dayToDisplay->format('n')){
	// 				$warning[]="3- on ne peut pas afficher les chiffres des l'année passée on a dépassé le mois bis<br>";
	// 				$dayToDisplayPrevStr="RIEN";
	// 			}
	// 			$dayToDisplayStr=$dayToDisplay->format('D d-m-Y');

	// 		}

			echo "<tr>";
			echo "<td style='border: 1px solid black; padding : 5px'>".$today->format("D d-m-Y")."</td>";
			echo "<td style='border: 1px solid black; padding : 5px'>".$dayToDisplayStr."</td>";
			echo "<td style='border: 1px solid black; padding : 5px'>".$dayToDisplayPrevStr."</td>";
			echo "<td style='border: 1px solid black; padding : 5px'>";
			for ($i=0; $i <count($warning) ; $i++) {
				echo $warning[$i];
			}
			echo "</td>";

			echo "</tr>";



		}
		?>
	</tbody>
</table>