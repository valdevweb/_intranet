<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
	set_include_path("D:\www\_intranet\_btlecest\\");
} else {
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/CrudDao.php';



$db = new Db();

$pdoQlik = $db->getPdo('qlik');
$pdoCasse = $db->getPdo('casse');

$qlikCrud = new CrudDao($pdoQlik);

$paletteNow = $qlikCrud->getAll("palettes4919");
$nbPaletteNow = count($paletteNow);
$nbPaletteYesterday = $qlikCrud->getAll("palettes4919_veille");
$nbPaletteYesterday = count($nbPaletteYesterday);






// on récup la liste des palettes présente sur palettes4919 et absente de palettes4919_veille


$req = $pdoQlik->query("SELECT palettes4919.* FROM palettes4919 left join palettes4919_veille on palettes4919.NumeroPalette=palettes4919_veille.NumeroPalette where palettes4919_veille.NumeroPalette is null");

$newPalettes = $req->fetchAll(PDO::FETCH_ASSOC);


if (!empty($newPalettes)) {
	// on parcourt la liste des nouvelles palettes pour passer leur statut à 1 (en stock) dans la table casse.palettes 
	// attention , on ne le fait que pour les palettes en statut 0, on veut conserver statut bloqué et palettes affectées
	foreach ($newPalettes as $key => $palette) {
		$req = $pdoCasse->query("UPDATE palettes set statut=1 where statut=0 and palette like '{$palette['NumeroPalette']}'");
	}

	if (VERSION == "_") {
		$dest = ['valerie.montusclat@btlecest.leclerc'];
		$cc = [];
	} else {
		$dest = ["jonathan.domange@btlecest.leclerc"];
		$cc = ["nathalie.pazik@btlecest.leclerc", "christelle.trousset@btlecest.leclerc"];
	}
	$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
	$mailer = new Swift_Mailer($transport);

	$htmlMail = file_get_contents(DIR_SITE . 'batch-casse/mail-occ.html');

	$htmlMail = str_replace('{NB}', $nbPaletteNow, $htmlMail);
	$subject = 'Portail BTLec Est - information palettes en stock 4919';
	$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest)
		->setCc($cc);

	if (!$mailer->send($message, $failures)) {
		print_r($failures);
	} else {
		$success[] = "mail envoyé avec succés";
	}

	$qlikCrud->deleteTable("palettes4919_veille");
	$qlikCrud->copyTable("palettes4919", "palettes4919_veille");
}
