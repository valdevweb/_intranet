<?php

if (preg_match('/_btlecest/', dirname(__FILE__)))
{
	define("VERSION",'_');
}
else
{
	define("VERSION",'');
}

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}
$dbCm=VERSION."cm";
$pdoCm=connectToDb($dbCm);
$pdoUser=connectToDb('web_users');
require_once  'D:\www\\'.VERSION.'btlecest\vendor\autoload.php';


function getToImport($pdoUser, $sens){
	$req=$pdoUser->prepare("SELECT * FROM lotus_histo WHERE added= :added AND cm=0");
	$req->execute([
		':added'=>$sens
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEntries($pdoCm,$email, $ldFull){
	$req=$pdoCm->prepare("DELETE FROM mag_email WHERE email= :email AND ld_full = :ld_full");
	$req->execute([
		':ld_full'	=>$ldFull,
		':email'	=>$email

	]);
	return $req->rowCount();
}


function addEntries($pdoCm,$email, $ldFull, $galec){

	$suffixe=substr($ldFull,strlen($ldFull)-4,4);
	$ldShort=substr($ldFull,0,strlen($ldFull)-4);

	$req=$pdoCm->prepare("INSERT INTO mag_email (ld_full, ld_short, ld_suffixe, email, galec) VALUES (:ld_full, :ld_short, :ld_suffixe, :email, :galec)");
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


function majHisto($pdoUser, $idHisto){
	$req=$pdoUser->prepare("UPDATE lotus_histo SET cm=1 WHERE id= :id");
	$req->execute([
		':id'		=>$idHisto
	]);
	return $req->rowCount();
}

$toAdd=getToImport($pdoUser,1);

$toDelete=getToImport($pdoUser,0);
$addedStrg="";
$deletedStrg="";
$errorStrg="";
foreach ($toDelete as $key => $value) {
	$row=deleteEntries($pdoCm, $value['email'], $value['ld_full']);

	if($row==1){
		majHisto($pdoUser, $value['id']);
		echo "sup " .$value['email'];
		$deletedStrg.=$value['ld_full'] . " suppression de " .$value['email']. "<br>";

	}else{
		$errorStrg.=$value['ld_full'] . " impossible de supprimer " .$value['email']. "<br>";

	}
}




foreach ($toAdd as $key => $value) {

	$row=addEntries($pdoCm, $value['email'], $value['ld_full'], $value['galec']);

	if($row==1){
		majHisto($pdoUser, $value['id']);
		$addedStrg.=$value['ld_full'] . " ajout de " .$value['email']. "<br>";


	}else{
		$errorStrg.=$value['ld_full'] . " impossible d'ajouter " .$value['email']. "<br>";
	}
}
$htmlMail = file_get_contents('mail-lotus-maj.html');
$htmlMail=str_replace('{ADDED}',$prod,$addedStrg);
$htmlMail=str_replace('{DELETED}',$prod,$deletedStrg);
$htmlMail=str_replace('{ERRORS}',$prod,$errorStrg);
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