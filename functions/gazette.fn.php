<?php


function showThisWeek($pdoBt)
{
	//debut et fin de la semaine en cours
	$start=new DateTime('Monday this week');
	$start=$start->format('Y-m-d');
	$end=new DateTime('Friday this week');
	$end=$end->format('Y-m-d');
	//SELECT file, month(date) as month, day(date) as day, date FROM `gazette`
	$req=$pdoBt->prepare("SELECT file, month(date) as month, day(date) as day, year(date) as year, date, category FROM gazette WHERE category=:gazette AND date BETWEEN :start AND :end ORDER BY date");
	$req->execute(array(
		':gazette'  =>'gazette',
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
	 	$jour=$g['day'];
	 	$month=$g['month'];
	 	$month=$months[$month];
	 	$year=$g['year'];
	 	$link="http://172.30.92.53/".$version."upload/gazette/" .$g['file'];
	 	$html="<li><i class='fa fa-angle-double-right'></i><a href='".$link."' class='simple-link stat-link' data-user-session='".$_SESSION['user']."'>la gazette du ".$jour .' '. $month .' '.$year ."</a></li>";
	 	array_push($gazette,$html);
	 }
	 return $gazette;
}

function showLastGazettesAppros($pdoBt)
{
	$req=$pdoBt->prepare("SELECT file, month(date) as month, day(date) as day, year(date) as year, date, category FROM gazette WHERE category=:gazette ORDER BY date ASC LIMIT 2");
	$req->execute(array(
		':gazette'  =>'gazette appros'

	));

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

function createLinksAppros($pdoBt,$gazettes,$version)
{
	$months= array('','janvier', 'février', 'mars', 'avril', 'mai', 'juin','juillet', 'août', 'septembre', 'octobre','novembre','décembre');
	$gazette=array();
	foreach ($gazettes as $g) {
	 	$jour=$g['day'];
	 	$month=$g['month'];
	 	$month=$months[$month];
	 	$year=$g['year'];
	 	$link="http://172.30.92.53/".$version."upload/gazette/" .$g['file'];
	 	$html="<li><i class='fa fa-angle-double-right'></i><a href='".$link."' class='simple-link stat-link' data-user-session='".$_SESSION['user']."'>la gazette du appros ".$jour .' '. $month .' '.$year ."</a></li>";
	 	array_push($gazette,$html);
	 }
	 return $gazette;
}






function histoGaz($pdoBt,$week,$year,$category)
{
	$req=$pdoBt->prepare("SELECT date, id,file,category, week(date) as week, year(date) as year FROM gazette WHERE week(date)= :week AND year(date)=:year AND category=:category ORDER BY date");
	$req->bindValue(':week',$week, PDO::PARAM_INT);
	$req->bindValue(':year',$year, PDO::PARAM_INT);
	$req->bindValue(':category',$category, PDO::PARAM_INT);

	$req->execute();
return $req->fetchAll(PDO::FETCH_ASSOC);
}

// retourne le nombre de semaine d'une année passée en paramètre => sert encore ??? page consultation gazette
function getIsoWeeksInYear($year)
{
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}


