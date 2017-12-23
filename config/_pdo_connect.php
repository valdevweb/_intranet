<?php
/*
*
*			connection aux db
*			base mag => user
*			base bt =>user
*
*/

/*CONNEXION BASE MAG*/
function getWebUserLink() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='web_users';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
		// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}
// var_dump(getWebUserLink());


/* CONNEXION BASE BTLEC */
function getBTLink() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='_btlec';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
		// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	         	echo "dead";
	      	}
 	return  $pdo;

}

/* CONNEXION BASE STATS */
function getStatsLink() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='stats';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
		// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	         	echo "dead";
	      	}
 	return  $pdo;

}



$pdoStat= getStatsLink();
$pdoUser=getWebUserLink();
$pdoBt=getBTLink();



// $db=getBTLink();
// var_dump($db);

