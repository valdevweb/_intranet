<?php

function addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null){

	if(VERSION=="_"){
		$typeLog="dev";
	}
	else
	{
		$typeLog="prod";
	}
	if(is_null($detail)){
		$detail="";
	}
	if(is_null($code)){
		$detail="";
	}

	$req=$pdoStat->prepare('INSERT INTO stats_logs (type_log,id_user,id_web_user,site,date_heure,page,action,description, detail, code)
		VALUE(:type_log,:id_user,:id_web_user,:site,:date_heure,:page,:action,:description, :detail, :code)');
	$req->execute(array(
		':type_log'=>$typeLog,
		':id_user'=>$_SESSION['user'],
		':id_web_user'=>$_SESSION['id_web_user'],
		':site'	=>'portail BT',
		':date_heure'=>date('Y-m-d H:i:s'),
		':page'		=>$page,
		':action'	=>$action,
		':description'=>$descr,
		':detail'=>$detail,
		':code'=>$code

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


//----------------------------------------------------------
//					page pwd.php
// on n'a pas de var de session donc on récupère
//-----------------------------------------------------------
function pwdStat($pdoStat,$login,$page, $action, $descr, $version)
{

	$req=$pdoStat->prepare('INSERT INTO stats_logs (type_log,id_user,site,date_heure,page,action,description)
		VALUE(:type_log,:id_user,:site,:date_heure,:page,:action,:description)');
	$req->execute(array(
		':type_log'		=>	$version,
		':id_user'		=>$login,
		':site'			=>'portail BT',
		':date_heure'	=>date('Y-m-d H:i:s'),
		':page'			=>$page,
		':action'		=>$action,
		':description'	=>$descr
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function errorlog($pdoUser){
	$data='';
	if(isset($_SESSION) && !empty($_SESSION) ){
		foreach ($_SESSION as $key => $session) {
			if(!is_array($session)){
				$data.='['.$key.'] = '.$session.'<br>';
			}
		}
	}elseif(empty($_SESSION)){
		$data="la session existe mais est vide";

	}
	else{
		$data="session inexistante";
	}

	$req=$pdoUser->prepare("INSERT INTO log_error (session, page,date_log) VALUES (:session, :page,:date_log)");
	$req->execute([
		':session'	=>$data,
		':page'		=>basename(__FILE__),
		':date_log'	=>date('Y-m-d H:i:s')
	]);

}