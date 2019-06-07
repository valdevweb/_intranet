<?php

function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.article,details.tarif,details.qte_cde, details.qte_litige,details.dossier_gessica,details.descr,details.fournisseur,details.pj,details.valo_line,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}





function getInfos($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		WHERE  dossiers.id= :id ");

	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getAnalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
		LEFT JOIN gt ON id_gt=gt.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getComment($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM dial WHERE id_dossier= :id AND mag =3 LIMIT 1");
	$req->execute([
		':id'	=>$_GET['id']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}



// function getDial($pdoLitige){

// 	$req=$pdoLitige->prepare("SELECT * FROM dial WHERE id_dossier= :id AND mag <>3");
// 	$req->execute([
// 		':id'	=>$_GET['id']
// 	]);
// 	return $req->fetchAll(PDO::FETCH_ASSOC);


// }