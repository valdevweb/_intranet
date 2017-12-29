<?php
require('../../config/autoload.php');

if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

include('../view/_head.php');
include('../view/_navbar.php');


function statProd($pdoStat, $day)
{
	$req=$pdoStat->prepare("SELECT * FROM stats_logs WHERE type_log= :type_log AND site= :site AND date_heure LIKE :day ORDER BY date_heure DESC, id_user");
	$req->execute(array(
		':type_log' =>'prod',
		':site'		=>'portail BT',
		':day'		=> $day.'%'

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
?>
<div class="container">

	<h1>connexions et pages visitées sur site de prod</h1>
<table class="striped s12 grey-text text-darken-2 z-depth-2">

	<tr>
		<th class="stat-t">user</th>
		<th class="stat-t">date_heure</th>
		<th class="stat-t">page</th>
		<th class="stat-t">action</th>
		<th class="stat-t">description</th>
	</tr>



<?php
// $data=statProd($pdoStat,$day);

$month='12';
$year="2017";
$nbDays=cal_days_in_month(CAL_GREGORIAN, $month,$year);

//numero de jour du mois en commençant par lundi =1
//N	Représentation numérique ISO-8601 du jour de la semaine (ajouté en PHP 5.1.0)	1 (pour Lundi) à 7 (pour Dimanche)
$NDate=(int) date("N", mktime(1,1,1, $month,1,$year));
//echo $nbDays;
//echo $NDate;

for ($i=1; $i <=$nbDays ; $i++)
{
	// if ($i<10)
	// {
	// 	$day= $year.'-'.$month .'-0'. $i;
	// }
	// else
	// {
	// 	$day= $year.'-'.$month .'-'. $i;
	// }
		$day= $year.'-'.$month .'-'. $i;

	if($result=statProd($pdoStat,$day))
	{
		foreach ($result as $key => $value) {
			echo "<tr><td>" .$value['id_user'] .' </td><td>' .$value['date_heure'].'</td><td>' .$value['page'] .' </td><td> ' .$value['action'] .'</td><td>' .$value['description'] .'</td></tr>' ;
		}
	}
	else
	{
		//echo "<br>pas de visite le $day <br>";
	}

}
// foreach ($data as $key => $value) {

// 	$date=new DateTime($value['date_heure']);
// 	$date=$date->format('d-m-Y');
// 	echo $date;
// }
echo "</table></div>";




include('../view/_footer.php');
 ?>

</body>
</html>