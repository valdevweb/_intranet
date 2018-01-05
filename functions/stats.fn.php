<?php
function addRecord($pdoStat,$page,$action, $descr)
{
	global $version;
	if($version=="_"){
		$typeLog="dev";
	}
	else
	{
		$typeLog="prod";
	}
	$date=new DateTime();
	$date=$date->format('Y-m-d H:i:s');
	$req=$pdoStat->prepare('INSERT INTO stats_logs (type_log,id_user,site,date_heure,page,action,description)
		VALUE(:type_log,:id_user,:site,:date_heure,:page,:action,:description)');
	$req->execute(array(
		':type_log'=>$typeLog,
		':id_user'=>$_SESSION['user'],
		':site'	=>'portail BT',
		':date_heure'=>$date,
		':page'		=>$page,
		':action'	=>$action,
		':description'=>$descr
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

//----------------------------------------------------------
//					page index.php
// on n'a pas de var de session donc on récupère le login saisi
//-----------------------------------------------------------
function authStat($pdoStat,$page, $action, $err)
{
	global $version;
	$date=new DateTime();
	$date=$date->format('Y-m-d H:i:s');
	if($version=="_"){
		$typeLog="dev";
	}
	else
	{
		$typeLog="prod";
	}
	// si la fonction login n'a pas renvoyé de message d'erreur, c'est que le user a ete authentifié et redirgé sur home
	if(empty($err))
	{
		$err="user authentifié";
	}
	$req=$pdoStat->prepare('INSERT INTO stats_logs (type_log,id_user,site,date_heure,page,action,description)
		VALUE(:type_log,:id_user,:site,:date_heure,:page,:action,:description)');
	$req->execute(array(
		':type_log'=>$typeLog,
		':id_user'=>$_POST['login'],
		':site'	=>'portail BT',
		':date_heure'=>$date,
		':page'		=>$page,
		':action'	=>$action,
		':description'=>$err
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}