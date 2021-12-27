<?php


$date=date('D Y-m-d');

echo $date;

for ($i=0; $i <365 ; $i++) {


	$veille = date("d-m-Y D", strtotime($date.'- 1 days'));
	$date=$veille;
	$veilleLastyear=date("d-m-Y D", strtotime($date.'- 364 days'));

	// echo "la veille " .$veille . "la veille l'année dernière " .$veilleLastyear;
	// echo "<br>";
	// $dateAr[$i]=['veille'=>$veille, "veille_lastyear"=>$veilleLastyear];
	if(date('m', strtotime($veille))!=date('m', strtotime($veilleLastyear))){
		// echo " PAS AFFICHAGE J-1 LAST YEAR";
		$moisLastyear=date("d-m-Y D", strtotime($veilleLastyear.'- 1 days'));
		$dateAr[$i]=['veille'=>$veille, "veille_lastyear"=>$veilleLastyear, "veille_lastyear_display"=>"", "mois_lastyear" =>$moisLastyear];


	}else{
		$dateAr[$i]=['veille'=>$veille, "veille_lastyear"=>$veilleLastyear, "veille_lastyear_display"=>$veilleLastyear, "mois_lastyear" =>$veilleLastyear];

		// $dateAr[$i]=["veille_lastyear_display"=>""];

	}


	// echo "<br>";


}


// echo "<pre>";
// print_r($dateAr);
// echo '</pre>';


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

	<table class="table table-sm">
		<thead class="thead-dark">
			<tr>
				<th>jour</th>
				<th>jour - un</th>
				<th>jour - un affichage</th>
				<th>fin de mois</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dateAr as $key => $d): ?>
				<tr>
					<td><?=$d['veille']?></td>
					<td><?=$d['veille_lastyear']?></td>
					<td><?=$d['veille_lastyear_display']?></td>
					<td><?=$d['mois_lastyear']?></td>
				</tr>
			<?php endforeach ?>

		</tbody>
	</table>



</body>
</html>