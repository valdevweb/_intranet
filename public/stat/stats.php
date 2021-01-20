<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

include('../view/_head.php');
include('../view/_navbar.php');


function statProd($pdoStat, $year,$month)
{
	$req=$pdoStat->prepare("SELECT id_log, type_log,id_user, site, year(date_heure)as year,page, month(date_heure) as month,date_heure,action, description FROM stats_logs WHERE type_log= :type_log AND site= :site AND year(date_heure)=:year AND month(date_heure)=:month ORDER BY date_heure DESC, id_user");
	$req->execute(array(
		':type_log' =>'prod',
		':site'		=>'portail BT',
		':year'		=> $year,
		':month'	=> $month

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

$month=1;
$year=2018;
$nbDays=cal_days_in_month(CAL_GREGORIAN, $month,$year);
var_dump($month);


	if($result=statProd($pdoStat,$year,$month))
	{
		foreach ($result as $key => $value) {
			echo "<tr><td>" .$value['id_user'] .' </td><td>' .$value['date_heure'].'</td><td>' .$value['page'] .' </td><td> ' .$value['action'] .'</td><td>' .$value['description'] .'</td></tr>' ;
		}
	}
	else
	{
		//echo "<br>pas de visite le $day <br>";
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