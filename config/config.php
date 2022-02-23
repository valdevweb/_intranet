<?php
define("SERVER_NAME", "172.30.92.53");
// on définit manuellement car lancé par batch, php n'a pas de variable de session server_name
if (preg_match('/_btlecest/', dirname(__FILE__))){
	$version='_';
	define("VERSION",'_');
	define("PORTAIL_FOU","http://".SERVER_NAME."/".VERSION."portail-fournisseurs/" );
	define("PORTAIL_SALON","http://".SERVER_NAME."/".VERSION."salon-btlec/" );
}else{
	$version='';
	define("VERSION",'');
	define("PORTAIL_FOU","http://159.180.231.226:8000/".VERSION."portail-fournisseurs/" );
	define("PORTAIL_SALON","http://159.180.231.226:8000/".VERSION."salon-btlec/" );
}
define("CONSEIL", "http://".SERVER_NAME."/".VERSION."conseil/");
define("DIR_UPLOAD","D:\www\\".VERSION."upload-main\\btlecest\\" );

define("PORTAIL","Portail BTlec" );
define("PORTAIL_CM","http://".SERVER_NAME."/".VERSION."cm/" );
define("PORTAIL_SAV","http://".SERVER_NAME."/".VERSION."sav/" );
define("ROOT_PATH","/".VERSION."btlecest");
define("SITE_ADDRESS", "http://".SERVER_NAME."/".VERSION."btlecest");
define("SITE_DIR","D:\www\\".VERSION."intranet\\".VERSION."btlecest\\" );

define("URL_UPLOAD","http://".SERVER_NAME."/".VERSION."upload-main/btlecest/" );


define("EXPEDITEUR_MAIL", array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'));
define("MYMAIL", "valerie.montusclat@btlecest.leclerc");
define("SMTP_INFORMADIS", "relay-smtp.informadis.com");



define("PDF_FOOTER", '<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>');
define("PDF_FOOTER_PAGE", '<table class="padding-table full-width"><tr><td class="footer ">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td><td>{PAGENO}{nbpg}</td></tr></table>');


define("FR_DAYS", ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi']);
define("FR_DAYS_SHORT", ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam']);
define("FR_MONTHS", ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre']);
define("FR_MONTHS_SHORT", ['', 'janv', 'fév', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc']);



$cssFile=ROOT_PATH ."/public/css/".str_replace('php','css', basename($_SERVER['PHP_SELF']) );

require SITE_DIR.'Class\Db.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
