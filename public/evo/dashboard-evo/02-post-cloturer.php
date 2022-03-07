<?php
$idEvo=$_POST['id_evo'];

$up=$evoDao->endEvo();


$thisEvo=$evoDao->getThisEvo($idEvo);
$arDevName=EvoHelpers::arrayRespName($pdoEvo);
$affectation=$affectationDao->getAffectation($idEvo);
$destAffectation=[];
$destDemandeur=[];
$destDemandeurAndAffectation=[];

	//  envoi mail dev et demandeur
if(VERSION=="_"){
	$destSuperviseur=[MYMAIL];
	$destDemandeurAndAffectation=[MYMAIL];
	$hidden=[];
}else{
	if($thisEvo['id_resp']==2){
		$destSuperviseur=['luc.muller@btlecest.leclerc'];

	}else{
		$destSuperviseur=LD_DIR;
	}
	if(!empty($affectation)){
		foreach ($affectation as $key => $affect) {
			$destAffectation[]=$affect['email'];
		}
	}

	$destDemandeur[]=$thisEvo['mail_dd'];
	$destDemandeurAndAffectation=array_merge($destDemandeur, $destAffectation);
	$destDemandeurAndAffectation=array_unique($destDemandeurAndAffectation);
	$hidden=[MYMAIL];

	// echo "<pre>";
	// print_r($destDemandeur);
	// print_r($destAffectation);
	// print_r($destDemandeurAndAffectation);
	// echo '</pre>';
}


// ---------------------------------------
// 		MAIL  developpeur + superviseur
// ---------------------------------------
$htmlMail = file_get_contents('mail/cloture-resp.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DEVNAME}',$arDevName[$thisEvo['id_resp']],$htmlMail);
$htmlMail=str_replace('{CMTRESP}',$_POST['cmt_resp'],$htmlMail);
$htmlMail=str_replace('{WHAT}',$thisEvo['plateforme']. ' - ' .$thisEvo['appli'].' - ' .$thisEvo['module'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo - clôture" ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EXPEDITEUR_MAIL)
->setTo($destSuperviseur)
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


$htmlMail = file_get_contents('mail/cloture-dd.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DEVNAME}',$arDevName[$thisEvo['id_resp']],$htmlMail);
$htmlMail=str_replace('{CMTDD}',$_POST['cmt_dd'],$htmlMail);
$htmlMail=str_replace('{WHAT}',$thisEvo['plateforme']. ' - ' .$thisEvo['appli'].' - ' .$thisEvo['module'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo - clôture" ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EXPEDITEUR_MAIL)
->setTo($destDemandeurAndAffectation)
->setBcc($hidden);

if (!$mailer->send($message, $failures)){
	print_r($failures);
	$errors[]="erreur envoi mail";
}else{
	$success[]="mail envoyé avec succés";
}

if(empty($errors)){
	if(isset($_GET['id'])){
		$successQ='?id='.$_GET['id'].'&success=over';

	}else{
		$successQ='?success=over';
	}
	unset($_POST);

	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}