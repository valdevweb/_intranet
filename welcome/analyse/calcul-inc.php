<?php
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

