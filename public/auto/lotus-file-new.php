<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';

$taskErrors=[];

function addNewFile($pdoMag, $newFile){
	$req=$pdoMag->prepare("INSERT INTO lotus_imports (date_import, file) VALUES (:date_import, :file)");
	$req->execute([
		':date_import'		=>date('Y-m-d H:i:s'),
		':file'				=>$newFile,

	]);
	// return $req->errorInfo();
	return $pdoMag->lastInsertId();

}


function getGalec($pdoMag,$ldName){
	$req=$pdoMag->prepare("SELECT galec_sca as galec,deno_sca as deno,racine_list FROM sca3 WHERE racine_list=:racine_list");
	$req->execute([
		':racine_list'		=>$ldName
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}


	return false;
}




// $lastImport=getLastImport($pdoUser);

 //  faire un systeme quotidien qui vérife si nouveau fichier à l'emplacement prévu

//1- récup date der fichier importé dans db lotus_import ou verfi si fichier à la date du jour
//2- vérifier si fichier avec date sup à celui de  la db
//3- si oui inserer en db
//

/*----------------------------------------------------------------------------------------------
FICHIER A TRAITER ? => newFile

------------------------------------------------------------------------------------------------ */

$lotusFileList = scandir(DIR_LOTUS_CSV);
$newFile='';
// vérif si fichier à la date du jour
foreach ($lotusFileList as $filename){
// récup la date de dépot du fichier
	if($filename!='.' && $filename!='..'){
		$fileDate=date ('Y-m-d H:i:s', filemtime(DIR_LOTUS_CSV.'\\'.$filename));
		$fileDate=new DateTimeImmutable($fileDate);
		$today=new DateTime();
		if($fileDate->format('Y-m-d') == (new DateTime())->format('Y-m-d')){
			if($fileDate->format('Y-m-d') == $today->format('Y-m-d')){
				$newFile=$filename;
			}
		}

	}

}
/*----------------------------------------------------------------------------------------------
TRAITEMENT NOUVEAU FICHIER

----------------------------------------------------------------------------------------------- */
// $newFile="lotus.txt";


if(!empty($newFile)){
	require('public\auto\lotus-file-import.php');
	require('public\auto\lotus-errors.php');
	// require('lotus-compare.php');
// echo $newFile;
}


if(http_response_code()==200){
	insertTaskLog($pdoExploit,6,0 ,"" );
}

// var_dump(http_response_code());
