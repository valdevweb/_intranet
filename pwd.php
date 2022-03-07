<?php
//----------------------------------------------------
// CONNEXION  DB
//----------------------------------------------------
$path=dirname(__FILE__);
require "config/config.inc.php";
require 'config/db-connect.php';



//----------------------------------------------------
// INCLUDES
//----------------------------------------------------

require 'functions/stats.fn.php';
include 'Class/mag/MagDao.php';
require 'vendor/autoload.php';
//----------------------------------------------------
// STATS
//----------------------------------------------------
$page=basename(__file__);
$action="demande d'identifiants de connexion";
$magDbHelper=new MagDao($pdoMag);
$centraleList=$magDbHelper->getDistinctCentraleMag();



//----------------------------------------------------
// DATA
//----------------------------------------------------
function addMsg($pdoBt,$idwebuser,$mail,$deno,$btlec){
	$inc_file="";
	$req=$pdoBt->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat, who, email, id_galec, code_bt)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :who, :email,  :id_galec, :code_bt)');
	$req->execute(array(
		':objet'		=> "demande d'identifiants",
		':msg'			=> "demande d'identifiants de connexion au portail BTLec",
		':id_mag'		=> $idwebuser,
		':id_service'	=> 7,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "en attente de réponse",
		':who'			=>$deno,
		':email'		=>$mail,
		':id_galec'		=>$_POST['galec'],
		':code_bt'		=>$btlec
	));
	$req->fetch(PDO::FETCH_ASSOC);
	return $pdoBt->lastInsertId();
}


function findUser($pdoUser){
	$req=$pdoUser->prepare("SELECT * FROM users WHERE galec= :galec AND (type='mag' OR type ='centrale')");
	$req->execute(array(
		":galec"	=>$_POST['galec'],
					// ":mag"		=>"mag"
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getMagInfo($pdoMag){
	$req=$pdoMag->prepare("SELECT * FROM mag WHERE galec LIKE :galec");
	$req->execute(array(
		":galec" =>$_POST['galec']
	));

		//si pano galec trouvé, le recherche dans table users
	return $req->fetch(PDO::FETCH_ASSOC);
}

$redir="";
$errors=[];
$success=[];
//  récup les infos des tables web_users/users et magasin/mag
if(isset($_POST['galec'])){
	$magInfo=getMagInfo($pdoMag);
	$webuser=findUser($pdoUser);
}
// cas 1 : le magasin n'a pas de compte sur le portail : envoi mail à moi même, copie Clément et David
if(isset($_POST['galec']) && empty($webuser)){

	$dest=['valerie.montusclat@btlecest.Leclerc'];
	$htmlMail = file_get_contents('public/mail/create-mag-login.html');
	$htmlMail=str_replace('{GALEC}',$magInfo['galec'],$htmlMail);
	$htmlMail=str_replace('{DENO}',$magInfo['deno'],$htmlMail);
	$subject="PORTAIL BTLEC Est - Création de compte magasin";
	$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(EMAIL_NEPASREPONDRE)
	->setTo($dest);

	if (!$mailer->send($message, $failures)){
		print_r($failures);
		$redir="pwd.php?error=1";

	}else{
		$redir="pwd.php?success=3";
		header('Location:'.$redir);
	}




}
// cas 2 : le mag a un compte
if(isset($_POST['galec']) && !empty($webuser)){

	if(VERSION=='_'){
		$ldRbt=MYMAIL;
		$hiddenAr=[];
	}else{
		$ldRbt='ga-btlecest-'.$magInfo['id']."-rbt@btlecest.leclerc";
		$hiddenAr=[MYMAIL];

	}
	// si le mot de passe en clair existe
	if($webuser['nohash_pwd'] !=""){


		$htmlMail = file_get_contents('public/mail/envoi_identifiant.tpl.html');
		$htmlMail=str_replace('{LOGIN}',$webuser['login'],$htmlMail);
		$htmlMail=str_replace('{PWD}',$webuser['nohash_pwd'],$htmlMail);
		$subject="PORTAIL BTLEC Est - Vos identifiants de connexion";
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($ldRbt)
		->setBcc($hiddenAr);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
			$redir="pwd.php?error=1";

		}else{
			$redir="pwd.php?success=1";
			$descr="envoi identifiants par mail";
			pwdStat($pdoStat,$webuser['login'],$page, $action, $descr,VERSION);
			header('Location:'.$redir);
		}
	}
	else{
		if(VERSION=='_'){
			$mailtoInfo=MYMAIL;

		}else{
			$mailtoInfo=EMAIL_INFORMATIQUE;

		}
		// création d'une demande sur le portail
		$idMsg=addMsg($pdoBt,$webuser['id'],$ldRbt,$magInfo['deno'], $magInfo['id']);
		$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$idMsg."'>ici pour consulter la demande</a>";

		$htmlMail = file_get_contents('public/mail/demande_identifiants.tpl.html');
		$htmlMail=str_replace('{DENO}',$magInfo['deno'],$htmlMail);
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);

		$subject="PORTAIL BTLEC Est - demande d'identifiants - magasin " . $magInfo['deno'];


		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($mailtoInfo);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
			$redir="pwd.php?error=1";
			header('Location:'.$redir);

		}else{
			$descr="création d'une demande identifiants sur le portail";
			pwdStat($pdoStat,$webuser['login'],$page, $action, $descr, $version);
			$redir="pwd.php?success=2";
			header('Location:'.$redir);
		}
	}

}

include "pwd-ct.php";

