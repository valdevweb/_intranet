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

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}




function getBTLink() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='btlec';

	try {
		$bdd=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $bdd;

}

/* CONNEXION BASE STATS */
function getStatsLink() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='stats';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

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