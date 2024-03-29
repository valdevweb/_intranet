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
define("DIR_IMPORT_GESSICA","D:\btlec\dumps\gessica\\");
define("DIR_IMPORT_DUMP","D:\btlec\dumps\\");
define("DIR_SITE","D:\www\\".VERSION."intranet\\".VERSION."btlecest\\");
define("CM_UPLOAD_URL","http://".SERVER_NAME."/".VERSION."upload-main/cm/" );
define("ANDROID_UPLOAD","http://".SERVER_NAME."/".VERSION."android/upload/" );


define("PDF_FOOTER", '<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>');
define("PDF_FOOTER_PAGE", '<table class="padding-table full-width"><tr><td class="footer ">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td><td>{PAGENO}{nbpg}</td></tr></table>');
$okko= 'version : ' . ROOT_PATH;


define("FR_DAYS", ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi']);
define("FR_DAYS_SHORT", ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam']);
define("FR_MONTHS", ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre']);
define("FR_MONTHS_SHORT", ['', 'janv', 'fév', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc']);


define("SMTP_ADDRESS", "relay-smtp.informadis.com");

// remplace btlecest.portailweb.informatique@btlec.fr
define("EMAIL_INFORMATIQUE", 'ga-btlecest-portailweb-informatique@btlecest.leclerc');
define("MYMAIL", "valerie.montusclat@btlecest.leclerc");
define("EMAIL_PILOTAGE_PREPA", 'pilotes.preparations@btlecest.leclerc');
// btlecest.portailweb.gazettes@btlec.fr
define("EMAIL_GAZETTE", 'ga-btlecest-portailweb-gazettes@btlecest.leclerc');
//btlecest.portailweb.litiges@btlec.fr
define("EMAIL_LITIGES", 'ga-btlecest-portailweb-litiges@btlecest.leclerc');
//salonbtlecest@btlec.fr
define("EMAIL_SALON", 'salons@btlecest.leclerc');

define("EMAIL_NEPASREPONDRE", array('ne_pas_repondre@btlecest.leclerc' => 'Portail BTLec EST'));
define("LD_DIR",['luc.muller@btlecest.leclerc', 'david.syllebranque@btlecest.leclerc']);
define("LD_OCCASION",['jonathan.domange@btlecest.leclerc', 'stephane.wendling@btlecest.leclerc', 'luc.muller@btlecest.leclerc']);