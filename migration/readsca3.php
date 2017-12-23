<?php
// recup des login d'alex dans table webaccess2 et injection dans web users



function getAlex() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='test';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}

function getWebUser() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='_web_users';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}

function getMag() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='_mag';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}





$pdo=getAlex();
$pdoweb=getWebUser();
$pdomag=getMag();
function getGalec1($pdoweb, $pdomag){

	$req='SELECT * FROM lkmaguser ORDER BY galec';
	$result=$pdomag->query($req);
	$row= $result->fetchAll(PDO::FETCH_ASSOC);
	if($result->fetchAll(PDO::FETCH_ASSOC)){
		$galec=$result['galec'];
		$iduser=$result['iduser'];
		$req=$pdomag->prepare('SELECT * FROM mag WHERE galec= :galec');
		$req->execute(array(
		':galec'	=> $galec
		));
		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

}

$id=688;

function getGalec($pdomag, $id)
{
	$req=$pdomag->prepare("SELECT * FROM lkmaguser WHERE iduser= :id");
	$req->execute(array(
		':id'	=>$id

	));
	if($idExist=$req->fetch(PDO::FETCH_ASSOC))
		{
			return $idExist;
		}

}


$result=getGalec($pdomag,$id);
$galec= $result['galec'];




function getMagInfo($pdomag,$galec)
{
	$req=$pdomag->prepare("SELECT * FROM sca3 WHERE galec= :galec");
	$req->execute(array(
		':galec'	=> $galec
	));
	return $req->fetch(PDO::FETCH_ASSOC);


}


$infoMag=getMagInfo($pdomag,$galec);

echo $infoMag['mag'];


