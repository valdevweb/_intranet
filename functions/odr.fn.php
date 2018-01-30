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