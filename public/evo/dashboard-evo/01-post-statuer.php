<?php
$err=$evoDao->statuer($pdoEvo);
	//besoin de récupérer les infos de la demande d'évo
$idEvo=$_POST['id_evo'];
$thisEvo=$evoDao->getThisEvo($idEvo);
	//  envoi mail dev et demandeur
if(VERSION=="_"){
	$destDev=['valerie.montusclat@btlec.fr'];
	$destDd=['valerie.montusclat@btlec.fr'];
	$cc=[];
	$hidden=[];
}else{
	$devMail=$arrDevMail[$thisEvo['id_resp']];
	$destDev=[$devMail, 'luc.muller@btlec.fr', 'david.syllebranque@btlec.fr'];
	$destDev=array_unique($destDev);
	$destDd[]=$thisEvo['mail_dd'];
	$cc=[];
	$hidden=['valerie.montusclat@btlec.fr'];
}
$arrDecision=[2 =>'<span style="color:yellowgreen; font-weight:bold">validée</span>', 5=>'<span style="color:crimson">refusée</span>'];
$decision=$arrDecision[$_POST['statut']];


// ---------------------------------------
		// MAIL  developpeur

// ---------------------------------------
$htmlMail = file_get_contents('mail/decision-dev.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DECISION}',$decision,$htmlMail);
$htmlMail=str_replace('{CMTDEV}',$thisEvo['cmt_dev'],$htmlMail);
$htmlMail=str_replace('{DEADLINE}',$thisEvo['deadlinefr'],$htmlMail);
$htmlMail=str_replace('{DDEUR}',$thisEvo['fullname_dd'],$htmlMail);
$htmlMail=str_replace('{WHAT}',$thisEvo['plateforme']. ' - ' .$thisEvo['appli'].' - ' .$thisEvo['module'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo " ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EXPEDITEUR_MAIL)
->setTo($destDev)
->setCc($cc)
->setBcc($hidden);

if (!$mailer->send($message, $failures)){
	print_r($failures);
	$errors[]="erreur envoi mail";
}else{
	$success[]="mail envoyé avec succés";
}
// ---------------------------------------
		// MAIL  dd

// ---------------------------------------


$htmlMail = file_get_contents('mail/decision-dd.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DECISION}',$decision,$htmlMail);
$htmlMail=str_replace('{CMTDD}',$thisEvo['cmt_dd'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo " ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EXPEDITEUR_MAIL)
->setTo($destDd)
->setCc($cc)
->setBcc($hidden);

if (!$mailer->send($message, $failures)){
	print_r($failures);
	$errors[]="erreur envoi mail";
}else{
	$success[]="mail envoyé avec succés";
}

if(empty($errors)){
	if(isset($_GET['id'])){
		$successQ='?id='.$_GET['id'].'&success=decision';
		echo "yes";

	}else{
		$successQ='?success=decision';
	}
	unset($_POST);
	// header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}