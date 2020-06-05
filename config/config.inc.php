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

$pdoCm=connectToDb('cm');
$pdoUser=connectToDb('web_users');
$pdoStat= connectToDb('stats');

$pdoQlik=connectToDb('qlik');
$pdoVal=connectToDb('val');
$pdoExploit= connectToDb('exploit');
$pdoMag=connectToDb(VERSION .'magasin');
$pdoBt=connectToDb('btlec');
$pdoSav=connectToDb('sav');
$pdoEvo=connectToDb(VERSION.'evo');


// emplacement fichier csv gessica et ctbt
define("DIR_IMPORT_GESSICA","D:\btlec\dumps\gessica\\");
// emplcement enregistrement fichier de log
define("DIR_LOGFILES", "D:\www\batch_log\\");
// emplacement consultation fichiers de log
define("DIR_LOGFILES_CONSULT", "http://172.30.92.53/batchlog/");

define("DIR_LOTUS_CSV", "D:\btlec\\".VERSION."lotus");

define("DIR_EXPORT_CSV", "D:\btlec\csv");

define("UPLOAD_DIR","http://172.30.92.53/".VERSION."upload" );

define("SITE_ADDRESS", "http://172.30.92.53/".VERSION."btlecest");
