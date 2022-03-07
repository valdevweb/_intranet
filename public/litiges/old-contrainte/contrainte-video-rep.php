<?php

$msg=getActionMsg($pdoLitige);

if(VERSION=='_'){
	$dest=MYMAIL;
}
else{
	$dest=[EMAIL_LITIGES];
}
$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-detail-litige.php?id='.$litige[0]['id_main'].'"> cliquez ici</a>';

$htmlMail = file_get_contents('mail/mail-rep-video.php');
$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);
$subject='Portail BTLec EST - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'] .' - VIDEO réponse';
$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')

->setFrom(EMAIL_NEPASREPONDRE)
->setTo($dest);

$delivered=$mailer->send($message);

if($delivered !=0)
{
	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

}
else
{
	$errors[]="impossible d'envoyer le mail";
}