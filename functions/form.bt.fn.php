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


//-----------------------------------
//			demandes mag
//-----------------------------------

//dashboard affichage des demandes non cloturées
function ddesMag($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE etat <> :clos ORDER BY id_service");
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
	$req=$pdoBt->prepare("SELECT * FROM replies LEFT JOIN msg ON replies.id_msg=msg.id WHERE etat= :clos");
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


function recordReply($pdoBt,$idMsg)
{
	$date=new DateTime();
	$date=$date->format('Y-m-d H:i:s');
	$reply=strip_tags($_POST['reply']);
	$reply=nl2br($reply);
	$insert=$pdoBt->prepare('INSERT INTO replies (id_msg, reply, replied_by, date_reply) VALUE (:id_msg, :reply, :replied_by, :date_reply)');
	$result=$insert->execute(array(
		':reply'		=> $reply,
		':date_reply'	=> $date,
		':id_msg'		=> $idMsg,
		':replied_by'	=>$_SESSION['id']
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

//	$update=$pdoBt->prepare('UPDATE replies SET reply= :reply, date_reply= :date_reply, replied_by=:replied_by  WHERE id_msg= :id');

		// ':etat'			=>'clos',
//etat= :etat,


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




// SELECT base1.table1.champ1, base2.table2.champ2 FROM base1.table1 [LEFT|RIGHT|INNER] JOIN base2.table2 ON [...]
