<?php

function caJour($pdoQlik,$date){
	$req=$pdoQlik->prepare("SELECT  sum(CAl) as somme, sum(Colis1) as colis, sum(Palettes1) as palettes FROM statscajour WHERE DateCA= :DateCA");
	$req->execute([
		':DateCA'=>$date

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return 	$data;
	}
	return 0;
}


function sommeCaJour($pdoQlik,$start,$end){

	$req=$pdoQlik->prepare("SELECT sum(CAl) as somme, sum(Colis1) as colis, sum(Palettes1) as palettes FROM statscajour WHERE DateCA BETWEEN :start AND :end");
	$req->execute([
		':start'			=>$start,
		':end'				=>$end
	]);

	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return 	$data;
	}
	return 0;
}
function caMois($pdoQlik,$month,$year){
	$req=$pdoQlik->prepare("SELECT CAl as somme, Colisl as colis, Palettesl as palettes FROM statscamois WHERE AnneeCA= :AnneeCA AND MoisCA= :MoisCA");
	$req->execute([
		':AnneeCA'			=>$year,
		':MoisCA'			=>$month

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);

	return 	$data;


}


function caAnnee($pdoQlik,$lastMonth,$year){
	$req=$pdoQlik->prepare("SELECT sum(CAl) as somme, sum(Colisl) as colis, sum(Palettesl) as palettes FROM statscamois WHERE AnneeCA= :AnneeCA AND (MoisCA>= 1 AND MoisCA<=:MoisCA)");
	$req->execute([
		':AnneeCA'			=>$year,
		':MoisCA'			=>$lastMonth

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return 	$data;
	}
	return 0;
}

function pourcentage($chiffreActuel,$chiffrePrecedent,$diff){
	if($diff!=0 && $chiffrePrecedent!=0){
		return $pourcentage=($diff*100)/$chiffrePrecedent;
	}
	return 0;

}

function valoStock($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM statsstockvalo  order by id desc LIMIT 1");

	return $req->fetch(PDO::FETCH_ASSOC);
}
