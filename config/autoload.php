<?php
// la variable okko permet de verifier si on travaille sur la bonne version avec la bonne connexion à la db
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
define("UPLOAD_DIR","http://172.30.92.53/".VERSION."upload" );
define("DIR_UPLOAD","D:\www\\".VERSION."intranet\upload\\" );
define("PORTAIL_SAV_HOME","http://172.30.92.53/".VERSION."sav/scapsav/home.php" );
define("PORTAIL_SAV","http://172.30.92.53/".VERSION."sav/" );
define("DIR_LOGFILES", "D:\www\batch_log\\");
define("DIR_LOGFILES_CONSULT", "http://172.30.92.53/batchlog/");




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
$pdoUser=connectToDb('web_users');
$pdoStat= connectToDb('stats');
$pdoQlik= connectToDb('qlik');
$pdoExploit= connectToDb('exploit');

// dev
$pdoBt=connectToDb(VERSION.'btlec');
$pdoMag=connectToDb(VERSION.'magasin');
$pdoSav=connectToDb(VERSION.'sav');
$pdoLitige=connectToDb(VERSION.'litige');
$pdoCasse=connectToDb(VERSION.'casse');
$pdoCm=connectToDb(VERSION.'cm');
$pdoEvo=connectToDb(VERSION.'evo');





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










