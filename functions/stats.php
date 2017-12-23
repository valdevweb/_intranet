<?php
require '../config/autoload.php';


function addRecord($pdoStat,$action, $descr)
{
	global $version;
	if($version=="_"){
		$typeLog="dev";
	}
	else
	{
		$typeLog="prod";
	}

	$req=$pdoStat->prepare('INSERT INTO stats (type_log,id_user,site,date_heure,page,action,description)
		VALUE(:type_log,:id_user,:site,:date_heure,:page,:action,:description)');
	$req->execute(array(
		':type_log'=>$typeLog,
		':id_user'=>$_SESSION['id'],
		':site'	=>'portail BT',
		':date_heure'=> date('Y-m-d H:i:s'),
		':page'		=>basename(__FILE__),
		':action'	=>$action,
		':description'=>$descr
	));
	$req->fetch(PDO::FETCH_ASSOC);



}