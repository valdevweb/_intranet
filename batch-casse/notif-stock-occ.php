<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/CrudDao.php';



$db=new Db();

$pdoQlik=$db->getPdo('qlik');


$qlikCrud=new CrudDao($pdoQlik);

$nbPaletteNow=$qlikCrud->getAll("palettes4919");
$nbPaletteNow=count($nbPaletteNow);
$nbPaletteYesterday=$qlikCrud->getAll("palettes4919_veille");
$nbPaletteYesterday=count($nbPaletteYesterday);

if($nbPaletteNow!= $nbPaletteYesterday){
	$diff=$nbPaletteNow-$nbPaletteYesterday;
	if($diff>0){
		if(VERSION=="_"){
			$dest=['valerie.montusclat@btlecest.leclerc'];
			$cc=[];
		}else{
			$dest=["jonathan.domange@btlecest.leclerc"];
			$cc=["nathalie.pazik@btlecest.leclerc", "christelle.trousset@btlecest.leclerc"];
		}
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);

		$htmlMail = file_get_contents(DIR_SITE.'batch-casse/mail-occ.html');

		$htmlMail=str_replace('{NB}',$nbPaletteNow,$htmlMail);
		$subject='Portail BTLec Est - information palettes en stock 4919';
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest)
		->setCc($cc);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[]="mail envoyé avec succés";
		}

	}

	$qlikCrud->deleteTable("palettes4919_veille");
	$qlikCrud->copyTable("palettes4919", "palettes4919_veille");

}

