<?php

function displayInscr($pdoBt){
	//order by id car date et non datetime
	$req=$pdoBt->prepare("SELECT id_galec,code_bt,nom_mag,centrale,ville,nom,prenom,fonction,date1,date2,repas2,visite,DATE_FORMAT(date_inscr,'%d/%m/%Y') AS dateInscr  FROM salon  ORDER BY id DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);


}
function nbMagSalon($pdoBt){
	$req=$pdoBt->prepare("SELECT id_galec FROM salon GROUP BY id_galec");
	$req->execute();
	return $req->rowCount();


}

function nbRepasFn($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM salon WHERE repas2='oui'");
	$req->execute();
	return $req->rowCount();

}
function dayOneFn($pdoBt){
	$req=$pdoBt->prepare("SELECT date1 FROM salon WHERE date1='oui'");
	$req->execute();
	return $req->rowCount();
}

function dayTwoFn($pdoBt){
	$req=$pdoBt->prepare("SELECT date2 FROM salon WHERE date2='oui'");
	$req->execute();
	return $req->rowCount();
}


function visiteOneFn($pdoBt){
	$req=$pdoBt->prepare("SELECT visite FROM salon WHERE visite='12/06/2018'");
	$req->execute();
	return $req->rowCount();
}


function visiteTwoFn($pdoBt){
	$req=$pdoBt->prepare("SELECT visite FROM salon WHERE visite='13/06/2018'");
	$req->execute();
	return $req->rowCount();
}


function nbInscrJourFn($pdoBt){
  	$req=$pdoBt->prepare("SELECT  DATE_FORMAT(date_inscr, '%d/%m/%y') as dateInscr,count(DISTINCT `nom_mag`) as per_day FROM `salon` GROUP BY `date_inscr`");
  	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function nbVenusParCentrale($pdoBt){
  	$req=$pdoBt->query("SELECT centrale, count(id) as nb FROM `salon` WHERE heure_mardi <>'' OR heure_mercredi<>'' GROUP BY `centrale` ");
  	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function nbVenus($pdoBt){
  	$req=$pdoBt->query("SELECT * FROM `salon` WHERE heure_mardi <>'' OR heure_mercredi<>''");
	return $req->rowCount();
  	// return $req->fetchAll(PDO::FETCH_ASSOC);
}

function nbManuel($pdoBt){
  	$req=$pdoBt->query("SELECT * FROM `salon` WHERE manuel=1");
	return $req->rowCount();
  	// return $req->fetchAll(PDO::FETCH_ASSOC);
}




function arriveesMardi($pdoBt){
  	$req=$pdoBt->query("SELECT count(id) as nb, substr(`heure_mardi`,12,2) as hour, heure_mardi FROM `salon` GROUP by substr(`heure_mardi`,12,2)");
	// return $req->rowCount();
  	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function arriveesMercredi($pdoBt){
  	$req=$pdoBt->query("SELECT count(id) as nb, substr(`heure_mercredi`,12,2) as hour, heure_mercredi FROM `salon` GROUP by substr(`heure_mercredi`,12,2)");
	// return $req->rowCount();
  	return $req->fetchAll(PDO::FETCH_ASSOC);
}