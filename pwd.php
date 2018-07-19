<?php
//----------------------------------------------------
// CONNEXION  DB
//----------------------------------------------------
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path))
{
	require "config/_pdo_connect.php";
	define("SITE_ADDRESS", "http://172.30.92.53/_btlecest");
	$version="dev";

}
else {
	require "config/pdo_connect.php";
	define("SITE_ADDRESS", "http://172.30.92.53/btlecest");
	$version="prod";
}

//----------------------------------------------------
// INCLUDES
//----------------------------------------------------
require 'functions/mail.fn.php';
require 'functions/stats.fn.php';

include "pwd-ct.php";

//----------------------------------------------------
// STATS
//----------------------------------------------------
$page=basename(__file__);
$action="demande d'identifiants de connexion";

//----------------------------------------------------
// DATA
//----------------------------------------------------
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
	$redir="";
	extract($_POST);
	if(isset($centrale) && isset($mag))
	{
	//form complet : on récupère le pano galec du mag grace au nom du mag dans la table sca3 =>
		$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE mag= :mag");
		$req->execute(array(
			":mag" =>$mag
		));
		//si pano galec trouvé, le recherche dans table users
		if($sca=$req->fetch(PDO::FETCH_ASSOC))
			{
				$req=$pdoUser->prepare("SELECT * FROM users WHERE galec= :galec AND type= :mag");
				$req->execute(array(
					":galec"	=>$sca['galec'],
					":mag"		=>"mag"
				));
				$webuser=$req->fetch(PDO::FETCH_ASSOC);
				//ld rbt du mag
				$codeBt=$sca['btlec'];
				$rbt=$codeBt."-RBT@btlec.fr";
				// $rbt="valerie.montusclat@btlec.fr";

				// ----------------------------------------
				// si le mot de passe en clair existe déjà
				// ---------------------------------------
				if($webuser['nohash_pwd'] !="")
				{
		 	// echo "envoi email avec mdp " . $result['nohash_pwd'];
					$link="";
					$tplIdent='public/mail/envoi_identifiant.tpl.html';
					$subject="PORTAIL BTLEC Est - Vos identifiants de connexion";
					if(sendMail($rbt,$subject,$tplIdent,$webuser['login'],$webuser['nohash_pwd'], $link))
					{
						$redir="pwd.php?success=1";
						$descr="envoi identifiants par mail";
						pwdStat($pdoStat,$webuser['login'],$page, $action, $descr, $version);
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
						$descr="création d'une demande identifiants sur le portail";
						pwdStat($pdoStat,$webuser['login'],$page, $action, $descr, $version);
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





		}
	}


