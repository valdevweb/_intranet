<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';


function getDetailDev($pdoOcc){

	$req=$pdoOcc->query("SELECT * FROM cdes_detail");
	return $req->fetchAll();
}
function getDetailProd($pdoOcc){

	$req=$pdoOcc->query("SELECT * FROM occasion.cdes_detail ");
	return $req->fetchAll();
}

function existInProd($pdoOcc, $idDev){

	$req=$pdoOcc->prepare("SELECT * FROM occasion.cdes_detail WHERE id=:id");
	$req->execute([
		':id'		=>$idDev
	]);
	return $req->fetch();
}
function add($pdoOcc, $id){
	$req=$pdoOcc->prepare("INSERT INTO occasion.cdes_detail SELECT * from _occasion.cdes_detail WHERE _occasion.cdes_detail.id= :id");

	$req->execute([
		':id'		=>$id

	]);
	return $req->rowCount();

}

$devData=getDetailDev($pdoOcc);
$prodData=getDetailProd($pdoOcc);
foreach ($devData as $key => $dev) {
	$found=existInProd($pdoOcc, $dev['id']);
	if(empty($found)){
		add($pdoOcc, $dev['id']);
		echo "manque ".$dev['id'].' commande '.$dev['id_cde'];
		echo "<br>";

	}else{
		// "trouve ".$dev['id'].' commande '.$dev['id_cde'];
	}
}