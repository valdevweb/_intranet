<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
    header('Location:' . ROOT_PATH . '/index.php');
    exit();
}

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
}

set_error_handler('exceptions_error_handler');

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesCmtDao.php';
require '../../Class/UserHelpers.php';
require_once '../../vendor/autoload.php';

include 'xl-cols/xl-cols.php';

use Mpdf\Tag\Tr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;






$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoDAchat = $db->getPdo('doc_achats');

$cdesAchatDao = new CdesAchatDao($pdoDAchat);
$cdesCmtDao = new CdesCmtDao($pdoDAchat);


if (isset($_GET['id'])) {
    $idImport = $_GET['id'];
    $fileInfo=$cdesAchatDao->getImport($idImport);
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(TRUE);

    $path = DIR_UPLOAD . 'cdes-encours\\';
    $fxls = $path . $fileInfo['file'];
    $spreadsheet = $reader->load($fxls);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();


    for ($row = 2; $row <= $highestRow; ++$row) {
        $idCdesEncours = $worksheet->getCell('a' . $row)->getValue();
        $cmtBtlec = $worksheet->getCell('x' . $row)->getValue();
        $cmtGalec = $worksheet->getCell('y' . $row)->getValue();
        // on verifie si on a des commentaires galec ou bt
        // si oui, on vérifie si on a une ligne en db (cdes_cmt) pour savoir si update ou insert
        if (trim($cmtBtlec) != "" || trim($cmtGalec)) {
            $cmts = $cdesCmtDao->getCmt($idCdesEncours);
            if (empty($cmts)) {
                $cdesCmtDao->insertCmt($idCdesEncours, $idImport, trim($cmtBtlec), trim($cmtGalec));
            } else {
                $cdesCmtDao->updateCmt($idCdesEncours, $idImport, trim($cmtBtlec), trim($cmtGalec));
            }
        }
        // on parcourt les colonnes qte_previ, date_previ et id cdes infos
        // si id cdes info non vide, update  (soit maj date et previ soit, si champs date et previ vide, mask info)
        // si id cdes info vide et date ou qte non vide, insert

        for ($i = 0; $i < count(COL_QTE); $i++) {
            $qtePrevi = trim($worksheet->getCell(COL_QTE_STR[$i] . $row)->getValue());
            $datePrevi = trim($worksheet->getCell(COL_DATE_STR[$i] . $row)->getValue());
            $idInfo = $worksheet->getCell(COL_ID_INFO_STR[$i] . $row)->getValue();

            if (!empty($qtePrevi) && !is_numeric($qtePrevi)) {
                echo "la quantité, " . $qtePrevi . ", à la ligne " . $row . " n'est pas dans un format correct. <br>";
                exit;
            }
            if ($datePrevi != "") {
                // si on a que des chiffres sans séparateur, on a une date format excel
                if (preg_match("/^[0-9]+$/", $datePrevi)) {
                    try {
                        $datePrevi = $excelStart->modify('+ ' . $datePrevi . ' day ');
                        $datePrevi = $datePrevi->format("Y-m-d");
                    } catch (Exception $e) {
                        echo "ligne " . $row . " la date " . $datePrevi . " n'est pas dans un format reconnu";
                        exit;
                    }
                } else {
                    try {
                        $datePrevi = dateFormat($datePrevi);
                    } catch (Error $e) {
                        echo "ligne " . $row . " la date " . $datePrevi . " n'est pas dans un format reconnu";
                        exit;
                    }
                }
            }
            if (!empty($qtePrevi) || !empty($datePrevi)) {
                // echo "ligne  $row la qte est $qtePrevi et la date est $datePrevi";
                $qtePrevi = (empty($qtePrevi)) ? null : $qtePrevi;
                $datePrevi = (empty($datePrevi)) ? null : $datePrevi;
                if (empty($idInfo)) {
                    $ligne = $cdesAchatDao->insertInfos($idImport, $idCdesEncours, $datePrevi, $qtePrevi);
                    // echo "INSERT $ligne<br>";
                } else {
                    $ligne = $cdesAchatDao->updateInfo($idInfo, $datePrevi, $qtePrevi);
                    // echo "IDINFO $idInfo UPDATE $ligne<br>";
                }
            } elseif ((empty($qtePrevi) || empty($datePrevi)) && !empty($idInfo)) {
                $cdesAchatDao->maskInfo($idInfo);
            }
        }
    }

    $successQ = '?id_import=' . $idImport;
    unset($_POST);
    header("Location: " . $_SERVER['PHP_SELF'] . $successQ, true, 303);
}



if (isset($_GET['id_import'])) {
    $listInfo = $cdesAchatDao->getInfoByImport($_GET['id_import']);
}


if (!empty($errors)) {
    $errors[] = "<br>Veuillez corriger le ficher et le réimporter";
}




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
    <div class="row py-5">
        <div class="col">
            <h1 class="text-main-blue">Import en cours</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col">
            <?php
            include('../view/_errors.php');
            ?>
        </div>
        <div class="col-lg-1"></div>
    </div>
    <div class="row">
        <div class="col">
            <div class="alert alter-danger">Merci de patienter pendant l'import des données. Vous pourrez quitter cette page sans arrêter le traitement, une fois le récapitulatif affiché</div>
        </div>
    </div>

    <div class="row pb-5">
        <div class="col">
            <?php if (isset($listInfo)) : ?>
                <h5 class="text-main-blue mb-5">Récapitualtif des données importées :</h5>
                <?php if (!empty($listInfo)) : ?>

                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Fournisseur</th>
                                <th>Commande</th>
                                <th>Article</th>
                                <th>Dossier</th>
                                <th>Réf</th>
                                <th class="text-right">Qte prévi</th>
                                <th class="text-right">Date prévi</th>
                                <th class="text-right">S. prévie</th>
                                <th>Commentaire</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listInfo as $key => $info) : ?>
                                <tr>
                                    <td><?= $info['fournisseur'] ?></td>
                                    <td><?= $info['id_cde'] ?></td>
                                    <td><?= $info['article'] ?></td>
                                    <td><?= $info['dossier'] ?></td>
                                    <td><?= $info['ref'] ?></td>
                                    <td class="text-right text-main-blue font-weight-bold"><?= $info['qte_previ'] ?></td>
                                    <td class="text-right text-main-blue font-weight-bold"><?= !empty($info['date_previ']) ? date('d/m/Y', strtotime($info['date_previ'])) : "" ?></td>
                                    <td class="text-right text-main-blue font-weight-bold"><?= $info['week_previ'] ?></td>
                                    <td class="text-main-blue font-weight-bold"><?= nl2br($info['cmt']) ?></td>

                                </tr>

                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-primary">Aucune information n'a été importée</div>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col text-center">
            <a href="cdes-encours.php" class="text-main-blue">Retour vers les commandes en cours</a>
        </div>
    </div>
</div>
<script src="../js/upload-helpers.js"></script>


<?php
require '../view/_footer-bt.php';
?>