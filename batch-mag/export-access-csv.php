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
		$oldMag['nom_gesap'],
		$oldMag['obs'],
		$oldMag['galec_old'],
		$sorti,
		$dateSortie,
		$centraleDoris,
		$oldMag['id_sca'],
		$oldMag['galec_sca'],
		$oldMag['btlec_sca'],
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
		$oldMag['adherent_sca']
	];

	$datasetPano[]=[
		$oldMag['galec_sca'],
		$oldMag['pole_sav_sca'],
		$centraleSmiley,
		$oldMag['racine_list'],

	];
	$datasetBt[]=[
		$oldMag['apple_id'],
		$oldMag['docubase_pwd'],
		$oldMag['docubase_login'],
		$oldMag['raison_sociale'],
		$oldMag['mandat'],
		$dateResiliation,
		$dateAdhesion,
		$affilie,
		$dateFermeture,
		$dateOuverture,
		$oldMag['btlec_sca'],
		$oldMag['mots_cles']
	];
}



// INSERT INTO `btlec`.`InfosPanonceau` (`Panonceau`, `PoleSAV`, `CentraleSmiley`, `RacineListe`) VALUES ('0097', '', 'DIVERS', '');
// INSERT INTO `btlec`.`InfosNumBT` (`apple_id`, `docubase_pwd`, `docubase_login`, `Raison sociale`, `MandatC3S`, `DateResiliation`, `DateAdhesion`, `Affilie`, `DateFermeture`, `DateOuverture`, `NumBT`, `mots_cles`) VALUES ('', '5741', 'M6895', '', 'Faux', '', '', '1', '30/09/2016', '01/06/2016', '4381', '');

// $datasetSyno[]=[
// 		$oldMag['galec_old'],

// 	];




// INSERT INTO `btlec`.`MagSyno` (`MagasinOld`, `MagasinActuel`) VALUES ('4034', '4005');


/*
		FICHIER SCA3
 */


// $exportSca=DIR_EXPORT_CSV."\\export-sca3_".date('YmdHis').'.csv';
// $exportNumBt=DIR_EXPORT_CSV."\\export-infosnumbt_".date('YmdHis').'.csv';
// $exportPanonco=DIR_EXPORT_CSV."\\export-infospanonceau_".date('YmdHis').'.csv';
// $exportMagSyno=DIR_EXPORT_CSV."\\export-magsyno_".date('YmdHis').'.csv';


$exportSca=DIR_EXPORT_CSV."\\export-sca3.csv";
$exportNumBt=DIR_EXPORT_CSV."\\export-infosnumbt.csv";
$exportPanonco=DIR_EXPORT_CSV."\\export-infospanonceau.csv";
$exportMagSyno=DIR_EXPORT_CSV."\\export-magsyno.csv";




$file = fopen($exportSca, "w") or die("Unable to open file!");
foreach ($datasetSca3 as $key => $value) {
    fputcsv($file, $value);
}

fclose($file);

$file = fopen($exportNumBt, "w") or die("Unable to open file!");
foreach ($datasetBt as $key => $value) {
    fputcsv($file, $value);
}
fclose($file);

$file = fopen($exportPanonco, "w") or die("Unable to open file!");
foreach ($datasetPano as $key => $value) {
    fputcsv($file, $value);
}
fclose($file);


$file = fopen($exportMagSyno, "w") or die("Unable to open file!");
foreach ($magSyno as $key => $value) {
    fputcsv($file, $value);
}

fclose($file);

insertTaskLog($pdoExploit,30, 0, "");
