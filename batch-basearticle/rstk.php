<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
    set_include_path("D:\www\_intranet\_btlecest\\");
} else {
    set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/CrudDao.php';



$db = new Db();

$pdoQlik = $db->getPdo('qlik');
$dirRstk = DIR_IMPORT_DUMP . "RSTK\\";
$fileRstk = $dirRstk . "RSTK-Produits.csv";

$crudDao = new CrudDao($pdoQlik);


//1. fichier non présent
if (!file_exists($fileRstk)) {
    exit;
}

// 2. intégration déjà faite
$dateInsert = $crudDao->getOneByField("rstk", "date_format(date_insert, '%Y-%m-%d')", date("Y-m-d"));
if (!empty($dateInsert)) {
    exit;
}

// 3. fichier du jour non présents
if (date('Y-m-d', filemtime($fileRstk)) != date('Y-m-d')) {
    exit;
}
// fichier txt (flag pour transfert en cours)
if (!empty(glob($dirRstk . "*.txt"))) {
    exit;
}

if (($handle = fopen($fileRstk, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

        $qte = $data[6];
        $galec = $data[1];
        $ean = $data[2];
        if (is_numeric($qte) && $qte != "" && $galec != "" && $ean != "") {
            $crudDao->insert("rstk", ['galec' => $galec, 'ean' => $ean, 'qte' => $qte]);
        }
    }
}
