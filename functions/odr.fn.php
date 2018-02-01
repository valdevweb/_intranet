<?php


function insertOdr($pdoBt,$file)
{
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO odr (operation,gt,brand,startdate,enddate,files) VALUE (:operation,:gt,:brand,:startdate,:enddate,:files)');
	$result=$insert->execute(array(
		':operation'=>$_POST['operation'],
		':gt'=>$_POST['gt'],
		':brand'=>$_POST['brand'],
		':startdate'=>$_POST['startdate'],
		':enddate'=>$_POST['enddate'],
		':files'=>$file
	));
	return $result;
}

function updateOdr($pdoBt,$file,$odrId)
{

	$req=$pdoBt->prepare('UPDATE odr SET operation=:operation, gt=:gt, brand=:brand, startdate=:startdate, enddate=:enddate, files=:file WHERE id= :id');
	$result=$req->execute(array(
		':operation'=>$_POST['operation'],
		':gt'=>$_POST['gt'],
		':brand'=>$_POST['brand'],
		':startdate'=>$_POST['startdate'],
		':enddate'=>$_POST['enddate'],
		':file'=>$file,
		 ':id'=>$odrId


	));

	return $result;
}







// recup odr en cours de validités
function showCurrentOdr($pdoBt)
{

	$today= new DateTime();
	$today=$today->format('Y-m-d');
	$req=$pdoBt->prepare("SELECT * FROM odr WHERE startdate<= :today AND enddate>= :today");
	$req->execute(array(
		':today' =>$today
	));

	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// recup odr en cours de validités
function showNextOdr($pdoBt)
{

	$today= new DateTime();
	$today=$today->format('Y-m-d');
	$req=$pdoBt->prepare("SELECT * FROM odr WHERE startdate>= :today");
	$req->execute(array(
		':today' =>$today
	));

	return $req->fetchAll(PDO::FETCH_ASSOC);
}


//affichage de toutes les odr depuis un an
//attention francisation de la date
//SELECT DATE_FORMAT(date, '%d/%m/%Y %Hh%imin%ss') AS date FROM table //renvoie DD/MM/YYYY HHhMMSS49s ( 11/03/2010 15h47min49)
function showOneYearOdr($pdoBt)
{

	$today= new DateTime();
	$today->modify('-1 year');
	$today=$today->format('Y-m-d');
	$req=$pdoBt->prepare("SELECT id, operation, gt, brand, DATE_FORMAT(startdate, '%d-%m-%Y') as startdate, DATE_FORMAT(enddate, '%d-%m-%Y') as enddate, files FROM odr WHERE startdate>= :today ORDER BY startdate DESC");
	$req->execute(array(
		':today' =>$today
	));

	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function extractLink($string){
	$html="";
	$links=explode(';',$string);
	foreach ($links as $link)
	{
		$html.="<a href='".UPLOAD_DIR."/odr/".$link."'>".$link ."</a><br>";
	}
	return $html;
}


function showThisOdr($pdoBt)
{

	$today= new DateTime();
	$today->modify('-1 year');
	$today=$today->format('Y-m-d');
	$req=$pdoBt->prepare("SELECT * FROM odr WHERE id= :odr");
	$req->execute(array(
		':odr' =>$_GET['odr']
	));

	return $req->fetch(PDO::FETCH_ASSOC);

}


function checkboxFiles($string){

	$html="";
	$links=explode(';',$string);
	foreach ($links as $link)
	{
		$html.="<input type='checkbox' name='uploaded' value='".$link."'><label>".$link ."</label><br>";
	}
	return $html;


}