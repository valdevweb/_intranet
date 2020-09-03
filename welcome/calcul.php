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

		// echo "OK special 1 ".$diff;
		// echo "<br>";
		// echo "LES 1ER DATES ";
		// echo "<br>";
		$firstDayActuList[$i]=$firstDayActuList[$i]->modify('+ '.$diff.'day');
	// echo "<pre>";
	// print_r($firstDayActuList[$i]);
	// print_r($firstDayPrevList[$i]);
	// echo '</pre>';


	}elseif($firstDayPrevList[$i]->format('N')==6){
		$diff=-($firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N'));

		// echo "OK special 3 ".$diff;
		// echo "<br>";

		// echo "LES 1ER DATES ";
		// echo "<br>";
		$firstDayActuList[$i]=$firstDayActuList[$i]->modify('+ '.$diff.'day');
	// echo "<pre>";
	// print_r($firstDayActuList[$i]);
	// print_r($firstDayPrevList[$i]);
	// echo '</pre>';

	}elseif($firstDayPrevList[$i]->format('N')==7){
		$diff=$firstDayActuList[$i]->format('N')- 0;
		$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');
	// 	echo "NOPE devrait faire + 2 special 2 faire calcul inverse ".$diff;
	// 	echo "<br>";
	// 		echo "<pre>";
	// print_r($firstDayActuList[$i]);
	// print_r($firstDayPrevList[$i]);
	// echo '</pre>';
	}
	else{
		$diff=$firstDayActuList[$i]->format('N')-$firstDayPrevList[$i]->format('N');
		$firstDayPrevList[$i]=$firstDayPrevList[$i]->modify($diff .' day');

	// 	echo "OK normal ". $diff . 'pour le mois de '.$i;
	// 	echo "<br>";
	// 	echo "<pre>";
	// print_r($firstDayActuList[$i]);
	// print_r($firstDayPrevList[$i]);
	// echo '</pre>';

	}

}
for ($i=1; $i <=12 ; $i++) {
	echo $firstDayActuList[$i]->format('d/m/Y') .' avec '. $firstDayPrevList[$i]->format('d/m/Y');
	echo "<br>";

}









$today=new DateTimeImmutable("2020/09/02");
echo $today->format("N");
echo "<br>";

if($today->format("N")==1){
	echo "on affiche les chiffres du vendredi donc - 3 jours";
	$dayToCheck=$today->modify('-3 day');
}else{
	echo "on affiche les chiffres de la veille";
	$dayToCheck=$today->modify('-1 day');
}

//  on vérifie tout d'abord que le j-1 ou -3 ne fzait pas changer de mois

if($today->format('n')!=$dayToCheck->format('n')){
	echo "<br>";
	echo "attention, on change de mois ";
	echo "<br>";

}




echo 	$monthToCheck=$dayToCheck->format('n');
$firstDayOfMonthActu=$firstDayActuList[$monthToCheck];
$firstDayOfMonthPrev=$firstDayPrevList[$monthToCheck];
echo "<pre>";
print_r($today);
print_r($firstDayOfMonthPrev);
echo '</pre>';

// on vérfie si on est inférieur ou supèrieur à la date de début de mois

if($dayToCheck<$firstDayOfMonthActu){
	echo "attention la date est inférieure à la date du 1er jour du mois donc on ne peut pas afficher la correspondance de l'année précédente";

}else{
	// calcul du nb de jourss passé depuis le 1er du mois du tableau
	$depuisLePremierDuMois=(date_diff($dayToCheck,$firstDayOfMonthActu))->format('%d');
	$dayToCheckPrev=$firstDayOfMonthPrev->modify($depuisLePremierDuMois .' day');
	if($dayToCheckPrev->format('n')!=$dayToCheck->format('n')){
		echo "on ne peut pas afficher les chiffres des l'année passée on a dépassé le mois";
	echo "<br>";

	}


}


	echo "Nous sommes le " .$today->format("D d-m-Y"). " on affiche les chiffres du " . $dayToCheck->format('D d-m-Y');
	echo " avec le " .$firstDayOfMonthPrev->format("D d-m-Y");
for($d=0;$d<=365;$d++){



}