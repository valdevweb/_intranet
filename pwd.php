<?php
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path))
{
	require "config/_pdo_connect.php";
	define("SITE_ADDRESS", "http://172.30.92.53/_btlecest");

}
else {
	require "config/pdo_connect.php";
	define("SITE_ADDRESS", "http://172.30.92.53/btlecest");

}
include "pwd-ct.php";
require 'functions/mail.fn.php';

function addMsg($pdoBt,$id,$rbt,$mag)
{
	$inc_file="";
	$req=$pdoBt->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat,inc_file,who,email)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :inc_file, :who, :email)');
	$req->execute(array(
		':objet'		=> "demande d'identifiants",
		':msg'			=> "demande d'identifiants de connexion au portail BTLec",
		':id_mag'		=> $id,
		':id_service'	=> 7,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "en attente de réponse",
		':inc_file'		=>$inc_file,
		':who'			=>$mag,
		':email'		=>$rbt,
	));
	$req->fetch(PDO::FETCH_ASSOC);
	return $pdoBt->lastInsertId();
}


$errors=[];
$success=[];

if(isset($_POST['submit'])){
	extract($_POST);
	if(isset($centrale) && isset($mag))
	{

	//form complet
		$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE mag= :mag");
		$req->execute(array(
			":mag" =>$mag
		));
		if($sca=$req->fetch(PDO::FETCH_ASSOC))
			{
				$req=$pdoUser->prepare("SELECT * FROM users WHERE galec= :galec");
				$req->execute(array(
					":galec"	=>$sca['galec']
				));
				$codeBt=$sca['btlec'];
		 // $rbt=$codeBt."-RBT";
				$rbt="valerie.montusclat@btlec.fr";
				$webuser=$req->fetch(PDO::FETCH_ASSOC);
				// ----------------------------------------
				// si le mot de passe en clair existe déjà
				// ---------------------------------------
				if($webuser['nohash_pwd'] !="")
				{
		 	// echo "envoi email avec mdp " . $result['nohash_pwd'];
					$link="";
		 	// $to="valerie.montusclat@btlec.fr";
					$tplIdent='public/mail/envoi_identifiant.tpl.html';
					$subject="PORTAIL BTLEC Est - Vos identifiants de connexion";
					if(sendMail($rbt,$subject,$tplIdent,$webuser['login'],$webuser['nohash_pwd'], $link))
					{
						$success[]="mail envoyé avec succès";
					}
					else
					{
						$errors[]="erreur d'envoi du mail";
					}
				}
				else
				{
					$id=$webuser['id'];
					$idMsg=addMsg($pdoBt,$id,$rbt,$mag);
					echo "id du message sur le portail " . $idMsg;
					$mailtoInfo="valerie.montusclat@btlec.fr";
					$subject="PORTAIL BTLEC Est - demande d'identifiants - magasin " . $mag;
					$tplIdent='public/mail/demande_identifiants.tpl.html';
					$content="";
					$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$idMsg."'>ici pour consulter la demande</a>";

					if(sendMail($mailtoInfo,$subject,$tplIdent,$mag,$content, $link))
					{
						$success[]="demande envoyée avec succès";
					}
					else
					{
						$errors[]="erreur d'envoi du mail";
					}

				}


			}
			else
			{
				$errors[]="erreur - magasin non trouvé";
			}

			// include ('public/view/_errors.php');



		}
	}


