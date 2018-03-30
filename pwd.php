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




if(isset($_POST['submit'])){
	// $errors=[];
	// $success=[];
	$redir="";
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
				//faire test sur 4920
		 		$rbt=$codeBt."-RBT@btlec.fr";
				// $rbt="valerie.montusclat@btlec.fr";
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
						$redir="pwd.php?success=1";
						header('Location:'.$redir);

					}
					else
					{
						//err mail
						$redir="pwd.php?error=1";
						header('Location:'.$redir);

					}
				}
				else
				{
					$id=$webuser['id'];
					$idMsg=addMsg($pdoBt,$id,$rbt,$mag);
					$mailtoInfo="btlecest.portailweb.informatique@btlec.fr";
					// $mailtoInfo="valerie.montusclat@btlec.fr";
					$subject="PORTAIL BTLEC Est - demande d'identifiants - magasin " . $mag;
					$tplIdent='public/mail/demande_identifiants.tpl.html';
					$content="";
					$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$idMsg."'>ici pour consulter la demande</a>";

					if(sendMail($mailtoInfo,$subject,$tplIdent,$mag,$content, $link))
					{
						// $success[]="Une demande a été automatiquement envoyée au service informatique pour que votre mot de passe soit communiqué à la (aux) personne(s) qui figure(nt) dans la liste RBT du magasin";
						$redir="pwd.php?success=2";
						header('Location:'.$redir);

					}
					else
					{
						//err mail
						$redir="pwd.php?error=1";
						header('Location:'.$redir);
					}

				}


			}
			else
			{
				$redir="pwd.php?error=2";
				header('Location:'.$redir);
			}

			// include ('public/view/_errors.php');



		}
	}


