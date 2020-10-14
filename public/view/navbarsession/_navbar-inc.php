<?php
function getUserRights($pdoUser){
	$req=$pdoUser->prepare("SELECT id_droit FROM attributions WHERE id_user= :id_user" );
	$req->execute([':id_user'=>$_SESSION['id']]);
	return $req->fetchAll(PDO::FETCH_COLUMN);
}
$arUserRights=getUserRights($pdoUser);
$_SESSION['id_droit']=$arUserRights;

