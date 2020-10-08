<?php

// fonction utilisées pour convertir les données pendat l'import
function convertToDate($data){
	$date=NULL;
	if(!empty($data)){
		$date=DateTime::createFromFormat('d/m/Y', $data);
		return $date->format("Y-m-d");
	}

	return $date;
}

function convertCentrale($data,$centraleList){
	$data=trim($data);
	if(trim($data=="SCACORSE")){
		$data="CORSE";
	}
	if(!empty($data)){
		$centrale=array_keys($centraleList,$data);
		return $centrale[0];
	}
	return NULL;
}



function convertTrueFalse($data){
	if(!empty(trim($data))){
		if(trim($data=="Faux")){
			return 0;
		}elseif(trim($data=="Vrai")){
			return 9;
		}else{
			return 99;
		}
	}
	return 99;
}

function convertGalec($data,$listPanoBt){
	if(array_key_exists($data ,$listPanoBt)){
		return $listPanoBt[$data];
	}
	return '';
}

function convertBtlec($data,$listPanoBt){
	if($btlec=array_search($data,$listPanoBt )){
		return $btlec;
	}
	return NULL;
}



function getCentrales($pdoMag){
	$req=$pdoMag->query("SELECT id_ctbt, centrale FROM centrales");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}




// function getBtlecGalec($pdoBt){
// 	$req=$pdoBt->query("SELECT galec, btlec FROM sca3");
// 	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
// }

function getMagSav($pdoSav){
	$req=$pdoSav->query("SELECT * FROM mag");
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

// fonction utilisées pour convertir les données pendat l'export
function getCentralesExport($pdoMag){
	$req=$pdoMag->query("SELECT centrale, id_ctbt FROM centrales");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}



function convertCentraleExport($data,$centraleList){
	$data=trim($data);

	if(!empty($data)){
		$centrale=array_keys($centraleList,$data);
		return $centrale[0];
	}
	return NULL;
}


function convertTrueFalseExport($data){

		if($data==0){
			return "Faux";
		}elseif($data==9){
			return "Vrai";
		}else{
			return "";
		}

}

function convertToDateExport($data){
	$date=NULL;
	if(!empty($data)){
		$date=new DateTime($data);
		return $date->format("d/m/Y");
	}

	return $date;
}