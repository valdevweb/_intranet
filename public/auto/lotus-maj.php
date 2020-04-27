<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';


require_once  'vendor\autoload.php';


function getToImport($pdoMag, $sens){
	$req=$pdoMag->prepare("SELECT * FROM lotus_histo WHERE added= :added AND processed= 0");
	$req->execute([
		':added'=>$sens
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEntries($pdoMag,$email, $ldFull){
	$req=$pdoMag->prepare("DELETE FROM mag_email WHERE email= :email AND ld_full = :ld_full");
	$req->execute([
		':ld_full'	=>$ldFull,
		':email'	=>$email

	]);
	return $req->rowCount();
}


function addEntries($pdoMag,$email, $ldFull, $galec){

	$suffixe=substr($ldFull,strlen($ldFull)-4,4);
	$ldShort=substr($ldFull,0,strlen($ldFull)-4);

	$req=$pdoMag->prepare("INSERT INTO mag_email (ld_full, ld_short, ld_suffixe, email, galec) VALUES (:ld_full, :ld_short, :ld_suffixe, :email, :galec)");
	$req->execute([
		':ld_full'	=>$ldFull,
		':ld_short'	=>$ldShort,
		':ld_suffixe'=>$suffixe,
		':email'	=>$email,
		':galec'	=>$galec

	]);
	return $req->rowCount();
	// return $req->errorInfo();
}


function majHisto($pdoMag, $idHisto){
	$req=$pdoMag->prepare("UPDATE lotus_histo SET processed=1 WHERE id= :id");
	$req->execute([
		':id'		=>$idHisto
	]);
	return $req->rowCount();
}

$toAdd=getToImport($pdoMag,1);

$toDelete=getToImport($pdoMag,0);





$addedStrg="";
$deletedStrg="";
$errorStrg="";
foreach ($toDelete as $key => $value) {
	$row=deleteEntries($pdoMag, $value['email'], $value['ld_full']);

	if($row==1){
		majHisto($pdoMag, $value['id']);
		echo "sup " .$value['email'];
		$deletedStrg.=$value['ld_full'] . " suppression de " .$value['email']. "<br>";

	}else{
		$errorStrg.=$value['ld_full'] . " impossible de supprimer " .$value['email']. "<br>";

	}
}




foreach ($toAdd as $key => $value) {

	$row=addEntries($pdoMag, $value['email'], $value['ld_full'], $value['galec']);

	if($row==1){
		majHisto($pdoMag, $value['id']);
		$addedStrg.=$value['ld_full'] . " ajout de " .$value['email']. "<br>";


	}else{
		$errorStrg.=$value['ld_full'] . " impossible d'ajouter " .$value['email']. "<br>";
	}
}
$htmlMail = file_get_contents('mail-lotus-maj.html');
$htmlMail=str_replace('{ADDED}',$addedStrg,$htmlMail);
$htmlMail=str_replace('{DELETED}',$deletedStrg, $htmlMail);
$htmlMail=str_replace('{ERRORS}',$errorStrg, $htmlMail);
$subject='Admin Web - Lotus - maj des ld ';

// ---------------------------------------
// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'PORTAIL Admin'))
->setTo(array('valerie.montusclat@btlec.fr'));
// ->attach($attachmentPdf)
// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));


if (!$mailer->send($message, $failures)){
  print_r($failures);
}else{
  $success[]="mail envoyé avec succés";
}