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
	$req=$pdoBt->query("SELECT btlec, sca3.* FROM sca3 ");
	return $req->fetchAll(PDO::FETCH_GROUP);
}
function alreadyInNewSca($pdoMag,$id){
	$req=$pdoMag->query("SELECT btlec FROM access WHERE btlec={$id}");

	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
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



$scaList=getSca($pdoBt);
$centraleList=getCentrales($pdoMag);

$newScaAdded=0;
$newScaUpdated=0;

foreach ($scaList as $btlec => $sca) {
	// echo convertToDate($sca[0]['date_sortie']) .'<br>';


	$dateSortie=convertToDate(trim($sca[0]['date_sortie']));
	$centrale=convertCentrale($sca[0]['centrale'], $centraleList);
	$centraleDoris=convertCentrale($sca[0]['centrale_doris'], $centraleList);
	$sorti=convertTrueFalse($sca[0]['sorti']);
	if(!empty($sca[0]['btlec'])){
		if(alreadyInNewSca($pdoMag,$sca[0]['btlec'])){
			$updateSca=updateNewSca($pdoMag, $sca[0], $centrale, $centraleDoris, $sorti, $dateSortie);
			if($updateSca==1){
				$newScaUpdated++;

			}else{
				echo "error";

			}
		}else{
			$insertSca=insertNewSca($pdoMag,$sca[0],$centrale,$centraleDoris,$sorti,$dateSortie);
			if($insertSca==1){
				$newScaAdded++;
			}
		}
	}

}


echo "ajouté ".$newScaAdded;
echo "<br>";

echo "updated" . $newScaUpdated;


