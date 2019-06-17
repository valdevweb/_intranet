<?php
function getOperateur($pdoUser){
	$req=$pdoUser->query("SELECT CONCAT(prenom, ' ', nom) as operateur,id FROM intern_users WHERE mask_casse=0 ORDER BY prenom");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getCategorie($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM categories WHERE mask=0 ORDER BY categorie");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getOrigine($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM origines WHERE mask=0 ORDER BY origine");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getTypecasse($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM type_casse WHERE mask=0 ORDER BY type");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getArticleFromBA($pdoQlik, $idArticle){
	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeDossier` as dossier, `GESSICA.CodeArticle` as article, `GESSICA.GT` as gt, `GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as pcb, `GESSICA.PANF` as panf, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur,	id FROM basearticles WHERE id = :id");
	$req->execute(array(
		':id'	=>$idArticle
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

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


function getStockPalette($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM palettes WHERE temp=0 ORDER BY palette");
	if($req){
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		return false;
	}
}



function getPaletteInfo($pdoCasse, $id){
	$req=$pdoCasse->prepare("SELECT *, palettes.id as idpalette, casses.id as idcasse FROM palettes LEFT JOIN casses ON palettes.id=casses.id_palette LEFT join exps ON palettes.id_exp= exps.id WHERE casses.id_palette= :id ORDER BY casses.id");
	$req->execute([
		':id'		=>$id
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}



function getActiveExp($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM exps WHERE exp=0");
	if($req){
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		return false;
	}

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
	$req=$pdoCasse->prepare("SELECT * FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp WHERE exps.id= :id_exp");
	$req->execute([
		':id_exp'			=>$idExp
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function magExpAlreadyExist($pdoCasse, $btlec)
{

	$req=$pdoCasse->prepare("SELECT * FROM exps WHERE exp=0 AND btlec = :btlec");
	$req->execute([
		':btlec'		=>$btlec
	]);
	$result=$req->fetch(PDO::FETCH_ASSOC);
	if(isset($result['id'])){
		return $result;
	}
	else{
		return false;
	}
}