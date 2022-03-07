<?php
$dest=[];
$hiddenAr=[];
$replyTo=[];
$replyToArray= UserHelpers::getLdGt($pdoUser, $prod['gt']);
foreach ($replyToArray as $ldgt) {
	$replyTo[]=$ldgt['ld'];
}
foreach ($emails as $mail) {
	$dest[]=$mail['email'];
}
if(VERSION=="_"){
	echo "version de dev";
	$destStr=implode(", ", $dest);
	$dest=[MYMAIL];
}else{
	if ($_GET['dest']=="me" ) {
		echo "version de prod, à moi ";
		$destStr=implode(", ", $dest);
		$userEmail= UserHelpers::getInternUser($pdoUser, $_SESSION['id_web_user']);
		if(filter_var($userEmail['email'], FILTER_VALIDATE_EMAIL)){
			$dest=[$userEmail['email']];
		}else{
			echo "votre compte n'est associé à aucune adresse mail. L'envoi des relances est impossible";
			exit;
		}
		$hiddenAr=[MYMAIL];
	}elseif($_GET['dest']=="fou"){
		echo "version de prod, au fou ";
		$destStr="";
		$hiddenAr=$replyTo;
	}
}



$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);

$htmlMail = file_get_contents('..\mail\achats-relances-cdes-op.html');
$htmlMail=str_replace('{LIGNE}',$ligne,$htmlMail);
$htmlMail=str_replace('{CMT}',$relance['cmt'],$htmlMail);

$htmlMail=str_replace('{DEST}',$destStr,$htmlMail);
$htmlMail=str_replace('{REPLYTO}',implode(",",$replyTo),$htmlMail);

$subject='BTLec EST - '.$prod['fournisseur'].' relance livraison opérations sans rendez-vous';
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EMAIL_NEPASREPONDRE)
->setTo($dest)
->setBcc($hiddenAr);

if (!$mailer->send($message, $failures)){
	print_r($failures);
	$errors[]="erreur d'envoi du mail";
}else{
	$success[]="mail envoyé avec succés";
	$cdesRelancesDao->updateRelance($relance['id']);
}

