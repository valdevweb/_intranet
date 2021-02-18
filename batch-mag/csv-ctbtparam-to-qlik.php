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





$ctbtParamFieldsArr=
['id','PID_RAISOC','PID_ENSEIG','PID_ADR1','PID_ADR2','PID_PAY','PID_CPO','PID_TLP','PID_TLC','PID_TLX','PID_TVAINT','CRE_OPE','CRE_DATE','CRE_HEURE','MAJ_DATE','MAJ_HEURE','MAJ_COD','PID_TRFENT','PID_TRFBO','PID_TRFCOM','PID_TRFCAE','PID_IMPETI','PID_CODREF','PID_DEVGES','PID_DELRLQ','PID_INS','PID_FACSAV','PID_ECHPIC','PID_ECHMAS','PID_TVA','PID_FORLIV','PID_VTECDE','PID_TRFSAV','PID_EXTSAV','PID_LIVDIR','PID_ARCOPL','PID_ARMAPL','PID_FACTSA','PID_CALGUE','PID_POINT','PID_DATGUE','PID_TRFCAI','PID_MAGFAC','PID_FACFIN','PID_MAGSTO','PID_CRE','PID_RAD','PID_SENCAI','PID_TYPMRG','PID_HLE','PID_DISENG','PID_GESGT','PID_EAN','PID_PIEFAC','PID_GESCAE','PID_TEXTENT1','PID_TEXTENT2','PID_TEXTENT3','PID_ORDCHQ','PID_EDTGAR','PID_TXTREM','PID_TXTRPV','PID_TXTRPA','PID_TXTEXP','PID_TXTGAR','PID_DATED','PID_DATEP','PID_DATER','PID_NOFAC','PID_DEBFAC','PID_FINFAC','PID_DEBCOT','PID_FINCOT','PID_GUELIV','PID_CON','PID_TVACOM','PID_GTRLVS','PID_VILLE','PID_ENSSAV','PID_DEBSAV','PID_FINSAV','PID_IDEBB','PID_DEBAVS','PID_FINAVS','PID_ECHAVS','PID_GESRES','PID_TYPCEN','PID_IDIV1','PID_IDIV2','PID_CDIV1','PID_CDIV2','date_import'];
$ctbtParamArgs=join(',', array_map(function(){return '?';},$ctbtParamFieldsArr));
$ctbtParamFields=implode(', ',$ctbtParamFieldsArr);



if (($handle = fopen($ctbtParamFile, "r")) !== FALSE) {
	$errArr=[];
	$req=$pdoQlik->query("DELETE FROM mag_ctbt_param");

	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			if(!empty($data[0])){
				array_push($data,date("Y-m-d H:i:s"));
				$req=$pdoQlik->prepare("INSERT INTO mag_ctbt_param($ctbtParamFields) VALUES ($ctbtParamArgs)");
				if(!$req->execute($data)){
					$err=$req->errorInfo();
					$errArr[$row]['btlec']=$data[0];
					$errArr[$row]['code']=$err[1];
					$errArr[$row]['message']=$err[2];
					$errArr[$row]['db']="mag_ctbt_param";
				}




			}
		}
		$row++;
	}
	$row=0;
	fclose($handle);
}

if(empty($errArr)){
	$logfile="";
	$idTask=25;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=$idTask.'-'.date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=25;
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

