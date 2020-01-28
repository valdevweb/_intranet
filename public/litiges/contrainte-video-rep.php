<?php

$msg=getActionMsg($pdoLitige);

if(VERSION=='_'){
	$dest='valerie.montusclat@btlec.fr';
}
else{
	$dest='btlecest.portailweb.logistique@btlec.fr';
}
$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-detail-litige.php?id='.$litige[0]['id_main'].'"> cliquez ici</a>';

$htmlMail = file_get_contents('mail-rep-video.php');
$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);
$subject='Portail BTLec EST - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'] .' - VIDEO réponse';
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')

->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'))
->setTo($dest)
		// ->addCc('btlecest.portailweb.logistique@btlec.fr')
		// ->addCc('valerie.montusclat@btlec.fr')
->addBcc('valerie.montusclat@btlec.fr');
$delivered=$mailer->send($message);

if($delivered !=0)
{
	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

}
else
{
	$errors[]="impossible d'envoyer le mail";
}