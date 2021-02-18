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
if(!isset($pdoBt)){
	$pdoBt=connectToDb(VERSION.'btlec');
}
if(!isset($pdoUser)){
	$pdoUser=connectToDb('web_users');
}
if(!isset($pdoMag)){
	$pdoMag=connectToDb(VERSION.'magasin');
}
if(!isset($pdoSav)){
	$pdoSav=connectToDb(VERSION.'sav');
}
if(!isset($pdoLitige)){
	$pdoLitige=connectToDb(VERSION.'litige');
}
if(!isset($pdoCasse)){
	$pdoCasse=connectToDb(VERSION.'casse');
}
if(!isset($pdoOcc)){
	$pdoOcc=connectToDb(VERSION.'occasion');
}
if(!isset($pdoCm)){
	$pdoCm=connectToDb(VERSION.'cm');
}
if(!isset($pdoEvo)){
	$pdoEvo=connectToDb(VERSION.'evo');
}
if(!isset($pdoStat)){
	$pdoStat= connectToDb('stats');
}
if(!isset($pdoQlik)){
	$pdoQlik= connectToDb('qlik');
}
if(!isset($pdoExploit)){
	$pdoExploit= connectToDb('exploit');
}
if(!isset($pdoFou)){
	$pdoFou=connectToDb(VERSION.'fournisseurs');
}