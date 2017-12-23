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

function getUsernames($pdo){

	$req='SELECT num, ville, designation FROM nummag2';
	// $req ->execute();
	$result=$pdo->query($req);
	$row= $result->fetchAll(PDO::FETCH_ASSOC);
return $row;

}




$data=getUsernames($pdo);
$pdoweb=getWebUser();

foreach ($data as $value) {
	$galec=$value['num'];
	$login=$value['ville'];
	$city=$value['designation'];
	newDb($pdoweb,$login,$galec,$city);
}

var_dump($login);
var_dump($galec);
var_dump($city);




function newDb($pdoweb,$login,$galec,$city){
	$req=$pdoweb->prepare('SELECT login FROM users WHERE login= :login');
	$req ->execute(array(
		':login' =>$login
	));


	if($exist= $req->fetch()){

	$req=$pdoweb->prepare('UPDATE users SET galec= :galec, temp = :temp WHERE login= :login');
		// $update=$dbUser->prepare('UPDATE users SET pwd= :pwd, date_signin= :date_signin WHERE id= :id');


	$req->execute(array(
		':login'	=>$login,
		':galec'	=>$galec,
		':temp'		=>$city
	));
	}
}



