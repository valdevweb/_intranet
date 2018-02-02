<?php

// INDEX.PHP
//id user correspondat à quel id_bt
function getUserId($pdoUser)
{
// bt ou mag
	$req=$pdoUser->prepare("SELECT id_bt FROM users WHERE id= :iduser");
	$req->execute(array(
		':iduser'	=>$_SESSION['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// INDEX.PHP
//recup infos du user dans table btlec
//on récup tout et créé les var de session dans index.php
function btInfo($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM btlec WHERE id= :btlec");
	$req->execute(array(
		':btlec'		=>$_SESSION['id_btlec']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}
// info mlag table sca3
function magInfo($pdoBt){

	$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE galec= :galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}


//récup pano galec dans table user de la db web_users
function getPanoGalec($pdoUser)
{
	$req=$pdoUser->prepare("SELECT galec FROM users WHERE id=:iduser");
	$req->execute(array(
		':iduser'=>$_SESSION['id']
	));
	// on ne retourne qu'un résultat m'id est unique
	return $req->fetch(PDO::FETCH_ASSOC);
}







