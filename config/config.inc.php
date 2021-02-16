<?php

define("SERVER_NAME", $_SERVER['SERVER_NAME']);
if (preg_match('/_btlecest/', dirname(__FILE__))){
	$version='_';
	define("VERSION",'_');
	define("PORTAIL_FOU","http://".SERVER_NAME."/".VERSION."portail-fournisseurs/" );
}else{
	$version='';
	define("VERSION",'');
	define("PORTAIL_FOU","http://159.180.231.226:8000/".VERSION."portail-fournisseurs/" );
}
define("CONSEIL", "http://".SERVER_NAME."/".VERSION."conseil/");
define("DIR_LOGFILES_CONSULT", "http://".SERVER_NAME."/batchlog/");
define("DIR_UPLOAD","D:\www\\".VERSION."upload-main\\btlecest\\" );
define("DIR_LOGFILES", "D:\www\batch_log\\");
define("DIR_LOTUS_CSV", "D:\btlec\\".VERSION."lotus");
define("DIR_EXPORT_CSV", "D:\btlec\csv");
define("PORTAIL","Portail BTlec" );
define("PORTAIL_CM","http://".SERVER_NAME."/".VERSION."cm/" );
define("PORTAIL_SAV","http://".SERVER_NAME."/".VERSION."sav/" );
define("PORTAIL_SAV_HOME","http://".SERVER_NAME."/".VERSION."sav/scapsav/home.php" );
define("ROOT_PATH","/".VERSION."btlecest");
define("SITE_ADDRESS", "http://".SERVER_NAME."/".VERSION."btlecest");
define("URL_UPLOAD","http://".SERVER_NAME."/".VERSION."upload-main/btlecest/" );





define("PDF_FOOTER", '<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>');
define("PDF_FOOTER_PAGE", '<table class="padding-table full-width"><tr><td class="footer ">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td><td>{PAGENO}{nbpg}</td></tr></table>');
$okko= 'version : ' . ROOT_PATH;

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