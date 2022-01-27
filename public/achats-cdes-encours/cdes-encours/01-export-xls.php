<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//  page qui permet de générer un fichier excel commandes en cours
// les données exportées sont celles affiches dans la page cdes-encours
//  on récupère donc les var $listeCdes et $listInfo de la page commande an cours


// utilisation d'un template protégé pour protéger les colonnes ids
$templateTrp = 'xl-file\export-encours.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templateTrp);
$sheet = $spreadsheet->getActiveSheet();


$nbColInfoAllowed = 6;
$row = 2;


//  insert données commandes en cours
foreach ($listCdes as $key => $cdes) {
	$percentRecu = "";
	$week = "";
	$id = 0;
	if ($cdes['qte_init'] != 0) {
		$recu = $cdes['qte_init'] - $cdes['qte_cde'];
		if ($recu != 0) {
			$percentRecu = ($recu * 100) / $cdes['qte_init'];
			$percentRecu = floor($percentRecu);
		} else {
			$percentRecu = 0;
		}
		$percentRecu = $percentRecu . "%";
	}

	$sheet->setCellValue('A' . $row, $cdes['id']);
	$sheet->setCellValue('B' . $row, $cdes['gt']);
	$sheet->setCellValue('c' . $row, $cdes['fournisseur']);
	$sheet->setCellValue('d' . $row, $cdes['id_cde']);
	$sheet->setCellValue('e' . $row, ($cdes['date_cde'] != null) ? date('d/m/y', strtotime($cdes['date_cde'])) : "");
	$sheet->setCellValue('f' . $row, $cdes['article']);
	$sheet->setCellValue('G' . $row, $cdes['dossier']);
	$sheet->setCellValue('h' . $row, ($cdes['date_start'] != null) ? date('d/m/y', strtotime($cdes['date_start'])) : "");
	$sheet->setCellValue('i' . $row, $cdes['libelle_op']);
	$sheet->setCellValue('j' . $row, $cdes['ref']);
	$sheet->setCellValue('k' . $row, $cdes['ean']);
	$sheet->setCellValue('l' . $row, $cdes['libelle_art']);
	$sheet->setCellValue('m' . $row, $cdes['marque']);
	$sheet->setCellValue('n' . $row, $cdes['cond_carton']);
	$sheet->setCellValue('o' . $row, $cdes['qte_init']);
	$sheet->setCellValue('p' . $row, 'Engagement initial');
	$sheet->setCellValue('q' . $row, $percentRecu);
	$sheet->setCellValue('r' . $row, $cdes['qte_uv_cde']);
	$sheet->setCellValue('s' . $row, $cdes['qte_cde']);
	$sheet->setCellValue('t' . $row, ($cdes['date_liv_init'] != null) ? date('d/m/y', strtotime($cdes['date_liv_init'])) : "");
	$sheet->setCellValue('x' . $row, ' ' . $cdes['cmt_btlec']);
	$sheet->setCellValue('y' . $row, ' ' . $cdes['cmt_galec']);

	// si des infos ont été saisie, on les insert en parcourant le tableau d'info cde (listInfo)
	// on calcule la date max prévi, la semaine max prévi, la qte totale prévi, le restant prévi
	//  on limite le nb de col info à 6
	if (isset($listInfos[$cdes['id']])) {
		$week = $qteReste = "";
		$qteTotalePrevi = 0;

		// compteur de colonnes info pour limiter le nb de col
		$nbColInfo = 0;

		//  récup date max et somme qte prévi + insert donnée info cdes
		foreach ($listInfos[$cdes['id']] as $key => $value) {
			if ($listInfos[$cdes['id']][$key]['qte_previ'] != null) {
				$qteTotalePrevi = $listInfos[$cdes['id']][$key]['qte_previ'] + $qteTotalePrevi;
			}
			if ($listInfos[$cdes['id']][$key]['date_previ'] != null) {

				if ($nbColInfo < $nbColInfoAllowed) {
					$sheet->setCellValue(getNameFromNumber(COL_QTE[$nbColInfo]) . $row, $listInfos[$cdes['id']][$key]['qte_previ']);
					if($listInfos[$cdes['id']][$key]['date_previ']!=null){
						$sheet->setCellValue(getNameFromNumber(COL_DATE[$nbColInfo]) . $row, date('d-m-Y', strtotime($listInfos[$cdes['id']][$key]['date_previ'])));

					}
					$sheet->setCellValue(getNameFromNumber(COL_ID_INFO[$nbColInfo]) . $row, $listInfos[$cdes['id']][$key]['id']);
					// recup la date de prévi la plus éloignée (les dates sont triées)
					$datePreviMax = $listInfos[$cdes['id']][$key]['date_previ'];
					$week = $listInfos[$cdes['id']][$key]['week_previ'];
					$nbColInfo++;
				}
			}
		}
		// insert des données calculées via les infos saisies
		$sheet->setCellValue('u' . $row, $week);
		if ($qteTotalePrevi != 0) {
			$sheet->setCellValue('v' . $row, $qteTotalePrevi);
			$qteReste = $cdes['qte_uv_cde'] - $qteTotalePrevi;
			$sheet->setCellValue('w' . $row, $qteReste);
		}
	}
	$row++;
}


$filename = "commandes_en_cours.xlsx";


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();
