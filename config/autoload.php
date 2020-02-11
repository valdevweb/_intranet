<?php
// la variable okko permet de verifier si on travaille sur la bonne version avec la bonne connexion à la db
session_start();
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path))
{
	define("ROOT_PATH","/_btlecest");
	define("SITE_ADDRESS", "http://172.30.92.53/_btlecest");
	$pdo_file='_pdo_connect.php';
	$version='_';
	define("VERSION",'_');
	define("PORTAIL","Portail BTlec - dev" );
	define("UPLOAD_DIR","http://172.30.92.53/_upload" );
	define("PORTAIL_SAV_HOME","http://172.30.92.53/_sav/scapsav/home.php" );
	define("PORTAIL_SAV","http://172.30.92.53/_sav/" );


}
else
{
	define("ROOT_PATH","/btlecest");
	define("SITE_ADDRESS", "http://172.30.92.53/btlecest");
	$pdo_file='pdo_connect.php';
	$version='';
	define("PORTAIL","Portail BTlec" );
	define("VERSION",'');
	define("UPLOAD_DIR","http://172.30.92.53/upload" );
	define("PORTAIL_SAV_HOME","http://172.30.92.53/sav/scapsav/home.php" );
	define("PORTAIL_SAV","http://172.30.92.53/sav/" );


}

define("PDF_FOOTER", '<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>');
define("PDF_FOOTER_PAGE", '<table class="padding-table full-width"><tr><td class="footer ">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td><td>{PAGENO}{nbpg}</td></tr></table>');


require_once $pdo_file;
$okko= 'version : ' . ROOT_PATH  .', db  : '.$pdo_file;


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










