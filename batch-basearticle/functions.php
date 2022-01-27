<?php
function getArticle($pdoQlik, $article, $dossier)
{
	$req = $pdoQlik->prepare("SELECT id from ba where article= :article AND dossier= :dossier");

	$req->execute([
		':article'		=> $article,
		':dossier'		=> $dossier
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function updateBa($pdoQlik)
{
	$req = $pdoQlik->query("UPDATE ba LEFT JOIN ba_newfields ON ba.id=ba_newfields.id SET ba.ref=ba_newfields.ref, ba.codelec=ba_newfields.codelec, ba.article_gessica=ba_newfields.article_gessica");
}

function getNewFields($pdoQlik)
{
	// $req=$pdoQlik->query("SELECT * FROM ba_newfields LIMIT 500 OFFSET 357500");
	$req = $pdoQlik->prepare("SELECT * FROM ba_newfields ", array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function insertNewFields($pdoQlik, $article, $dossier, $ref, $codelec)
{
	$req = $pdoQlik->prepare("INSERT INTO ba_newfields (id, article, dossier,  ref, codelec) VALUES (:id, :article, :dossier, :ref, :codelec)");
	$req->execute([
		':id'	=> $article . $dossier,
		':article'	=> $article,
		':dossier'	=> $dossier,
		':ref'		=> $ref,
		':codelec'	=>$codelec

	]);
}

function copyBa($pdoQlik)
{
	$req = $pdoQlik->query("INSERT INTO ba (`id`, id_ba, `date_import`, `article`, `dossier`, `panf`, `deee`, `codif_deee`, `sorecop`, `pfnp`, `ppi`, `pvp`, `pvi`, `gt`, `lib_gt`, `libelle`, `marque`, `pcb`, `cnuf`, `fournisseur`, `ean`, `poids_colis`, `poids_brut_uv`, `poids_brut_ul`, `stock_entrepot`)  SELECT concat(`GESSICA.CodeArticle`, `GESSICA.CodeDossier`), `id`, `DateExecutionScriptQlik`, `GESSICA.CodeArticle`, `GESSICA.CodeDossier`, `GESSICA.PANF`, `GESSICA.D3E`, `GESSICA.CodifD3E`, `GESSICA.SORECOP`, `GESSICA.PFNP`, `GESSICA.PPI`, `GESSICA.PVP`, `GESSICA.PVI`, `GESSICA.GT`, `GESSICA.LibGT`, `GESSICA.LibelleArticle`, `GESSICA.Marque`, `GESSICA.PCB`, `GESSICA.CodeFournisseur`, `GESSICA.NomFournisseur`, `GESSICA.Gencod`, `GESSICA.PoidsColis`, `GESSICA.PoidsBrutUV`, `GESSICA.PoidsBrutUL`, `CTBT.StkEnt` FROM basearticles");
}


//
function getBasearticle($pdoQlik)
{
	$req = $pdoQlik->query("SELECT * FROM basearticles LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}