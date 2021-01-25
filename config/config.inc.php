<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	define("VERSION",'_');
	// set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	define("VERSION",'');
	// set_include_path("D:\www\intranet\btlecest\\");
}

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


// emplacement fichier csv gessica et ctbt
define("DIR_IMPORT_GESSICA","D:\btlec\dumps\gessica\\");
// emplcement enregistrement fichier de log
define("DIR_LOGFILES", "D:\www\batch_log\\");
// emplacement consultation fichiers de log
define("DIR_LOGFILES_CONSULT", "http://172.30.92.53/batchlog/");

define("DIR_LOTUS_CSV", "D:\btlec\\".VERSION."lotus");

define("DIR_EXPORT_CSV", "D:\btlec\csv");


define("SITE_ADDRESS", "http://172.30.92.53/".VERSION."btlecest");

define("URL_UPLOAD","http://172.30.92.53/".VERSION."upload/" );
define("DIR_UPLOAD","D:\www\\".VERSION."intranet\upload\\" );