<?php
if (preg_match('/_btlecest/', dirname(__FILE__)))
{
	define("VERSION",'_');
}
else
{
	define("VERSION",'');
}

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}
$dbCm=VERSION."cm";
$pdoCm=connectToDb($dbCm);
$pdoUser=connectToDb('web_users');
/*
1- recup les id des db old et actuelle
2- vérifie qu'elle n'ont pas déjà été traitée (présence id dans table histo )
3- si oui quittre
4- si non requetes et ajout à la db
*/

function getOldDbImportId($pdoUser){
	$req=$pdoUser->query("SELECT id_import FROM mag_ld_old LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getNewDbImportId($pdoUser){
	$req=$pdoUser->query("SELECT id_import FROM mag_ld LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getIdNewHisto($pdoUser){
	$req=$pdoUser->query("SELECT max(id_new) as id_new FROM lotus_histo");
	return $req->fetch(PDO::FETCH_ASSOC);
}


function existInNew($pdoUser){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoUser->query("SELECT * FROM mag_ld_old t1 RIGHT OUTER JOIN mag_ld t2 ON t1.email = t2.email WHERE t1.email IS NULL ORDER BY t1.ld_full");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function noMoreInNew($pdoUser){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoUser->query("SELECT * FROM mag_ld t1 RIGHT OUTER JOIN mag_ld_old t2 ON t1.email = t2.email WHERE t1.email IS NULL ORDER BY  t1.ld_full");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function addToHisto($pdoUser, $idold,$idnew,$email,$ldFull,$galec,$added){

	$req=$pdoUser->prepare("INSERT INTO lotus_histo (id_old,id_new,email,ld_full, galec, added) VALUES (:id_old, :id_new, :email, :ld_full, :galec, :added)");
	$req->execute([
		':id_old'	=>$idold,
		':id_new'	=>$idnew,
		':email'	=>$email,
		':ld_full'	=>$ldFull,
		':galec'	=>$galec,
		':added'	=>$added,
	]);
}

$oldId=getOldDbImportId($pdoUser);
echo $oldId['id_import'];
$newId=getNewDbImportId($pdoUser);
echo $newId['id_import'];
$histoId=getIdNewHisto($pdoUser);
echo $histoId['id_new'];
if($newId['id_import']==$histoId['id_new']){
	echo "le traitement a déjà été fait";
	exit();
}

$news=existInNew($pdoUser);
$deleted=noMoreInNew($pdoUser);




if(!empty($news)){
	foreach ($news as $new) {
		addToHisto($pdoUser, $oldId['id_import'],$newId['id_import'],$new['email'],$new['ld_full'], $new['galec'],1);


	}
}
echo "nouveau ". sizeof($news);
if(!empty($deleted)){
		foreach ($deleted as $del) {
 		addToHisto($pdoUser, $oldId['id_import'],$newId['id_import'],$del['email'],$del['ld_full'], $del['galec'],0);
	}
}

echo " a supprimer " .sizeof($deleted);
