<?php
session_start();
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path)){
	$version='_';
	define("VERSION",'_');
	define("ROOT_PATH","/_btlecest");
	define("PORTAIL","Portail BTlec - dev" );
	// define("SITE_ADDRESS", "http://172.30.92.53/_btlecest");

}else{
	$version='';
	define("VERSION",'');
	define("ROOT_PATH","/btlecest");
	define("PORTAIL","Portail BTlec" );
}


define("SITE_ADDRESS", "http://172.30.92.53/".VERSION."btlecest");
define("PORTAIL_SAV_HOME","http://172.30.92.53/".VERSION."sav/scapsav/home.php" );
define("PORTAIL_SAV","http://172.30.92.53/".VERSION."sav/" );
define("PORTAIL_FOU","http://172.30.92.53/".VERSION."portail-fournisseurs/" );
define("PORTAIL_CM","http://172.30.92.53/".VERSION."cm/" );
define("CONSEIL", "http://172.30.92.53/".VERSION."conseil/");
define("DIR_LOGFILES", "D:\www\batch_log\\");
define("DIR_LOGFILES_CONSULT", "http://172.30.92.53/batchlog/");
define("UPLOAD_DIR","http://172.30.92.53/".VERSION."upload" ); //=>URL_UPLOAD
define("URL_UPLOAD","http://172.30.92.53/".VERSION."upload/" );
define("DIR_UPLOAD","D:\www\\".VERSION."intranet\upload\\" );


// define("UPLOAD_URL","http://172.30.92.53/".VERSION."upload-main/" );
// define("UPLOAD_DIR","D:\www\\".VERSION."upload-main\\" );

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
// css files
$md_css=ROOT_PATH ."/vendor/materialize/css/materialize.css";
$bootstrap=ROOT_PATH ."/vendor/bootstrap/css/bootstrap.css";

$awesome=ROOT_PATH . "/vendor/fontawesome/css/font-awesome.min.css";
$tweakcss=ROOT_PATH ."/public/css/main.css";
$timelinecss=ROOT_PATH ."/public/css/timeline.css";
$w3c=ROOT_PATH ."/vendor/w3c/w3c.css";
$nav=ROOT_PATH ."/public/css/nav.css";





// $matchanges=ROOT_PATH ."/public/css/matchanges.css";

//js files
$jquery=ROOT_PATH."/vendor/jquery/jquery-3.2.1.js";
$md_js=ROOT_PATH."/vendor/materialize/js/materialize.js";
$main_js=ROOT_PATH."/public/js/main.js";
$dashboard_js=ROOT_PATH."/public/js/dashboard-select.js";
$sorttable_js=ROOT_PATH."/public/js/sorttable.js";


//directories
$img=ROOT_PATH."/public/img/";


//_______________________________________________________
//pages
// ???
$contact=ROOT_PATH."/public/mag/contact.php?";
$dashboard=ROOT_PATH."/public/btlec/dashboard.php?";
$map=ROOT_PATH."/public/mag/google-map.php";
$mail_form=ROOT_PATH."/public/form/mail.php";
$histoMagVersBt=ROOT_PATH."/public/btlec/histo.php";










