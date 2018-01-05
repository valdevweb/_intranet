<?php

// INDEX.PHP
// interroagation de la table qui fait le lien entre webuser et btlec ou sca3 : lkuser
function getUserId($pdoBt)
{
// bt ou mag
	$req=$pdoBt->prepare("SELECT * FROM lk_user WHERE iduser= :iduser");
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


//interrogation sca3^pour mag
function getPanoGalec($pdoUser)
{
	$req=$pdoUser->prepare("SELECT galec FROM users WHERE id=:iduser");
	$req->execute(array(
		':iduser'=>$_SESSION['id']
	));
	// on ne retourne qu'un résultat m'id est unique
	return $req->fetch(PDO::FETCH_ASSOC);
}







