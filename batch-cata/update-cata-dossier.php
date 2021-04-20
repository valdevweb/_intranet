<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';

function getCataDossier($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM cata_dossiers ");
	return $req->fetchAll();
}
function getInfoOp($pdoQlik,$codeOp){

	$req=$pdoQlik->prepare("SELECT * FROM cata_op WHERE code_op= :code_op");
	$req->execute([
		'code_op'	=>$codeOp
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateDossier($pdoQlik,$id,$libelle, $dateStart, $dateEnd){
	$req=$pdoQlik->prepare("UPDATE cata_dossiers SET libelle= :libelle, date_start= :date_start, date_end= :date_end WHERE id= :id");
	$req->execute([
		':libelle'		=>$libelle,
		':date_start'		=>$dateStart,
		':date_end'		=>$dateEnd,
		':id'		=>$id,
	]);
	return $req->rowCount();
}
$listDossier=getCataDossier($pdoQlik);
	// echo "<pre>";
	// print_r($listDossier);
	// echo '</pre>';
foreach ($listDossier as $key => $dossier) {
	$infoOp=getInfoOp($pdoQlik,$dossier['code_op']);
	if(!empty($infoOp))
		updateDossier($pdoQlik,$dossier['id'], $infoOp['libelle'], $infoOp['date_start'], $infoOp['date_end']);

}
