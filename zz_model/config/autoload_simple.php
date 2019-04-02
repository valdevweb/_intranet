<?php
session_start();

define('WEBSITE_NAME', 'Exploitation');
$path=dirname(__FILE__);

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}


	$pdoExploit=connectToDb('exploit');
	$pdoUser=connectToDb('web_users');
	$pdoBt=connectToDb('btlec');
	// $pdoStat=connectToDb('stats');












