<?php
// recup toutes les demandes mag pour iun service




// function idService($pdoBt,$gt){
// 	$req=$pdoBt->prepare("SELECT * FROM services WHERE slug= :gt");
// 	$req->execute(array(
// 		':gt' => $gt
// 		 ));
// 	return $req->fetchAll();
// }


//-----------------------------------
//			liste des services
//-----------------------------------
function listServices($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM services WHERE slug <>'' ORDER BY full_name ");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function listServicesNoTest($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM services WHERE slug <>'' AND slug<> 'test' ORDER BY full_name ");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

//-----------------------------------
//			demandes mag
//-----------------------------------

//dashboard affichage des demandes non cloturées
function ddesMag($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE etat <> :clos ORDER BY id_service, date_msg DESC");
	$req->execute(array(
	':clos' =>'clos'
	 ));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
//dashboard : info rapides sur la der réponse bt et le nombre de réponse totale pour un msg
function nbRep($pdoBt, $idMsg)
{
	$req=$pdoBt->prepare("SELECT count(t_replies.id_msg) AS nb_rep, t_replies.id_msg, max(t_replies.date_reply)  AS last_reply_date, t_replies.replied_by FROM replies t_replies WHERE t_replies.id_msg= :id_msg GROUP BY t_replies.id_msg");
	$req->execute(array(
	':id_msg' =>$idMsg
	 ));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function histoDdesMag($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM replies LEFT JOIN msg ON replies.id_msg=msg.id WHERE etat= :clos ORDER BY date_reply DESC");
	$req->execute(array(
	':clos' =>'clos'
	 ));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


//récup pano galec dans table user de la db web_users
function getPanoGalec($pdoUser,$idMag)
{
	$req=$pdoUser->prepare("SELECT galec FROM users WHERE id=:id");
	$req->execute(array(
		':id'=>$idMag
	));
	// on ne retourne qu'un résultat m'id est unique
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getMag($pdoBt,$panoGalec)
{
	$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE galec= :galec");
	$req->execute(array(
		':galec' =>$panoGalec
			));
			return $req->fetch(PDO::FETCH_ASSOC);

}

function showOneMsg($pdoBt,$idMsg)
{
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id= :idMsg");
	$req->execute(array(
		'idMsg'	=>	$idMsg
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function recordReply($pdoBt,$idMsg,$file)
{
	$date=new DateTime();
	$date=$date->format('Y-m-d H:i:s');
	$reply=strip_tags($_POST['reply']);
	$reply=nl2br($reply);
	$insert=$pdoBt->prepare('INSERT INTO replies (id_msg, reply, replied_by, date_reply,inc_file) VALUE (:id_msg, :reply, :replied_by, :date_reply, :inc_file)');
	$result=$insert->execute(array(
		':reply'		=> $reply,
		':date_reply'	=> $date,
		':id_msg'		=> $idMsg,
		':replied_by'	=>$_SESSION['id'],
		':inc_file'		=> $file
	));
	return $result;
}

function majEtat($pdoBt,$idMsg,$etat)
{
	$update=$pdoBt->prepare('UPDATE msg SET etat= :etat  WHERE id= :id');
	$result=$update->execute(array(
		':etat'		=> $etat,
		':id'		=>$idMsg
	));
	return $result;
}


function affectation($pdoBt,$idMsg,$service)
{
	$update=$pdoBt->prepare('UPDATE msg SET id_service= :service  WHERE id= :id');
	$result=$update->execute(array(
		':service'		=> $service,
		':id'		=>$idMsg
	));
	return $result;
}



function displayMsgMag($pdoBt,$idMsg){
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id= :idMsg");
	$req->execute(array(
		'idMsg'	=>	$idMsg
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function infoMag($pdoMag,$idMag){
	$req=$dbmag->prepare("SELECT * FROM mag WHERE id_mag= :idMag");
	$req->execute(array(
		'idMag'	=>$id_mag
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


//-----------------------------------
//			fichiers joints - affichage :
//					mag/edit-msg
//					btlec/dashboard
//					btlec/answer
//					btlec/closedmsg
//					btlec/histo
//-----------------------------------


function isAttached($incFileStrg)
{
	global $version;
	$href="";
	if(!empty($incFileStrg))
	{
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg=explode( '; ', $incFileStrg );
		foreach ($incFileStrg as $dbData)
		{
		$ico="<i class='fa fa-paperclip fa-lg' aria-hidden='true'></i>";
		$href.= "<a class='pj' href='http://172.30.92.53/".$version ."upload/mag/" . $dbData . "'>" .$ico ."&nbsp; &nbsp; ouvrir</a>";

		}
	}
	return $href;
}






// SELECT base1.table1.champ1, base2.table2.champ2 FROM base1.table1 [LEFT|RIGHT|INNER] JOIN base2.table2 ON [...]
