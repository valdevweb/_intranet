<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';
/*
1- recup les id des db old et actuelle
2- vérifie qu'elle n'ont pas déjà été traitée (présence id dans table histo )
3- si oui quittre
4- si non requetes et ajout à la db
*/

function getOldDbImportId($pdoMag){
	$req=$pdoMag->query("SELECT id_import FROM lotus_ld_old LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getNewDbImportId($pdoMag){
	$req=$pdoMag->query("SELECT id_import FROM lotus_ld LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getIdNewHisto($pdoMag){
	$req=$pdoMag->query("SELECT max(id_new) as id_new FROM lotus_histo");
	return $req->fetch(PDO::FETCH_ASSOC);
}


function existInNew($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM lotus_ld_old t1 RIGHT OUTER JOIN lotus_ld t2 ON t1.email = t2.email WHERE t1.email IS NULL ORDER BY t1.ld_full");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function noMoreInNew($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM lotus_ld t1 RIGHT OUTER JOIN lotus_ld_old t2 ON t1.email = t2.email WHERE t1.email IS NULL ORDER BY  t1.ld_full");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function addToHisto($pdoMag, $idold,$idnew,$email,$ldFull,$galec,$added){

	$req=$pdoMag->prepare("INSERT INTO lotus_histo (id_old,id_new,email,ld_full, galec, added) VALUES (:id_old, :id_new, :email, :ld_full, :galec, :added)");
	$req->execute([
		':id_old'	=>$idold,
		':id_new'	=>$idnew,
		':email'	=>$email,
		':ld_full'	=>$ldFull,
		':galec'	=>$galec,
		':added'	=>$added,
	]);
}

$oldId=getOldDbImportId($pdoMag);
echo $oldId['id_import'];
$newId=getNewDbImportId($pdoMag);
echo $newId['id_import'];
$histoId=getIdNewHisto($pdoMag);
echo $histoId['id_new'];
if($newId['id_import']==$histoId['id_new']){
	echo "le traitement a déjà été fait";
	exit();
}

$news=existInNew($pdoMag);
$deleted=noMoreInNew($pdoMag);




if(!empty($news)){
	foreach ($news as $new) {
		addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$new['email'],$new['ld_full'], $new['galec'],1);


	}
}
echo "nouveau ". sizeof($news);
if(!empty($deleted)){
		foreach ($deleted as $del) {
 		addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$del['email'],$del['ld_full'], $del['galec'],0);
	}
}

echo " a supprimer " .sizeof($deleted);
