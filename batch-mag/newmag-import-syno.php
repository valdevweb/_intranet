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

function getMagSyno($pdoBt){
	$req=$pdoBt->query("SELECT * FROM magsyno");
	return $req->fetchAll(PDO::FETCH_GROUP);
}

function alreadyInSyno($pdoMag,$id){
	$req=$pdoMag->query("SELECT id FROM magsyno WHERE id={$id}");
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}

function addMagSyno($pdoMag,$data,$galec){
	$req=$pdoMag->prepare("INSERT INTO magsyno (btlec_old, btlec_new, galec, date_insert ) VALUES(:btlec_old, :btlec_new, :galec, :date_insert)");
	$req->execute([
		':btlec_old'	=>$data['MagasinOld'],
		':btlec_new'	=>$data['MagasinActuel'],
		':galec'	=>$galec,
		':date_insert'	=>date('Y-m-d H:i:s')
	]);
	// return $req->errorInfo();
	return $req->rowCount();

}

$magsyno=getMagSyno($pdoBt);
$listPanoBt=getBtlecGalec($pdoBt);

$synoUpdated=0;
$synoAdded=0;
	// echo "<pre>";
	// print_r($magsyno);
	// echo '</pre>';
$notFound=[];
$errArr=[];

foreach ($magsyno as $key => $syno) {
	if(!alreadyInSyno($pdoMag, $key)){
		$galec=convertBtlec($syno[0]['MagasinActuel'], $listPanoBt);
 		$added=addMagSyno($pdoMag,$syno[0],$galec);

 		if($added==1){
 			$synoAdded++;
 		}else{
 			$errArr[$row]['btlec']=$syno[0]['MagasinActuel'];
			$errArr[$row]['galec']=$galec;
			$errArr[$row]['msg']="impossible d'ajouter le magasin";

 		}
	}
}
echo "mag syno ajoutés ".$synoAdded;

if(empty($errArr)){
	$logfile="";
	$idTask=29;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=$idTask.'-'.date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=29;
	$ko=1;
	$file = fopen($logfile, "w") or die("Unable to open file!");
	foreach ($errArr as $key => $value) {
		fputcsv($file, $value);
	}
	fclose($file);
	insertTaskLog($pdoExploit,$idTask, $ko, $logfileName);
}
