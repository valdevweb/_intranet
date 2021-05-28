<?php
$dest=[];
$hiddenAr=[];
$replyTo=[];
$replyToArray= UserHelpers::getLdGt($pdoUser, $prod['gt']);

foreach ($emails as $mail) {
	$dest[]=$mail['email'];
}
if(VERSION=="_"){
	$destStr=implode(", ", $dest);
	$dest=['valerie.montusclat@btlec.fr'];
}else{
	if ($_GET['dest']=="me" ) {
		$destStr=implode(", ", $dest);
		$userEmail= UserHelpers::getInternUser($pdoUser, $_SESSION['id_web_user']);
		if(filter_var($userEmail['email'], FILTER_VALIDATE_EMAIL)){
			$dest=[$userEmail['email']];
		}else{
			echo "votre compte n'est associé à aucune adresse mail. L'envoi des relances est impossible";
			exit;
		}
		$hiddenAr=['valerie.montusclat@btlec.fr'];
	}elseif($_GET['dest']=="fou"){
		$destStr="";
		$hiddenAr=$replyTo;
	}

}


$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);

$htmlMail = file_get_contents('..\mail\achats-relances-cdes-perm.html');
$htmlMail=str_replace('{LIGNE}',$ligne,$htmlMail);
$htmlMail=str_replace('{CMT}',$relance['cmt'],$htmlMail);
$htmlMail=str_replace('{REPLYTO}',implode(",",$replyTo),$htmlMail);
$htmlMail=str_replace('{DEST}',$destStr,$htmlMail);


$subject='BTLec EST - '.$prod['fournisseur'].' relance livraison permanent sans rendez-vous';
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'BTLec Est'))
->setTo($dest)
->setBcc($hiddenAr);

if (!$mailer->send($message, $failures)){
	print_r($failures);
	$errors[]="erreur d'envoi du mail";
}else{
	$success[]="mail envoyé avec succés";
	$cdesRelancesDao->updateRelance($relance['id']);
}

