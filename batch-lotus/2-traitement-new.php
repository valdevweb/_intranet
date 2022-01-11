<?php

if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
	$manuel=true;

}
else{
	set_include_path("D:\www\intranet\btlecest\\");
	$manuel=false;

}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'functions/tasklog.fn.php';
include 'Class/CrudDao.php';


$crudMag=new CrudDao($pdoMag);



function undoneImport($pdoMag){
	$req=$pdoMag->query("SELECT * FROM lotus_imports WHERE done=2 ORDER BY id desc LIMIT 1");
	$data=$req->fetch(PDO::FETCH_ASSOC);
	return $data;
}

function getExtractionByType($pdoMag,$id,$type){
	$req=$pdoMag->prepare("SELECT * FROM lotus_extraction WHERE id_import= :id_import AND type= :type AND id_listdiffu IS NOT NULL");
	$req->execute([
		':type'		=>$type,
		':id_import'=>$id
	]);
	$datas=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($datas)){
		return "";
	}
	return $datas;
}

function getExtraction($pdoMag,$id){
	$req=$pdoMag->prepare("SELECT * FROM lotus_extraction WHERE id_import= :id_import");
	$req->execute([
		':id_import'=>$id
	]);
	$datas=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($datas)){
		return "";
	}
	return $datas;
}
/* ------------------------------------
*				Initialisation
---------------------------------------	*/
$added=0;

$lotusCon=ldap_connect('217.0.222.26',389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser="ADMIN_BTLEC";
$lpappass="toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
$justThese = array( "mail","displayname", "mailaddress");


/* ------------------------------------
*				traitement
---------------------------------------	*/


// regarde si import non traité => done à 0
$newImport=undoneImport($pdoMag);
if(empty($newImport)){

}
echo $newImport;

$extraction=getExtraction($pdoMag,$newImport['id']);


if(empty($extraction)){
	echo "no data";
	exit;
}

$crudMag->deleteTable('listdiffu_email');

include 'traitement-new/extraction.php';




// si on a un import à traiter, on récupère les extractions par catégories de traitement
$empty=getExtractionByType($pdoMag,$newImport['id'],"vide");
$lotus=getExtractionByType($pdoMag,$newImport['id'],"lotus");
$emailDb=getExtractionByType($pdoMag,$newImport['id'],"email");
$ld=getExtractionByType($pdoMag,$newImport['id'],"ld");


if(!empty($lotus)){
	include 'traitement-new/lotus-type.php';
}

if(!empty($empty)){
	include 'traitement-new/empty-type.php';
}

if(!empty($emailDb)){
	include 'traitement-new/email-type.php';

}
if(!empty($ld)){
	include 'traitement-new/ld-type.php';
}

// echo "nb adresses ajoutées " .$added;


$crudMag->updateOneField("lotus_imports", "done", 1, $newImport['id']);