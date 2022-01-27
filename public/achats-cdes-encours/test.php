<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
    header('Location:' . ROOT_PATH . '/index.php');
    exit();
}

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require '../../Class/achats/CdesDao.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesCmtDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/FormHelpers.php';
require '../../Class/UserDao.php';


$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoQlik = $db->getPdo('qlik');
$pdoFou = $db->getPdo('fournisseurs');
$pdoDAchat = $db->getPdo('doc_achats');

$cdesDao = new CdesDao($pdoQlik);
$userDao = new UserDao($pdoUser);
$cdesAchatDao = new CdesAchatDao($pdoDAchat);
$cdesCmtDao = new  CdesCmtDao($pdoDAchat);


$listCdes = $cdesDao->getCdes();
$listInfos = $cdesAchatDao->getInfos();


foreach ($listCdes as $key => $cdes) {

    if (isset($listInfos[$cdes['id']])) {
        echo 'commande id ' . $cdes['id'] . 'article ' . $cdes['article'];
        echo "<br>";

        $week = $qteReste = "";
        $qteTotalePrevi = 0;
        // compteur de colonnes info : pas plus de 6 colonnes d'info 
        $nbColInfo = 0;
        $nbColInfoAllowed = 6;
        // on crée une date previ initiale pour comparer les date de previ saisies et prendre uniquement la dernière


        foreach ($listInfos[$cdes['id']] as $key => $value) {
            echo 'info ' . $key . ' : week : ' . $listInfos[$cdes['id']][$key]['week_previ'] . ' qte previ :' . $listInfos[$cdes['id']][$key]['qte_previ'] . 'date previ' . $listInfos[$cdes['id']][$key]['date_previ'];
            echo "<br>";
            if ($listInfos[$cdes['id']][$key]['qte_previ'] != null) {
                $qteTotalePrevi = $listInfos[$cdes['id']][$key]['qte_previ'] + $qteTotalePrevi;
            }
            if ($listInfos[$cdes['id']][$key]['date_previ'] != null) {

                if ($nbColInfo < $nbColInfoAllowed) {
                    echo '  qte previ '.$listInfos[$cdes['id']][$key]['qte_previ'] .' date previ ' .$listInfos[$cdes['id']][$key]['date_previ'];
                    echo "<br>";
                    // $sheet->setCellValue(getNameFromNumber(COL_QTE[$nbColInfo]) . $row, $listInfos[$cdes['id']][$key]['qte_previ']);
                    // $sheet->setCellValue(getNameFromNumber(COL_DATE[$nbColInfo]) . $row, $listInfos[$cdes['id']][$key]['date_previ']);
                    // $sheet->setCellValue(getNameFromNumber(COL_ID_INFO[$nbColInfo]) . $row, $listInfos[$cdes['id']][$key]['id']);	
                    // recup la date de prévi la plus éloignée - les dates sont triées
                    $datePreviMax = $listInfos[$cdes['id']][$key]['date_previ'];
                    $week = $listInfos[$cdes['id']][$key]['week_previ'];

                    $nbColInfo++;
                }
            }
        }

        if ($qteTotalePrevi != 0) {
            // $sheet->setCellValue('v' . $row, $qteTotalePrevi);

            $qteReste = $cdes['qte_uv_cde'] - $qteTotalePrevi;
            // $sheet->setCellValue('w' . $row, $qteReste);
            echo " TOTAL PREVI ". $qteTotalePrevi. " RESTE ". $qteReste . ' date max ' .$datePreviMax;
            echo "<br>";
            echo "<br>";
        }
    }
    // $row++;

}
