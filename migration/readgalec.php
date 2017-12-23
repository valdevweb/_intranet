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
function getGalec($pdoweb){

	$req='SELECT * FROM users ORDER BY galec';
	// $req ->execute();
	$result=$pdoweb->query($req);
	$row= $result->fetchAll(PDO::FETCH_ASSOC);
return $row;

}




$data=getGalec($pdoweb);

foreach ($data as $value)
{
	$galec=$value['galec'];
	$ville=$value['temp'];
	$id=$value['id'];
	if(!is_null($galec))
	{
		//echo $id ." - " . $galec . ' - ' . $ville .'<br>';

 		//writelk($pdomag, $id, $galec);
	}
	else
	{
		//echo $id ." - NULL - " . $ville .'<br>';
	}

}



function writelk($pdomag, $id, $galec)
{
$req=$pdomag->prepare('INSERT INTO lkmaguser (iduser,galec)
		VALUE(:iduser, :galec)');

$req->execute(array(
	':iduser'	=> $id,
	':galec'	=> $galec

));

}






