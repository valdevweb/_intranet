<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';
include 'batch-mag\utils.fn.php';




function getSca($pdoBt){
	$req=$pdoBt->query("SELECT btlec, sca3.* FROM sca3");
	return $req->fetchAll(PDO::FETCH_GROUP);

}

function getNewSca($pdoMag){
	$req=$pdoMag->query("SELECT btlec, access.* FROM access");
	return $req->fetchAll(PDO::FETCH_GROUP);
}

function getAllInfoNumBt($pdoBt){
	$req=$pdoBt->query("SELECT NumBT, infosnumbt.* FROM infosnumbt");
	return $req->fetchAll(PDO::FETCH_GROUP);
}


function getInfoPano($pdoBt){
	$req=$pdoBt->query("SELECT Panonceau, infospanonceau.* FROM infospanonceau");
	return $req->fetchAll(PDO::FETCH_GROUP);

}

function getCentrales($pdoMag){
	$req=$pdoMag->query("SELECT id_ctbt, centrale FROM centrales");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getBtlecGalec($pdoBt){
	$req=$pdoBt->query("SELECT  galec, btlec FROM sca3");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}

function alreadyInMag($pdoMag,$id){
	$req=$pdoMag->query("SELECT id FROM mag WHERE id={$id}");
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}
function alreadyInNewSca($pdoMag,$id){
	$req=$pdoMag->query("SELECT btlec FROM access WHERE btlec={$id}");

	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}

function alreadyInNewPano($pdoMag, $galec){
	$req=$pdoMag->prepare("SELECT * FROM access_pano WHERE galec= :galec");
	$req->execute([
		':galec'		=>trim($galec)
	]);
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}



function alreadyInNewNumBt($pdoMag,$id){
	$req=$pdoMag->query("SELECT btlec FROM access_numbt WHERE btlec={$id}");
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}


function updateNumBt($pdoMag,$data, $resiliation, $adhesion, $affilie, $fermeture, $ouverture){
	$req=$pdoMag->prepare("UPDATE access_numbt SET mandat_c3s= :mandat_c3s,date_resiliation= :date_resiliation,date_adhesion= :date_adhesion,affilie= :affilie,date_fermeture= :date_fermeture,date_ouverture= :date_ouverture,docubase_login= :docubase_login,docubase_pwd= :docubase_pwd,apple_id= :apple_id,mots_cles= :mots_cles, date_update= :date_update WHERE btlec= :btlec");
	$req->execute([
		':btlec'		=>$data['NumBT'],
		':mandat_c3s'		=>$data['MandatC3S'],
		':date_resiliation'		=>$resiliation,
		':date_adhesion'		=>$adhesion,
		':affilie'		=>$affilie,
		':date_fermeture'		=>$fermeture,
		':date_ouverture'		=>$ouverture,
		':docubase_login'		=>$data['docubase_login'],
		':docubase_pwd'		=>$data['docubase_pwd'],
		':apple_id'		=>$data['apple_id'],
		':mots_cles'		=>$data['mots_cles'],
		':date_update'		=>date('Y-m-d H:i:s')



	]);

	// return $req->errorInfo();
	return $req->rowCount();
}

function insertNumBt($pdoMag,$data, $resiliation, $adhesion, $affilie, $fermeture, $ouverture){
	$req=$pdoMag->prepare("INSERT INTO access_numbt (btlec, mandat_c3s, date_resiliation, date_adhesion, affilie, date_fermeture, date_ouverture, docubase_login, docubase_pwd, apple_id, mots_cles, date_insert) VALUES (:btlec, :mandat_c3s, :date_resiliation, :date_adhesion, :affilie, :date_fermeture, :date_ouverture, :docubase_login, :docubase_pwd, :apple_id, :mots_cles,  :date_insert)");
	$req->execute([
		':btlec'		=>$data['NumBT'],
		':mandat_c3s'		=>$data['MandatC3S'],
		':date_resiliation'		=>$resiliation,
		':date_adhesion'		=>$adhesion,
		':affilie'		=>$affilie,
		':date_fermeture'		=>$fermeture,
		':date_ouverture'		=>$ouverture,
		':docubase_login'		=>$data['docubase_login'],
		':docubase_pwd'		=>$data['docubase_pwd'],
		':apple_id'		=>$data['apple_id'],
		':mots_cles'		=>$data['mots_cles'],
		':date_insert'		=>date('Y-m-d H:i:s')


	]);
	return $req->rowCount();

}

function updateMagDocubase($pdoMag, $btlec, $login,$pwd){
	$req=$pdoMag->prepare("UPDATE mag SET docubase_login= :docubase_login, docubase_pwd= :docubase_pwd, date_update= :date_update WHERE id= :id");
	$req->execute([
		':docubase_login'	=>$login,
		':docubase_pwd'		=>$pwd,
		':id'				=>$btlec,
		':date_update'		=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();

}


function updateNewSca($pdoMag,$data,$centrale,$centraleDoris,$sorti,$dateSortie){
	$req=$pdoMag->prepare("UPDATE access SET galec= :galec, mag= :mag, centrale= :centrale, ad1= :ad1, ad2= :ad2, ad3= :ad3, cp= :cp, city= :city, tel= :tel, fax= :fax, surface= :surface, adherent= :adherent, nom_gesap= :nom_gesap, lotus_rbt= :lotus_rbt, obs= :obs, galec_old= :galec_old, sorti= :sorti, centrale_doris= :centrale_doris, date_sortie= :date_sortie, date_update= :date_update WHERE btlec= :btlec");
	$req->execute([

		':galec'		=>$data['galec'],
		':btlec'		=>$data['btlec'],
		':mag'		=>$data['mag'],
		':centrale'		=>$centrale,
		':ad1'		=>$data['ad1'],
		':ad2'		=>$data['ad2'],
		':ad3'		=>$data['ad3'],
		':cp'		=>$data['cp'],
		':city'		=>$data['city'],
		':tel'		=>$data['tel'],
		':fax'		=>$data['fax'],
		':surface'		=>$data['surface'],
		':adherent'		=>$data['adherent'],
		':nom_gesap'		=>$data['nom_gesap'],
		':lotus_rbt'		=>$data['lotus_rbt'],
		':obs'		=>$data['obs'],
		':galec_old'		=>$data['galec_old'],
		':sorti'		=>$sorti,
		':centrale_doris'		=>$centraleDoris,
		':date_sortie'		=>$dateSortie,
		':date_update'		=>date('Y-m-d H:i:s')
	]);
	// return $req->errorInfo();
	return $req->rowCount();

}


function insertNewSca($pdoMag,$data,$centrale,$centraleDoris,$sorti,$dateSortie){
	$req=$pdoMag->prepare("INSERT access (btlec, galec, mag, centrale, ad1, ad2, ad3, cp, city, tel, fax, surface, adherent, nom_gesap, lotus_rbt, obs, galec_old, sorti, centrale_doris, date_sortie, date_insert) VALUES (:btlec, :galec,  :mag, :centrale, :ad1, :ad2, :ad3, :cp, :city, :tel, :fax, :surface, :adherent, :nom_gesap, :lotus_rbt, :obs, :galec_old, :sorti, :centrale_doris, :date_sortie, :date_insert)");
	$req->execute([

		':galec'		=>$data['galec'],
		':btlec'		=>$data['btlec'],
		':mag'		=>trim($data['mag']),
		':centrale'		=>$centrale,
		':ad1'		=>trim($data['ad1']),
		':ad2'		=>trim($data['ad2']),
		':ad3'		=>trim($data['ad3']),
		':cp'		=>$data['cp'],
		':city'		=>trim($data['city']),
		':tel'		=>$data['tel'],
		':fax'		=>$data['fax'],
		':surface'		=>$data['surface'],
		':adherent'		=>trim($data['adherent']),
		':nom_gesap'		=>$data['nom_gesap'],
		':lotus_rbt'		=>$data['lotus_rbt'],
		':obs'		=>$data['obs'],
		':galec_old'		=>$data['galec_old'],
		':sorti'		=>$sorti,
		':centrale_doris'		=>$centraleDoris,
		':date_sortie'		=>$dateSortie,
		':date_insert'		=>date('Y-m-d H:i:s')

	]);


	return $req->rowCount();

}

function updatePano($pdoMag,$data,  $btlec,$centrale, $poleSav){
	$req=$pdoMag->prepare("UPDATE access_pano SET id= :id, btlec= :btlec, pole_sav= :pole_sav, centrale_smiley= :centrale_smiley, lotus_racine= :lotus_racine, date_update= :date_update WHERE  galec= :galec");
	$req->execute([

		':id'	=>$data['id'],
		':btlec'	=>$btlec,
		':galec'	=>$data['Panonceau'],
		':pole_sav'	=>$poleSav,
		':centrale_smiley'	=>$centrale,
		':lotus_racine'	=>$data['RacineListe'],
		':date_update'=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
}
function insertPano($pdoMag,$data, $btlec,$centrale, $poleSav){
	$req=$pdoMag->prepare("INSERT INTO access_pano (id, btlec, galec, pole_sav, centrale_smiley, lotus_racine, date_insert) VALUES (:id, :btlec, :galec, :pole_sav, :centrale_smiley, :lotus_racine, :date_insert)");
	$req->execute([
		':id'	=>$data['id'],
		':btlec'	=>$btlec,
		':galec'	=>$data['Panonceau'],
		':pole_sav'	=>$poleSav,
		':centrale_smiley'	=>$centrale,
		':lotus_racine'	=>$data['RacineListe'],
		':date_insert'=>date('Y-m-d H:i:s')
	]);
	// return $req->errorInfo();
	return $req->rowCount();

}










$centraleList=getCentrales($pdoMag);
$scaList=getSca($pdoBt);
$newSca=getNewSca($pdoMag);
$oldInfoNumBt=getAllInfoNumBt($pdoBt);
// $infoPano=getInfoPano($pdoBt);
// $listPanoBt=getBtlecGalec($pdoBt);

// conversion à faire :
// centrale
// centrale_doris
// date_sortie
// sorti
// echo "<pre>";
// print_r($centraleList);
// echo '</pre>';
$newScaAdded=0;
$newScaUpdated=0;
$newInfoUpdated=0;
$newInfoAdded=0;
$newDocubase=0;
$newPanoUpdated=0;
$newPanoAdded=0;
// echo "<pre>";
// print_r($oldInfoNumBt);
// echo '</pre>';


/*****************************************
IMPORT SCA3 VERS MAGASIN.ACCESS

*****************************************/
// include('mag-inc-sca.php');
// include('mag-inc-numbt.php');
