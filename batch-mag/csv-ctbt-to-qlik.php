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

$fieldseparator = ",";
$lineseparator = "\n";

// chemin+ nom des 3 fichiers
$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt"];
$ctbtFile=DIR_IMPORT_GESSICA.$arrFilename[0];
$ctbtParamFile=DIR_IMPORT_GESSICA.$arrFilename[1];
$gessicaFile=DIR_IMPORT_GESSICA.$arrFilename[2];

$row=0;
// paramètre pour les requetes
$ctbtFieldsArr=['id','MAG_LIB','MAG_LIBR','MAG_MAI','MAG_PAN','MAG_ANCPAN','MAG_SCA','MAG_BAS','MAG_TYPINF','CRE_OPE','CRE_DATE','CRE_HEURE','MAJ_OPE','MAJ_DATE','MAJ_HEURE','MAJ_COD','MAG_LIVSCA','MAG_PANGAL','date_import'];
$ctbtArgs=join(',', array_map(function(){return '?';},$ctbtFieldsArr));
$ctbtFields=implode(', ',$ctbtFieldsArr);

// (id, MAG_LIB, MAG_LIBR, MAG_MAI, MAG_PAN, MAG_ANCPAN, MAG_SCA, MAG_BAS, MAG_TYPINF, CRE_OPE, CRE_DATE, CRE_HEURE, MAJ_OPE, MAJ_DATE, MAJ_HEURE, MAJ_COD, MAG_LIVSCA, MAG_PANGAL, date_import


if (($handle = fopen($ctbtFile, "r")) !== FALSE) {
	$errArr=[];

	$req=$pdoQlik->query("DELETE FROM mag_ctbt");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			array_push($data,date('Y-m-d H:i:s'));
			$req=$pdoQlik->prepare("INSERT INTO mag_ctbt($ctbtFields) VALUES ($ctbtArgs)");
			if(!$req->execute($data)){
				$err=$req->errorInfo();
				$errArr[$row]['btlec']=$data[0];
				$errArr[$row]['code']=$err[1];
				$errArr[$row]['message']=$err[2];
				$errArr[$row]['db']="mag_ctbt";
			}
		}
		$row++;
	}
	$row=0;
	fclose($handle);
}

if(empty($errArr)){
	$logfile="";
	$idTask=24;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=$idTask.'-'.date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=24;
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
