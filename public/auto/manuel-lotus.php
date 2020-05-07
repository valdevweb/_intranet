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




$newFile="LOTUS_20200505.txt";


if(!empty($newFile)){
	require('public\auto\manuel-lotus-file-import.php');
	// require('public\auto\manuel-lotus-errors.php');

// echo $newFile;
}


if(http_response_code()==200){
	insertTaskLog($pdoExploit,6,0 ,"" );
}

// var_dump(http_response_code());
