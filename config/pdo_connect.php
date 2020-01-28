<?php
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

// no dev
$pdoUser=connectToDb('web_users');
$pdoStat= connectToDb('stats');
$pdoQlik= connectToDb('qlik');

// dev
$pdoBt=connectToDb('btlec');
$pdoSav=connectToDb('sav');
$pdoLitige=connectToDb('litige');
$pdoCasse=connectToDb('casse');
$pdoCm=connectToDb('cm');
