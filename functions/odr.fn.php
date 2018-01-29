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