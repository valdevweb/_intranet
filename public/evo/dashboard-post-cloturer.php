<?php
$idEvo=$_POST['id_evo'];
// echo $idEvo;
// maj db
function endEvo($pdoEvo){
	$req=$pdoEvo->prepare("UPDATE evos SET cmt_end_dd= :cmt_end_dd, cmt_end_resp= :cmt_end_resp, date_end= :date_end, id_etat= :id_etat WHERE id= :id");
	$req->execute([
		':cmt_end_dd'		=>$_POST['cmt_dd'],
		':cmt_end_resp'		=>$_POST['cmt_resp'],
		':date_end'		=>date('Y-m-d H:i:s'),
		':id_etat'		=>4,
		':id'		=>$_POST['id_evo'],

	]);

	return $req->errorInfo();
	return $req->rowCount();

}

$up=endEvo($pdoEvo);

//besoin de récupérer les infos de la demande d'évo
$thisEvo=$evoMgr->getThisEvo($idEvo);
$arDevName=EvoHelpers::arrayRespName($pdoEvo);


	//  envoi mail dev et demandeur
if(VERSION=="_"){
	$destSuperviseur=['valerie.montusclat@btlec.fr'];
	$destDd=['valerie.montusclat@btlec.fr'];
	$cc=[];
	$hidden=[];
}else{
	// 2 =dsy
	if($thisEvo['id_resp']==2){
		$destSuperviseur=['luc.muller@btlec.fr'];

	}else{
		$destSuperviseur=['luc.muller@btlec.fr', 'david.syllebranque@btlec.fr'];
	}
	$destDd[]=$thisEvo['mail_dd'];
	$cc=[];
	$hidden=['valerie.montusclat@btlec.fr'];
}


// ---------------------------------------
		// MAIL  developpeur

// ---------------------------------------
$htmlMail = file_get_contents('mail-cloture-resp.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DEVNAME}',$arDevName[$thisEvo['id_resp']],$htmlMail);
$htmlMail=str_replace('{CMTRESP}',$_POST['cmt_resp'],$htmlMail);
$htmlMail=str_replace('{WHAT}',$thisEvo['plateforme']. ' - ' .$thisEvo['appli'].' - ' .$thisEvo['module'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo - clôture" ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
->setTo($destSuperviseur)
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


$htmlMail = file_get_contents('mail-cloture-dd.html');
$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
$htmlMail=str_replace('{DEVNAME}',$arDevName[$thisEvo['id_resp']],$htmlMail);
$htmlMail=str_replace('{CMTDD}',$_POST['cmt_dd'],$htmlMail);
$htmlMail=str_replace('{WHAT}',$thisEvo['plateforme']. ' - ' .$thisEvo['appli'].' - ' .$thisEvo['module'],$htmlMail);
$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
$subject="Portail BTLec Est - Demandes d'évo - clôture" ;

// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
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
	$successQ='?success=over';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}