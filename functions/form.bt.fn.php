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
	$req=$pdoBt->prepare("SELECT * FROM services ORDER BY full_name");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


//-----------------------------------
//			demandes mag
//-----------------------------------

function ddesMag($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE etat <> :clos ORDER BY id_service");
	$req->execute(array(
	':clos' =>'clos'
	 ));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function histoDdesMag($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE etat = :clos");
	$req->execute(array(
	':clos' =>'clos'
	 ));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}



function getMag($pdoBt,$idMag)
{
	$req=$pdoBt->prepare("SELECT * FROM lk_user WHERE iduser= :id");
	$req->execute(array(
		':id' => $idMag
	));
	if($galecExist=$req->fetch(PDO::FETCH_ASSOC))
		{
			$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE galec= :galec");
			$req->execute(array(
				':galec' => $galecExist['galec']
			));

			return $req->fetch(PDO::FETCH_ASSOC);
		}

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

	$update=$pdoBt->prepare('UPDATE msg SET reply= :reply, etat= :etat, date_reply= :date_reply,reply_by=:reply_by  WHERE id= :id');
	$update->execute(array(
		':reply'		=> $_POST['reply'],
		':date_reply'	=> $date,
		':etat'			=>'clos',
		':id'			=>$idMsg,
		':reply_by'		=>$_SESSION['id']
	));


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




// SELECT base1.table1.champ1, base2.table2.champ2 FROM base1.table1 [LEFT|RIGHT|INNER] JOIN base2.table2 ON [...]
