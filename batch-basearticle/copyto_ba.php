<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
	set_include_path("D:\www\_intranet\_btlecest\\");
} else {
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config/config.inc.php';
require 'Class/Db.php';
require 'Class/CrudDao.php';
require 'functions.php';


$db = new Db();
$pdoQlik = $db->getPdo('qlik');

$file = DIR_IMPORT_GESSICA . "SCEBFART.csv";
$fileArtGessica = DIR_IMPORT_GESSICA . "SCEBFCAB.csv";

$qlikCrud=new CrudDao($pdoQlik);


$row = 0;




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

// récupération de champs issus du fichier SCEBFART.csv  => vers ba_newfields
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

$row = 0;


// récupération des correspondances code article/code article gessica issues de SCEBFCAB => vers ba_newfields
if (($handle = fopen($fileArtGessica, "r")) !== FALSE) {

	$errArr = [];
	$i = 0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if ($row == 0) {
			$entete = $data;
		} else {
            $artGessica=$data[8];
            $art=$data[1];
            $dossier=$data[0];
            $id=$art.$dossier;
            echo 'id recherché '.$id;
            echo "<br>";
			if ($artGessica != "" && $art!="" && $dossier!="") {
                $idExist=$qlikCrud->getOneById('ba_newfields', $id);
  
                if(!empty($idExist)){
                    echo $idExist['id'];
                    echo "<br>";
                    echo "<br>";

                    $qlikCrud->updateOneField('ba_newfields', 'article_gessica',$artGessica, $id);
                }else{
                    echo 'non trouvé';
                    echo "<br>";
                    echo "<br>";

                }
			}
		}
		$row++;
	}
	$row = 0;
	fclose($handle);
}
// update de ba avec les champs de ba_newfields
updateBa($pdoQlik);



$pdoQlik->query("OPTIMIZE TABLE ba ");
