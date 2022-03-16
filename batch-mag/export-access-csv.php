<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'functions/tasklog.fn.php';
include 'batch-mag/utils.fn.php';


function getSca3($pdoMag){
	$req=$pdoMag->query("SELECT mag.id as btlec, mag.*, sca3.* FROM sca3 left JOIN mag ON  sca3.btlec_sca=mag.id");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function getMagSyno($pdoMag){
	$req=$pdoMag->query("SELECT * FROM magsyno");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$centraleList=getCentralesExport($pdoMag);

$sca=getSca3($pdoMag);
$magSyno=getMagSyno($pdoMag);



$fieldsName=['id_sca', 'btlec_sca', 'galec_sca', 'deno_sca', 'centrale_sca', 'ad1_sca3', 'ad2_sca3', 'ad3', 'cp_sca', 'ville_sca',
 'tel_sca', 'fax_sca','surface_sca', 'adherent_sca', 'nom_gesap', 'lotus_rbt', 'obs', 'galec_old', 'centrale_doris', 'sorti', 'date_sorti', 'raison_sociale', 'mandat', 'date_resiliation',
'date_adhesion', 'affilie', 'date_fermeture', 'date_ouverture', 'docubase_login', 'docubase_pwd', 'apple_id', 'mots_cles', 'pole_sav_sca', 'centrale_smiley', 'racine_list', 'date_insert', 'date_update'];



foreach ($sca as $key => $oldMag) {
	// certains champ de la table sca3 était tj à vide, on les passe directement à vide pour pouvoir supprimer ultérieurement les champs sans les supprimer

	// Ce que l'on faisait avant de vider les champs:
	// $dateSortie=convertToDateExport(trim($oldMag['date_sortie']));
	// $lotusRbt=$oldMag['lotus_rbt'];
	// $raisonSociale=$oldMag['raison_sociale'];
	// $dateResiliation=convertToDateExport($oldMag['date_resiliation']);
	// $dateAdhesion=convertToDateExport($oldMag['date_adhesion']);
	// $appleId=$oldMag['apple_id']
	$ad3="";
	$dateSortie="";
	$lotusRbt="";
	$raisonSociale="";
	$dateResiliation="";
	$dateAdhesion="";
	$appleId="";
	$centraleSca=convertCentraleExport($oldMag['centrale_sca'], $centraleList);
	$centraleDoris=convertCentraleExport($oldMag['centrale_doris'], $centraleList);
	$centraleSmiley=convertCentraleExport($oldMag['centrale_smiley'], $centraleList);
	$sorti=convertTrueFalseExport($oldMag['gel']);


	$dateFermeture=convertToDateExport($oldMag['date_ferm']);
	$dateOuverture=convertToDateExport($oldMag['date_ouv']);;
	$affilie=($oldMag['affilie']==1)? 2 :0;
	$datasetSca3[]=[
		$oldMag['id_sca'],
		$oldMag['btlec'],
		$oldMag['galec'],
		$oldMag['deno'],
		$centraleSca,
		$oldMag['ad1'],
		$oldMag['ad2'],
		$ad3,
		$oldMag['cp'],
		$oldMag['ville'],
		$oldMag['tel'],
		$oldMag['fax'],
		$oldMag['surface'],
		$oldMag['adherent'],
		$oldMag['nom_gesap'],
		$lotusRbt,
		$oldMag['obs'],
		$oldMag['galec_old'],
		$centraleDoris,
		$sorti,
		$dateSortie,
		$raisonSociale,
		$oldMag['mandat'],
		$dateResiliation,
		$dateAdhesion,
		$affilie,
		$dateFermeture,
		$dateOuverture,
		$oldMag['docubase_login'],
		$oldMag['docubase_pwd'],
		$appleId,
		$oldMag['mots_cles'],
		$oldMag['pole_sav'],
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
