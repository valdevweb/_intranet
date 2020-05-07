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


function oldTable($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM lotus_ld_old");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function newTable($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM lotus_ld");
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



// parcours l'ancienne => pas dans la nouvelle =email à supprimer
function inNew($pdoMag, $ldFull,$email, $galec){
	$req=$pdoMag->prepare("SELECT * FROM lotus_ld WHERE ld_full LIKE :ld_full AND email LIKE :email AND galec= :galec ");
	$req->execute([
		':ld_full'	=>'%'.$ldFull.'%',
		':email'	=>'%'.$email .'%',
		':galec'	=>$galec,
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return true;

}
//parcours la nouvelle => pas dans l'ancienne = email à ajouter
function inOld($pdoMag, $ldFull,$email, $galec){
	$req=$pdoMag->prepare("SELECT * FROM lotus_ld_old WHERE ld_full LIKE :ld_full AND email LIKE :email AND galec= :galec ");
	$req->execute([
		':ld_full'	=>'%'.$ldFull.'%',
		':email'	=>'%'.$email .'%',
		':galec'	=>$galec,
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return true;

}
$oldId=getOldDbImportId($pdoMag);
$newId=getNewDbImportId($pdoMag);
$histoId=getIdNewHisto($pdoMag);


// if($newId['id_import']==$histoId['id_new']){
// 	echo "le traitement a déjà été fait";
// 	exit();
// }


$newTable=newTable($pdoMag);
$oldTable=oldTable($pdoMag);
$arrNew=[];
$arrOld=[];

//parcours la nouvelle => pas dans l'ancienne = email à ajouter
$j=0;
foreach ($newTable as $key => $new) {
	if(!inOld($pdoMag,$new['ld_full'], $new['email'], $new['galec'])){
		echo "ajouter ".$new['ld_full']. $new['email']. $new['galec'];
		echo "<br>";

		$arrNew[$j]['ld_full']=$new['ld_full'];
		$arrNew[$j]['email']=$new['email'];
		$arrNew[$j]['galec']=$new['galec'];
		$j++;

	}
}

$i=0;
foreach ($oldTable as $key => $old) {
	if(!inNew($pdoMag, $old['ld_full'], $old['email'], $old['galec'])){
		echo "supprimer ".$old['ld_full']. $old['email']. $old['galec'];
		echo "<br>";
		$arrOld[$i]['ld_full']=$old['ld_full'];
		$arrOld[$i]['email']=$old['email'];
		$arrOld[$i]['galec']=$old['galec'];
		$i++;
	}
}




if(!empty($arrNew)){
	foreach ($arrNew as $new) {
		addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$new['email'],$new['ld_full'], $new['galec'],1);
	}
}



if(!empty($arrOld)){
	foreach ($arrOld as $old) {
		addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$old['email'],$old['ld_full'], $old['galec'],0);

	}
}


