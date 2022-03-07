<?php 
$cc=[EMAIL_LITIGES];
if(VERSION=='_'){
	$dest=[MYMAIL];
	$cc=[];
}

$htmlMail = file_get_contents($mailFile);
$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
$htmlMail=str_replace('{MSG}',nl2br($actionLitige['libelle']),$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);


$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $pdfFilename, 'application/pdf');

$message = (new Swift_Message($subjet))
->setBody($htmlMail, 'text/html')
->setFrom(EMAIL_NEPASREPONDRE)
->setTo($dest)
->setCc($cc);



$delivered=$mailer->send($message);

if($delivered !=0){
	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
}
else{
	$errors[]="impossible d'envoyer le mail";

}