
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config/config.inc.php';
require 'Class/Db.php';

$db=new Db();
$pdoQlik=$db->getPdo('qlik');

$file=DIR_IMPORT_GESSICA."SCEBFART.csv";



$row=0;

function getArticle($pdoQlik, $article, $dossier){
	$req=$pdoQlik->prepare("SELECT id from ba where article= :article AND dossier= :dossier");

	$req->execute([
		':article'		=>$article,
		':dossier'		=>$dossier
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function updateBa($pdoQlik){
	$req=$pdoQlik->query("UPDATE ba LEFT JOIN ba_ref ON ba.id=ba_ref.id SET ba.ref=ba_ref.ref");
}

function getRef($pdoQlik){
	// $req=$pdoQlik->query("SELECT * FROM ba_ref LIMIT 500 OFFSET 357500");
	$req=$pdoQlik->prepare("SELECT * FROM ba_ref ", array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function insertRef($pdoQlik, $article, $dossier, $ref){
	$req=$pdoQlik->prepare("INSERT INTO ba_ref (id, article, dossier, ref) VALUES (:id, :article, :dossier, :ref)" );
	$req->execute([
		':id'	=>$article.$dossier,
		':article'	=>$article,
		':dossier'	=>$dossier,
		':ref'		=>$ref

	]);
}

function copyBa($pdoQlik){
	$req=$pdoQlik->query("INSERT INTO ba (`id`, id_ba, `date_import`, `article`, `dossier`, `panf`, `deee`, `codif_deee`, `sorecop`, `pnfp`, `ppi`, `pvp`, `pvi`, `gt`, `lib_gt`, `libelle`, `marque`, `pcb`, `cnuf`, `fournisseur`, `ean`, `poids_colis`, `poids_brut_uv`, `poids_brut_ul`, `stock_entrepot`)  SELECT concat(`GESSICA.CodeArticle`, `GESSICA.CodeDossier`), `id`, `DateExecutionScriptQlik`, `GESSICA.CodeArticle`, `GESSICA.CodeDossier`, `GESSICA.PANF`, `GESSICA.D3E`, `GESSICA.CodifD3E`, `GESSICA.SORECOP`, `GESSICA.PFNP`, `GESSICA.PPI`, `GESSICA.PVP`, `GESSICA.PVI`, `GESSICA.GT`, `GESSICA.LibGT`, `GESSICA.LibelleArticle`, `GESSICA.Marque`, `GESSICA.PCB`, `GESSICA.CodeFournisseur`, `GESSICA.NomFournisseur`, `GESSICA.Gencod`, `GESSICA.PoidsColis`, `GESSICA.PoidsBrutUV`, `GESSICA.PoidsBrutUL`, `CTBT.StkEnt` FROM basearticles");
}


//
function getBasearticle($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM basearticles LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}

$qlikBa=getBasearticle($pdoQlik);
// $qlikRef=getRef($pdoQlik);
if(isset($qlikBa['DateExecutionScriptQlik'])){
	$dateImport=$qlikBa['DateExecutionScriptQlik'];
	echo $dateImport;
	echo "<br>";

	$today=date('Y-m-d');
	echo $today;

	if($dateImport==$today){
		echo "mise à jour";
		$pdoQlik->query("DELETE FROM `ba`");
		copyBa($pdoQlik);

	}else{
		echo $dateImport;
		echo "pas de mise à jour";
		// echo $today->format('Y-m-d');
	}
}





if (($handle = fopen($file, "r")) !== FALSE) {
		$pdoQlik->query("DELETE FROM `ba_ref`");

	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}else{
	if($data[85]!=3){
			insertRef($pdoQlik,$data[1] ,$data[0], $data[20]);
		}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}


updateBa($pdoQlik);



$pdoQlik->query("OPTIMIZE TABLE ba ");
