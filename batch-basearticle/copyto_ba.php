
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
	set_include_path("D:\www\_intranet\_btlecest\\");
} else {
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config/config.inc.php';
require 'Class/Db.php';

$db = new Db();
$pdoQlik = $db->getPdo('qlik');

$file = DIR_IMPORT_GESSICA . "SCEBFART.csv";

$row = 0;

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
	$req = $pdoQlik->query("UPDATE ba LEFT JOIN ba_newfields ON ba.id=ba_newfields.id SET ba.ref=ba_newfields.ref, ba.codelec=ba_newfields.codelec");
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
	$req = $pdoQlik->prepare("INSERT INTO ba_newfields (id, article, dossier, ref, codelec) VALUES (:id, :article, :dossier, :ref, :codelec)");
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


// on récup la date de la dernièremise à jour de la table article de david
// si elle a été mise à jour aujourd'hui, on efface la table ba et on pousse la table david dans ba
$qlikBa = getBasearticle($pdoQlik);
if (isset($qlikBa['DateExecutionScriptQlik'])) {
	$dateImport = $qlikBa['DateExecutionScriptQlik'];
	$today = date('Y-m-d');
	// si ladate 
	if ($dateImport == $today) {
		$pdoQlik->query("DELETE FROM `ba`");
		copyBa($pdoQlik);
	} else {
		echo "pas de mise à jour à faire";
	}
}

// récupération des nouveaux champs dans ba_newfields
if (($handle = fopen($file, "r")) !== FALSE) {
	$pdoQlik->query("DELETE FROM `ba_newfields`");

	$errArr = [];
	$i = 0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if ($row == 0) {
			$entete = $data;
		} else {
			//  on récupère tout sauf les lignes avec majcode à 3
			if ($data[85] != 3) {
				$article= $data[1];
				$dossier=$data[0];
				$ref=$data[20];
				$codelec=$data[19];
				insertNewFields($pdoQlik, $article, $dossier, $ref, $codelec);
			}
		}
		$row++;
	}
	$row = 0;
	fclose($handle);
}


updateBa($pdoQlik);



$pdoQlik->query("OPTIMIZE TABLE ba ");
