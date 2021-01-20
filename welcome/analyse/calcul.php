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





	$monthChange=false;




$today=new DateTimeImmutable("2020/12/01");
echo $today->format("N");
echo "format <br>";

if($today->format("N")==1){
	echo "le lundi, on affiche les chiffres du vendredi donc - 3 jours";
	$dayToDisplay=$today->modify('-3 day');
}else{
	echo "on affiche les chiffres de la veille";
	$dayToDisplay=$today->modify('-1 day');
}

//  on vérifie tout d'abord que le j-1 ou -3 ne fzait pas changer de mois

if($today->format('n')!=$dayToDisplay->format('n')){
	$monthChange=true;
	echo "<br>";
	echo "attention, on change de mois ";
	echo "<br>";

}




echo 	$monthToDisplay=$dayToDisplay->format('n');

  $d = new DateTime('2010-01-19');
$firstDayOfMonthActu=$dayToDisplay->modify('first day of this month');

$firstDayOfMonthPrev=$firstDayOfMonthActu->modify('last year');


	// calcul du nb de jourss passé depuis le 1er du mois du tableau
	$depuisLePremierDuMois=(date_diff($dayToDisplay,$firstDayOfMonthActu))->format('%d');
	$dayToDisplayPrev=$firstDayOfMonthPrev->modify($depuisLePremierDuMois .' day');
	if($dayToDisplayPrev->format('n')!=$dayToDisplay->format('n')){
		echo "on ne peut pas afficher les chiffres des l'année passée on a dépassé le mois";
	echo "<br>";
}


	echo "Nous sommes le " .$today->format("D d-m-Y"). " on affiche les chiffres du " . $dayToDisplay->format('D d-m-Y');
	echo " avec le " .$firstDayOfMonthPrev->format("D d-m-Y");
