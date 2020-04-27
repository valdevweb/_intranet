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
*		suite mauvaise manip en faisant des tests => besoin de vérifie que les adresses de la table de prod (email mag ) sont bien à jour par rapport
*		à ld_lotus
*
 */


// les adresses qui n'apparaissent plus dans les nouveau exports lotus (donc table lotus_ld) et qui sont tj dans mag_email

function notAnymoreInLotusLd($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM lotus_ld RIGHT OUTER JOIN mag_email ON lotus_ld.email = mag_email.email WHERE lotus_ld.email IS NULL ORDER BY  lotus_ld.ld_full");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



// les adresses qui sont dans les nouveaux export et qui n'ont pas été ajoutés à la base de prod

function newInLotusLd($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM mag_email RIGHT OUTER JOIN lotus_ld ON  mag_email.email=lotus_ld.email AND mag_email.ld_suffixe=lotus_ld.ld_suffixe WHERE mag_email.email IS NULL ORDER BY  lotus_ld.ld_full");
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
	$err=$req->errorInfo();
	if(!empty($err[2])){
			echo "<pre>";
			print_r($err);
			echo '</pre>';

	}
}
$toadd=newInLotusLd($pdoMag);
$todelete= notAnymoreInLotusLd($pdoMag);

foreach ($toadd as $add) {

	$oldId['id_import']=0;
	$newId['id_import']=54;

	addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$add['email'],$add['ld_full'], $add['galec'],1);
}



foreach ($todelete as $delete) {

	$oldId['id_import']=0;
	$newId['id_import']=54;

	addToHisto($pdoMag, $oldId['id_import'],$newId['id_import'],$delete['email'],$delete['ld_full'], $delete['galec'],0);



}


