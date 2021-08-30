<?php

$litigeQueryDefault="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec";
$litigeParamDefault="WHERE (id_etat != 1 AND id_etat != 20)|| commission != 1";
$litigeModDefault=" ORDER BY dossiers.dossier DESC";



$statutQueryDefault="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat, etat.occ_etat FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
";
$typoQueryDefault="SELECT sum(valo) as valo, dossiers.id_typo, typo.typo, count(dossiers.id) as nbTypo FROM dossiers
LEFT JOIN typo ON id_typo=typo.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
";
// requete par défaut
$dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
$dateEnd=date('Y-m-d H:i:s');

$statutParamDefault=" WHERE date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY etat ORDER BY occ_etat, etat.etat";
$typoParamDefault=" WHERE date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY id_typo ORDER BY typo";





if(!isset($paramList) && !isset($notifQuery)){

	$litigeQuery=$litigeQueryDefault;
	$litigeParam=$litigeParamDefault;
	$litigeMod=$litigeModDefault;



	$statutQuery=$statutQueryDefault;
	$typoQuery=$typoQueryDefault;

	$statutParam=$statutParamDefault;
	$typoParam=$typoParamDefault;

}elseif(isset($paramList) && !isset($notifQuery)){
	$paramList=array_filter($paramList);
	$joinParam=function($value){
		if(!empty($value)){
			return '('.$value.')';
		}
	};

	$litigeParam=" WHERE ".join(' AND ',array_map($joinParam,$paramList));
	$statutParam=$litigeParam." GROUP BY etat ORDER BY occ_etat, etat.etat";
	$typoParam=$litigeParam." GROUP BY id_typo ORDER BY typo";



	if(!isset($_SESSION['form-data-deux']['article'])){
		$litigeQuery=$litigeQueryDefault;
		$litigeMod=$litigeModDefault;
		$statutQuery=$statutQueryDefault;
		$typoQuery=$typoQueryDefault;

	}else{
		$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, details.*, details.ean as ean_detail, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec ";
		$litigeMod=" GROUP BY dossiers.id ORDER BY dossiers.dossier DESC ";
		$statutQuery="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat, etat.occ_etat FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN details ON dossiers.id=details.id_dossier ";
		$typoQuery="SELECT sum(valo) as valo, dossiers.id_typo, typo.typo, count(dossiers.id) as nbTypo FROM dossiers LEFT JOIN typo ON id_typo=typo.id LEFT JOIN details ON dossiers.id=details.id_dossier";


	}

}elseif(isset($notifQuery)){
	$statutQuery=$statutQueryDefault;
	$typoQuery=$typoQueryDefault;
	$statutParam=$statutParamDefault;
	$typoParam=$typoParamDefault;

	if($notifQuery=="dial"){
		$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat
		FROM dial
		LEFT JOIN dossiers ON dial.id_dossier=dossiers.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec";
		$litigeParam="WHERE read_dial=0 AND dial.mag=1";
		$litigeMod=" GROUP BY dossiers.id ORDER BY dossiers.dossier DESC";

	}
	if($notifQuery=="action"){
		$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat
		FROM action
		LEFT JOIN dossiers ON action.id_dossier=dossiers.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec";
		$litigeParam="WHERE read_action=0 AND id_contrainte=5";
		$litigeMod=" GROUP BY dossiers.id ORDER BY dossiers.dossier DESC";


	}
}

