<?php

$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec";
$litigeParam="WHERE (id_etat != 1 AND id_etat != 20)|| commission != 1";
$litigeMod=" ORDER BY dossiers.dossier DESC";



$statutQuery="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat, etat.occ_etat FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
";
$typoQuery="SELECT sum(valo) as valo, dossiers.id_typo, typo.typo, count(dossiers.id) as nbTypo FROM dossiers
LEFT JOIN typo ON id_typo=typo.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
";
// requete par défaut
$dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
$dateEnd=date('Y-m-d H:i:s');

$statutParam=" WHERE date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY etat ORDER BY occ_etat, etat.etat";
$typoParam=" WHERE date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY id_typo ORDER BY typo";



if(isset($paramList)){
	$paramList=array_filter($paramList);
	$joinParam=function($value){
		if(!empty($value)){
			return '('.$value.')';
		}
	};

	$litigeParam=" WHERE ".join(' AND ',array_map($joinParam,$paramList));

	$statutParam=$litigeParam." GROUP BY etat ORDER BY occ_etat, etat.etat";
	$typoParam=$litigeParam." GROUP BY id_typo ORDER BY typo";

		// 2 requetes types : une sur la table dossier "seule", une sur la table dossier jointe à la table article
	if(isset($_SESSION['form-data-deux']['article'])){
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
	// if(isset($_SESSION['form-data']['export_excel'])){
	// 	$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, details.*, details.ean as ean_detail, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat FROM dossiers
	// 	LEFT JOIN details ON dossiers.id=details.id_dossier
	// 	LEFT JOIN etat ON id_etat=etat.id
	// 	LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE";
	// 	include('xl-selected.php');

	// }
}
