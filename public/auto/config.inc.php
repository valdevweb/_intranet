<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	define("VERSION",'_');
	define("DIR_IMPORT_GESSICA","D:\btlec\dumps\gessica\\");
}
else
{
	define("VERSION",'');
	define("VERSION",'');
	define("DIR_IMPORT_GESSICA","D:\btlec\dumps\gessica\\");
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
$dbCm=VERSION."cm";
$pdoCm=connectToDb($dbCm);
$pdoUser=connectToDb('web_users');
$pdoQlik=connectToDb('qlik');
$pdoVal=connectToDb('val');
$pdoExploit= connectToDb('exploit');

define("DIR_LOGFILES", "D:\btlec\batch_log\\");
