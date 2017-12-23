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







$pdo=getAlex();
var_dump($pdo);

function getUsernames($pdo){

	$req='SELECT user		 FROM webaccess2 ORDER BY user';
	// $req ->execute();
	$result=$pdo->query($req);
	$row= $result->fetchAll(PDO::FETCH_ASSOC);





return $row;

}




$data=getUsernames($pdo);
$pdoweb=getWebUser();

foreach ($data as $value) {
	$user=$value['user'];
	newDb($pdoweb, $user);
}

var_dump($user);


function newDb($pdoweb,$user){
	$req=$pdoweb->prepare('INSERT INTO users (login)
		VALUE(:login)');
	$req->execute(array(
		':login'	=>$user
	));

}



