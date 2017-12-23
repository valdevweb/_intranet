<?php


function showThisWeek($pdoBt)
{
	//debut et fin de la semaine en cours
	$start=new DateTime('Monday this week');
	$start=$start->format('Y-m-d');
	$end=new DateTime('Friday this week');
	$end=$end->format('Y-m-d');
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date BETWEEN :start AND :end ORDER BY date");
	$req->execute(array(
		':start' 	=> $start,
		':end'		=> $end
	));

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}



function createLinks($pdoBt,$gazettes,$version)
{
	$months= array('','janvier', 'février', 'mars', 'avril', 'mai', 'juin','juillet', 'août', 'septembre', 'octobre','novembre','décembre');
	$gazette=array();
	foreach ($gazettes as $g) {
		$date=explode('-',$g['date']);
		$jour=$date[2];
		$month=$date[1];
		$month=$months[$month];
		$year=$date[0];
		$link="http://172.30.92.53/".$version."upload/gazette/" .$g['file'];
		$html="<li><a href='".$link."' class='simple-link'>la gazette du ".$jour .' '. $month .' '.$year ."</a></li>";
		array_push($gazette,$html);
	}
	return $gazette;
}


