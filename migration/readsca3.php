<?php
// recup des login d'alex dans table webaccess2 et injection dans web users


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




$pdoweb=getWebUser();
$pdomag=getMag();

// function getGalec1($pdoweb, $pdomag){

// 	$req='SELECT * FROM lkmaguser ORDER BY galec';
// 	$result=$pdomag->query($req);
// 	$row= $result->fetchAll(PDO::FETCH_ASSOC);
// 	if($result->fetchAll(PDO::FETCH_ASSOC)){
// 		$galec=$result['galec'];
// 		$iduser=$result['iduser'];
// 		$req=$pdomag->prepare('SELECT * FROM mag WHERE galec= :galec');
// 		$req->execute(array(
// 		':galec'	=> $galec
// 		));
// 		$data=$req->fetchAll(PDO::FETCH_ASSOC);
// 		return $data;
// 	}

// }

// $id=688;

// function getGalec($pdomag, $id)
// {
// 	$req=$pdomag->prepare("SELECT * FROM lkmaguser WHERE iduser= :id");
// 	$req->execute(array(
// 		':id'	=>$id

// 	));
// 	if($idExist=$req->fetch(PDO::FETCH_ASSOC))
// 		{
// 			return $idExist;
// 		}

// }


// $result=getGalec($pdomag,$id);
// $galec= $result['galec'];

//SELECT www.galec, btlec.galec, btlec.mag FROM web_users.users AS www LEFT JOIN _btlec.sca3 AS btlec ON www.galec = btlec.galec


// function getMagInfo($pdomag,$galec)
// {
// 	$req=$pdomag->prepare("SELECT * FROM sca3 WHERE galec= :galec");
// 	$req->execute(array(
// 		':galec'	=> $galec
// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);


// }


// $infoMag=getMagInfo($pdomag,$galec);

// echo $infoMag['mag'];


//$req=$pdomag->prepare("SELECT btlec.galec as sca3_galec, btlec.mag, www.galec as www_galec FROM `sca3` AS btlec LEFT JOIN web_users.users AS www ON btlec.galec = www.galec WHERE www.galec <>''");
//


$req=$pdomag->prepare("SELECT galec, mag FROM sca3");
$req->execute();
$result=$req->fetchAll(PDO::FETCH_ASSOC);
	echo "<pre>";
	// var_dump($result);
	echo '</pre>';

 foreach ($result as $key => $value) {
 	$req=$pdoweb->prepare("SELECT galec, login FROM users WHERE galec <> :galec");
	$req->execute(array(
		'galec'	=>$value['galec']
	));
$abs=$req->fetchAll(PDO::FETCH_ASSOC);
	echo "<pre>";
	var_dump($abs);
	echo '</pre>';

 }