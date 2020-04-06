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


function getSca3($pdoMag){
	$req=$pdoMag->query("SELECT * FROM sca3 ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function getMagSyno($pdoMag){
	$req=$pdoMag->query("SELECT * FROM magsyno");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$centraleList=getCentralesExport($pdoMag);

$sca=getSca3($pdoMag);
$magSyno=getMagSyno($pdoMag);



$fieldsName=array_keys($sca[0]);



foreach ($sca as $key => $oldMag) {
	$centraleSca=convertCentraleExport($oldMag['centrale_sca'], $centraleList);
	$centraleDoris=convertCentraleExport($oldMag['centrale_doris'], $centraleList);
	$centraleSmiley=convertCentraleExport($oldMag['centrale_smiley'], $centraleList);
	$sorti=convertTrueFalseExport($oldMag['sorti']);
	$dateSortie=convertToDateExport(trim($oldMag['date_sortie']));
	$dateResiliation=convertToDateExport($oldMag['date_resiliation']);
	$dateAdhesion=convertToDateExport($oldMag['date_adhesion']);
	$dateFermeture=convertToDateExport($oldMag['date_fermeture']);
	$dateOuverture=convertToDateExport($oldMag['date_ouverture']);;
	$affilie=($oldMag['affilie']==1)? 2 :0;
	$datasetSca3[]=[
		$oldMag['id_sca'],
		$oldMag['btlec_sca'],
		$oldMag['galec_sca'],
		$oldMag['deno_sca'],
		$centraleSca,
		$oldMag['ad1_sca'],
		$oldMag['ad2_sca'],
		$oldMag['ad3'],
		$oldMag['cp_sca'],
		$oldMag['ville_sca'],
		$oldMag['tel_sca'],
		$oldMag['fax_sca'],
		$oldMag['surface_sca'],
		$oldMag['adherent_sca'],
		$oldMag['nom_gesap'],
		$oldMag['lotus_rbt'],
		$oldMag['obs'],
		$oldMag['galec_old'],
		$centraleDoris,
		$sorti,
		$dateSortie,
		$oldMag['raison_sociale'],
		$oldMag['mandat'],
		$dateResiliation,
		$dateAdhesion,
		$affilie,
		$dateFermeture,
		$dateOuverture,
		$oldMag['docubase_login'],
		$oldMag['docubase_pwd'],
		$oldMag['apple_id'],
		$oldMag['mots_cles'],
		$oldMag['pole_sav_sca'],
		$centraleSmiley,
		$oldMag['racine_list'],
		$oldMag['date_insert'],
		$oldMag['date_update'],
	];
}



$exportSca=DIR_EXPORT_CSV."\\export-sca3.csv";
$exportMagSyno=DIR_EXPORT_CSV."\\export-magsyno.csv";




$file = fopen($exportSca, "w") or die("Unable to open file!");
    fputcsv($file, $fieldsName);
foreach ($datasetSca3 as $key => $value) {
    fputcsv($file, $value);
}

fclose($file);



$file = fopen($exportMagSyno, "w") or die("Unable to open file!");
foreach ($magSyno as $key => $value) {
    fputcsv($file, $value);
}

fclose($file);

insertTaskLog($pdoExploit,30, 0, "");
