<?php
//----------------------------------------------------
// CONNEXION  DB
//----------------------------------------------------
$path=dirname(__FILE__);
require "config/config.inc.php";



//----------------------------------------------------
// INCLUDES
//----------------------------------------------------
require 'functions/mail.fn.php';
require 'functions/stats.fn.php';
include 'Class/MagDbHelper.php';

//----------------------------------------------------
// STATS
//----------------------------------------------------
$page=basename(__file__);
$action="demande d'identifiants de connexion";
$magDbHelper=new MagDbHelper($pdoMag);
$centraleList=$magDbHelper->getDistinctCentraleMag();



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
		$req=$pdoMag->prepare("SELECT * FROM mag WHERE id= :id");
		$req->execute(array(
			":id" =>$mag
		));
		//si pano galec trouvé, le recherche dans table users
		if($magInfo=$req->fetch(PDO::FETCH_ASSOC))
		{
			$req=$pdoUser->prepare("SELECT * FROM users WHERE galec= :galec AND (type='mag' OR type ='centrale')");
			$req->execute(array(
				":galec"	=>$magInfo['galec'],
					// ":mag"		=>"mag"
			));
			$webuser=$req->fetch(PDO::FETCH_ASSOC);
				//ld rbt du mag
			$codeBt=$magInfo['id'];


			if(VERSION=='_'){
				$rbt='valerie.montusclat@btlec.fr';
			}else{
				$rbt=$codeBt."-RBT@btlec.fr";
			}
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
					pwdStat($pdoStat,$webuser['login'],$page, $action, $descr,VERSION);
					header('Location:'.$redir);

				}
				else
				{
						//err mail
					$redir="pwd.php?error=1";
						// header('Location:'.$redir);


				}
			}
			else
			{

				$id=$webuser['id'];
				$idMsg=addMsg($pdoBt,$id,$rbt,$mag);
				if(VERSION=='_'){
					$mailtoInfo="valerie.montusclat@btlec.fr";

				}else{
					$mailtoInfo="btlecest.portailweb.informatique@btlec.fr";

				}

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

include "pwd-ct.php";

