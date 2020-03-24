<?php
include 'config.inc.php';
include 'functions\tasklog.fn.php';

$fieldseparator = ",";
$lineseparator = "\n";

// chemin+ nom des 3 fichiers
$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt"];
$ctbtFile=DIR_IMPORT_GESSICA.$arrFilename[0];
$ctbtParamFile=DIR_IMPORT_GESSICA.$arrFilename[1];
$gessicaFile=DIR_IMPORT_GESSICA.$arrFilename[2];

$row=0;
// paramètre pour les requetes



$gessicaFieldsArr=['id', 'ADH_PAN', 'ADH_NOMADH', 'ADH_NOMCHEF', 'ADH_RS', 'ADH_ADR1', 'ADH_ADR2', 'ADH_CP', 'ADH_ADR3', 'PAY_COD', 'ADH_TEL', 'ADH_TLC', 'ADH_TLX', 'ADH_ADH', 'ADH_MOTAPL', 'DIC_GEL', 'DIC_HYPSUP', 'ADH_SURF', 'ADH_KM', 'ADH_CNUD', 'GEC_COD', 'DIC_CONGAQ', 'ADH_ADHPYR', 'ADH_NOMBAN', 'ADH_CODBAN', 'ADH_CODGUI', 'ADH_CPTBAN', 'ADH_CLERIB', 'ADH_PRL', 'ADH_NBJECH', 'DIC_TRTECH', 'ADH_DATECH', 'ADH_DATOUV', 'ADH_DATFER', 'ADH_OBS', 'MAJ_DATCRE', 'MAJ_OPECRE', 'MAJ_OPEMAJ', 'MAJ_COD', 'MAJ_DATE', 'MAJ_HEURE', 'ADH_BLCPRL', 'ADH_DATDBL', 'DIC_APLADH', 'ADH_ANCPAN', 'ADH_EAN', 'ADH_AR', 'ADH_NOMUTIL', 'ADH_BLBSCO', 'ADH_DATSIT', 'ADH_DATFINEX', 'ADH_TELABR', 'ADH_TLCABR', 'DIC_CSITPAY', 'DIC_CSITENS', 'DIC_CSITNATS', 'ADH_CSITDPT', 'ADH_CSIRET', 'ADH_CCPT', 'ADH_CSITNUM', 'ADH_ENVFACMAG', 'ADH_ENVBLI', 'ADH_BOOL', 'ADH_VALCOD', 'BCG_ADH', 'ADH_ENVMAJC', 'ADH_ENVMAJCFEL', 'ADH_VITESSE', 'DOS_SUIVIENVOI', 'VIT_COD', 'BCT_TYPREL', 'ADH_SOUMICOT', 'ADH_RES', 'DIC_TYPDEST', 'POT_COD', 'ADH_ECH', 'ADH_TYPADH', 'ADH_PANBT', 'ADH_EXREV', 'ADH_GESAPPAN', 'ADH_ADHREMP', 'ADH_DATREMP', 'BAD_COD', 'DIC_ADHRIB', 'ADH_CENTSPE', 'DIC_TYPADHPYR', 'ADH_MODPRP', 'ADH_TPARAM', 'DIC_TYPMAG', 'ADH_EMB', 'DIC_GESADHPYR', 'ADH_EMBPYR', 'ADH_STAT', 'ADH_REPCDE', 'ADH_CA', 'ADH_ZONE1', 'ADH_BLOCC3SFDM', 'ADH_PANBO', 'ADH_DAT1LIV', 'ADH_ANTIC3S', 'GLM_COD', 'DIC_DEMAT', 'ADH_ANCPANBT', 'ADH_TRIMAGBO', 'ADH_ZONE2', 'ADH_ZONE3', 'ADH_EMAILADH', 'ADH_NOMCODE', 'ADH_DEMAT', 'ADH_LIENHYP', 'ADH_NUMACDL', 'ADH_NUMACT', 'AAC_COD', 'ADH_NUMORD', 'ADH_ZONE4', 'ADH_ZONE5', 'ADH_ZONE6', 'ADH_ZONE7', 'ADH_BIC', 'ADH_IBAN', 'ADH_QUOTAMAXCDE', 'ADH_CTRLREQ', 'ADH_CPTGALEC', 'ADH_RUM', 'ADH_DEBGESADHPYR', 'ADH_FINGESADHPYR', 'ADH_ANCEAN', 'date_import'];
$gessicaArgs=join(',', array_map(function(){return '?';},$gessicaFieldsArr));
$gessicaFields=implode(",",$gessicaFieldsArr);


if (($handle = fopen($gessicaFile, "r")) !== FALSE) {
	$errArr=[];
	$req=$pdoQlik->query("DELETE FROM mag_gessica");
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			array_push($data,date("Y-m-d H:i:s"));
			$req=$pdoQlik->prepare("INSERT INTO mag_gessica($gessicaFields) VALUES ($gessicaArgs)");
			if(!$req->execute($data)){
				$err=$req->errorInfo();
				$errArr[$row]['btlec']=$data[0];
				$errArr[$row]['code']=$err[1];
				$errArr[$row]['message']=$err[2];
				$errArr[$row]['db']="mag_gessica";

			}
		}
		$row++;
	}
	$row=0;
	fclose($handle);
}

if(empty($errArr)){
	$logfile="";
	$idTask=26;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=26;
	$ko=1;
	$file = fopen($logfile, "w") or die("Unable to open file!");
	foreach ($errArr as $key => $value) {
		fputcsv($file, $value);
	}

	fclose($file);


	insertTaskLog($pdoExploit,$idTask, $ko, $logfileName);
}


echo "<pre>";
print_r($errArr);
echo '</pre>';