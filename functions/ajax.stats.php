<?php
// ----------------------------------------------------------
// 			pas d'autoload,
// 			donc fonction de connexion à la db
// 			et fonction d'enregistrement des stats reprises
// ----------------------------------------------------------
/* CONNEXION BASE STATS */
function getStatsLink() {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	$database='stats';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;

}
/* fonction ajout enregistrement db */
function addRecord($pdoStat,$typeLog,$user, $page,$action, $descr)
{
	$date=new DateTime();
	$date=$date->format('Y-m-d H:i:s');
	$req=$pdoStat->prepare('INSERT INTO stats_logs (type_log,id_user,site,date_heure,page,action,description)
		VALUE(:type_log,:id_user,:site,:date_heure,:page,:action,:description)');
	$req->execute(array(
		':type_log'=>$typeLog,
		':id_user'=>$user,
		':site'	=>'portail BT',
		':date_heure'=>$date,
		':page'		=>$page,
		':action'	=>$action,
		':description'=>$descr
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// récup les valeur transmises par ajax en POST
$descr=$_POST['urlSend'];
$action=$_POST['action'];
$from=$_POST['page'];
$user=$_POST['user'];

// avant découpage pour récupérer uniquement le nom de la page
// on regarde si site de dev ou de prod
if (preg_match('/_btlecest/', $from))
{
	$typeLog="dev";
}
else
{
	$typeLog="prod";
}
$from=explode('/',$from);
$page=end($from);


$pdoStat=getStatsLink();

addRecord($pdoStat,$typeLog,$user, $page,$action, $descr);

?>