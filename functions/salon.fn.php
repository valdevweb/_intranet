<?php

function displayInscr($pdoBt){
	$req=$pdoBt->prepare("SELECT id_galec,code_bt,nom_mag,centrale,ville,nom,prenom,fonction,date1,date2,repas2,visite,DATE_FORMAT(date_inscr,'%d/%m/%Y') AS dateInscr  FROM salon  ORDER BY id_galec");
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
	$req=$pdoBt->prepare("SELECT vsite FROM salon WHERE visite='12/06/2018'");
	$req->execute();
	return $req->rowCount();
}


function visiteTwoFn($pdoBt){
	$req=$pdoBt->prepare("SELECT vsite FROM salon WHERE visite='13/06/2018'");
	$req->execute();
	return $req->rowCount();
}
		// <li>Nombre d'inscrits : </li>
		// 		<li>Nombre de magasin inscrits : </li>
		// 		<li>Nombre de repas : </li>
		// 		<li>Nombre d'inscrits le 12/06/2018 : </li>
		// 		<li>Nombre d'inscrits le 13/06/2018 : </li>
		// 		<li>Nombre de visite le 12/06/2018 : </li>
		// 		<li>Nombre de visite le 13/06/2018 : </li>