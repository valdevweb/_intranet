<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
function insertCdes($pdoQlik){

	$req=$pdoQlik->query("INSERT INTO cdes_encours
		(id_cde, id_detail, date_cde, date_liv_init, date_liv, cnuf, fournisseur, gt, article, dossier, ean, ref, libelle_art, marque, libelle_op, date_start, date_end, qte_init, qte_cde, cond_carton, qte_uv_cde)
		SELECT
		cdes_fou.id, cdes_fou_details.id, cdes_fou.date_cde, cdes_fou_qte_init.date_liv_init, cdes_fou.date_liv, ba.cnuf, ba.fournisseur, cdes_fou.gt, ba.article,	ba.dossier,	ba.ean, ba.ref,	ba.libelle , ba.marque, cata_dossiers.libelle , cata_dossiers.date_start,	cata_dossiers.date_end,	qte_cde_init, cdes_fou_details.qte_cde, cdes_fou_details.cond_carton, cdes_fou_details.qte_uv_cde
		FROM cdes_fou
		LEFT JOIN cdes_fou_details  ON cdes_fou_details.id_cde=cdes_fou.id
		LEFT JOIN cdes_fou_qte_init ON cdes_fou_details.id=cdes_fou_qte_init.id_detail
		LEFT JOIN ba ON id_artdos=ba.id
		LEFT JOIN cata_dossiers ON cdes_fou_details.dossier=cata_dossiers.dossier WHERE cdes_fou.date_cde IS NOT NULL AND cdes_fou_details.qte_cde IS NOT NULL");
	return $req->fetchAll();
}

// function insertCdes($pdoQlik){

// 	$req=$pdoQlik->query("INSERT INTO cdes_encours
// 		(id, id_cde, date_cde, fournisseur, gt, article, dossier, ref, libelle_art, marque, libelle_op, date_start, date_end, qte_init, qte_cde, cond_carton, qte_uv_cde)
// 		SELECT
// 		cdes_fou_details.id, cdes_fou.id, cdes_fou.date_cde, ba.fournisseur, cdes_fou.gt,ba.article,	ba.dossier,	ba.ref,	ba.libelle , ba.marque, cata_dossiers.libelle , cata_dossiers.date_start,	cata_dossiers.date_end,	qte_cde_init, cdes_fou_details.qte_cde, cdes_fou_details.cond_carton, cdes_fou_details.qte_uv_cde
// 		FROM cdes_fou
// 		LEFT JOIN cdes_fou_details  ON cdes_fou_details.id_cde=cdes_fou.id
// 		LEFT JOIN cdes_fou_qte_init ON cdes_fou_details.id=cdes_fou_qte_init.id_detail
// 		LEFT JOIN ba ON id_artdos=ba.id
// 		LEFT JOIN cata_dossiers ON cdes_fou_details.dossier=cata_dossiers.dossier");
// 	return $req->fetchAll();
// }

$pdoQlik->query("DELETE FROM `cdes_encours`");
$pdoQlik->query("OPTIMIZE TABLE cdes_encours ");
$pdoQlik->query("OPTIMIZE TABLE ba ");
$pdoQlik->query("OPTIMIZE TABLE cdes_fou_qte_init ");
$pdoQlik->query("OPTIMIZE TABLE cdes_fou ");
$pdoQlik->query("OPTIMIZE TABLE cata_dossiers");

insertCdes($pdoQlik);
$pdoQlik->query("OPTIMIZE TABLE cdes_encours ");
$pdoQlik->query("UPDATE `cdes_encours` SET `id`=`id_detail`, date_import=NOW()");
$pdoQlik->query("OPTIMIZE TABLE cdes_encours ");
