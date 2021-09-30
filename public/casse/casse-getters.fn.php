<?php
function getOperateur($pdoUser){
	$req=$pdoUser->query("SELECT CONCAT(prenom, ' ', nom) as operateur,id FROM intern_users WHERE mask_casse=0 ORDER BY prenom");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}





// function getArticleFromBA($pdoQlik, $idArticle){
// 	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeDossier` as dossier, `GESSICA.CodeArticle` as article, `GESSICA.GT` as gt, `GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as pcb, `GESSICA.PANF` as panf, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur,`GESSICA.PFNP` as pfnp,`GESSICA.D3E` as deee, `GESSICA.SORECOP` as sacem,`GESSICA.CodifD3E` as deee_codif,	id FROM basearticles WHERE id = :id");
// 	$req->execute(array(
// 		':id'	=>$idArticle
// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }

function getCasse($pdoCasse, $idCasse){
	$req=$pdoCasse->prepare("SELECT *,
		casses.id as cassesid,
		web_users.intern_users.id as usersid,
		categories.id as categoriesid,
		origines.id as originesid,
		type_casse.id as typesid,
		palettes.id as palettesid,
		DATE_FORMAT(date_casse, '%d-%m-%Y') as dateCasse

		FROM casses
		LEFT JOIN   web_users.intern_users ON casses.id_operateur = web_users.intern_users.id
		LEFT JOIN categories ON id_categorie=categories.id
		LEFT JOIN origines ON id_origine=origines.id
		LEFT JOIN type_casse ON id_type=type_casse.id
		LEFT JOIN  palettes ON id_palette=palettes.id
		WHERE  casses.id= :id");
	$req->execute([
		':id'		=>$idCasse
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getCmt($pdoCasse, $id){
	$req=$pdoCasse->prepare("SELECT *, DATE_FORMAT(date_cmt, '%d-%m-%Y') as dateCmt FROM cmt WHERE id_casse= :id ORDER BY date_cmt DESC");
	$req->execute([
		':id'		=>$id
	]);
	$result=$req->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($result)){
		return $result;
	}
	else{
		return false;
	}
}







function getPaletteInfo($pdoCasse, $id){
	$req=$pdoCasse->prepare("SELECT *, palettes.id as idpalette, casses.id as idcasse, DATE_FORMAT(date_dd_pilote,'%d-%m-%Y') as dateddpilote, DATE_FORMAT(date_retour_pilote,'%d-%m-%Y') as dateretourpilote,DATE_FORMAT(date_info_mag,'%d-%m-%Y') as dateinfomag,DATE_FORMAT(date_delivery,'%d-%m-%Y') as datedelivery,DATE_FORMAT(palettes.date_clos,'%d-%m-%Y') as dateclos FROM palettes LEFT JOIN casses ON palettes.id=casses.id_palette LEFT join exps ON palettes.id_exp= exps.id WHERE casses.id_palette= :id ORDER BY casses.id");
	$req->execute([
		':id'		=>$id
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getSerialsPalette($pdoCasse, $idPalette){
	$req=$pdoCasse->prepare("SELECT id_casse, serial_nb FROM serials
		LEFT JOIN casses ON id_casse = casses.id
		LEFT JOIN palettes ON casses.id_palette=palettes.id
		WHERE id_palette=:id");
	$req->execute([
		':id'			=>$idPalette,
	]);
	return $req->fetchAll(PDO::FETCH_GROUP);
}
function getSerialsCasse($pdoCasse, $idCasse){
	$req=$pdoCasse->prepare("SELECT id_casse, serial_nb FROM serials
		WHERE id_casse=:id");
	$req->execute([
		':id'			=>$idCasse,
	]);
	return $req->fetchAll(PDO::FETCH_GROUP);
}


function getThisExp($pdoCasse,$idPalette){
	$req=$pdoCasse->prepare("SELECT * FROM palettes LEFT JOIN exps ON palettes.id_exp=exps.id WHERE palettes.id= :id_palette");
	$req->execute([
		':id_palette'	=>$idPalette
	]);
	$result=$req->fetch(PDO::FETCH_ASSOC);
	if(isset($result['id'])){
		return $result;
	}
	else{
		return false;
	}

}


function getDetailExp($pdoCasse,$idExp){
	$req=$pdoCasse->prepare("SELECT * FROM palettes WHERE id_exp= :id_exp");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $result=$req->fetchAll(PDO::FETCH_ASSOC);
}

function getExpAndPalette($pdoCasse,$idExp){
	$req=$pdoCasse->prepare("SELECT *, palettes.id as paletteid,exps.id as expid FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp WHERE exps.id= :id_exp");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



function getExpPaletteCasse($pdoCasse,$idExp){
	//  on ne fait que la somme des quantité puisque dans la facture, on multiplie le prix par la quantité
	$req=$pdoCasse->prepare("SELECT concat (article,dossier) as artdossier,sum(uvc) as uvc, valo, pfnp, deee, sacem, deee_codif,dossier, article, palettes.id as paletteid,exps.id as expid, btlec, gt FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp LEFT JOIN casses ON palettes.id= casses.id_palette WHERE exps.id= :id_exp GROUP BY concat (article,dossier) ORDER BY gt, article");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function getPaletteList($pdoCasse,$idExp){
	$req=$pdoCasse->prepare("SELECT  DISTINCT palette  FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp  WHERE exps.id= :id_exp");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $req->fetchAll(PDO::FETCH_COLUMN);
}



function getContremarqueList($pdoCasse,$idExp){
	$req=$pdoCasse->prepare("SELECT  DISTINCT contremarque  FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp  WHERE exps.id= :id_exp");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $req->fetchAll(PDO::FETCH_COLUMN);
}


function getClosExp($pdoCasse){
	$req=$pdoCasse->query("SELECT *, DATE_FORMAT(date_delivery,'%d-%m-%y') as datedelivery,  DATE_FORMAT(date_fac,'%d-%m-%y') as datefac, exps.id as expid, palettes.id as paletteid FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp   WHERE
		exps.exp=1 AND (date_delivery IS NOT NULL OR date_fac IS NOT NULL)");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function sumDeclare($pdoCasse){
	$req=$pdoCasse->query("SELECT sum(valo) as valoTotal FROM casses");
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getCasseHS($pdoCasse){
	$req=$pdoCasse->query("SELECT sum(valo) as sumhs FROM casses LEFT JOIN palettes ON casses.id_palette=palettes.id WHERE palette LIKE '%HS%'");
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getExpWaiting($pdoCasse){
	$req=$pdoCasse->query("SELECT sum(valo) as sumvalo FROM casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp=exps.id WHERE palette NOT LIKE '%HS%' AND exps.exp=0");
	return $req->fetch(PDO::FETCH_ASSOC);
}
function getNoExpYet($pdoCasse){
	$req=$pdoCasse->query("SELECT sum(valo) as sumvalo FROM casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp=exps.id WHERE palettes.id_exp IS NULL AND  palette NOT LIKE '%HS%'");
	return $req->fetch(PDO::FETCH_ASSOC);
}
//  utilisé pour la facturation
// function getArticleByGt($pdoCasse, $idExp, $gt){
// 	if($gt=='blanc'){
// 		$gtParam=' (gt=1 OR gt=2) ';
// 	}elseif($gt=='brun'){
// 		$gtParam=' (gt=3 OR gt=4 OR gt=5) ';

// 	}
// 	elseif($gt='gris'){
// 		$gtParam='(gt=6 OR gt=7 OR gt=8 OR gt=9 OR gt=10) ';
// 	}

// 	$req=$pdoCasse->prepare("SELECT concat (article,dossier) as artdossier,sum(uvc) as uvc,sum(valo) as valo,sum(pu) as pu ,sum(pfnp) as pfnp,sum(deee) as deee, sum(sacem) as sacem, deee_codif,dossier, article, palettes.id as paletteid,exps.id as expid, btlec
// 	 FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp LEFT JOIN casses ON palettes.id= casses.id_palette WHERE exps.id= :id_exp AND $gtParam GROUP BY concat (article,dossier) ORDER BY article");
// 	$req->execute([
// 		':id_exp'			=>$idExp,

// 	]);
// 	return $req->fetchAll(PDO::FETCH_ASSOC);
// }