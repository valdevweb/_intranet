<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
// include 'batch-mag/utils.fn.php';


function getSavCorrespondance($pdoMag,$sav){
	$req=$pdoMag->prepare("SELECT id from corresp_sav WHERE sav= :sav");
	$req->execute([
		':sav'		=>$sav
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data['id'];
	}
	return NULL;
}

function getMagSav($pdoSav){
	$req=$pdoSav->query("SELECT * FROM mag");
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getMagNotInGessica($pdoMag){
    $req=$pdoMag->query("SELECT * FROM mag where absent=1 and id>10000");
    return $req->fetchAll();

}


function addToSca3($pdoMag, $id){
    $req=$pdoMag->query("
    INSERT INTO sca3(btlec_sca, galec_sca, deno_sca, centrale_sca, ad1_sca, ad2_sca,  cp_sca, ville_sca, tel_sca, fax_sca, surface_sca, adherent_sca, directeur_sca, sorti, backoffice_sca ) 
    SELECT id,galec, deno, centrale, ad1, ad2, cp, ville, tel, fax, surface, adherent, directeur, gel, backoffice FROM mag WHERE id={$id}");
    return $req->errorInfo();

}
$mags=getMagSav($pdoSav);


foreach($mags as $mag){

    $poleSav=getSavCorrespondance($pdoMag,$mag['sav']);
    $antenneSav=getSavCorrespondance($pdoMag,$mag['pole']);
    $req=$pdoMag->query("UPDATE sca3 SET pole_sav={$poleSav}, antenne_sav={$antenneSav} WHERE btlec_sca={$mag['btlec']}");
    echo $req->rowCount();


}


// $toAdd=getMagNotInGessica($pdoMag);

// foreach ($toAdd as $key => $mag) {
//     $done=addToSca3($pdoMag, $mag['id']);
//     echo "<pre>";
//     print_r($done);
//     echo '</pre>';
// }

