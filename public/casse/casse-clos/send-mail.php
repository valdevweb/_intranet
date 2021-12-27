<?php

if(VERSION=='_'){
	$to=['valerie.montusclat@btlecest.leclerc']	;
	$cc=[];
	$bcc='';
}else{
	$to=['isabelle.richard@btlecest.leclerc','clement.anciaux@btlecest.leclerc', 'sandie.lejeune@btlecest.leclerc']	;
	$cc=['christelle.trousset@btlecest.leclerc','nathalie.pazik@btlecest.leclerc','luc.muller@btlecest.leclerc'];

}

if ($infoExp['id_affectation']==1) {
	$htmlMail = file_get_contents('mail/mail-compta-mag.html');
	$htmlMail=str_replace('{MAG}',$mag,$htmlMail);
}else{
	$htmlMail = file_get_contents('mail/mail-compta-occ.html');
}


$htmlMail=str_replace('{FAC}',$infoExp['mt_fac'],$htmlMail);
$htmlMail=str_replace('{BLANC}',$infoExp['mt_blanc'],$htmlMail);
$htmlMail=str_replace('{BRUN}',$infoExp['mt_brun'],$htmlMail);
$htmlMail=str_replace('{GRIS}',$infoExp['mt_gris'],$htmlMail);
$subject='Portail BTLEC - facturation casse';
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);

if ($infoExp['id_affectation']==1) {

	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(EXPEDITEUR_MAIL)
	->setTo($to)
	->setCc($cc)
	->attach(Swift_Attachment::fromPath($dirUpload.$file));
}else{

	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(EXPEDITEUR_MAIL)
	->setTo($to)
	->setCc($cc);
}


if (!$mailer->send($message, $failures)){
	print_r($failures);
	exit;
}