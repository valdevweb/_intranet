<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$templateTrp = 'xl-file\export-encours.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templateTrp);

$sheet = $spreadsheet->getActiveSheet();
//'id', 'GT',fournisseur, Cde,Date cde, 'Article', 'Dossier', 'date debut op', 'Op', 'Réf', 'EAN', 'Désignation', 'Marque','PCB', 'Qte init colis','Engagement initial'
//'% reçu', 'UV à recevoir', 'Colis à recevoir', 'Date livraison initiale', 'Semaine prévi', 'Qte en rendez-vous', 'Qte reste à recevoir'

$row = 2;

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
	$sheet->setCellValue('x' . $row, ' '.$cdes['cmt_btlec']);
	$sheet->setCellValue('y' . $row, ' '.$cdes['cmt_galec']);


	if (isset($listInfos[$cdes['id']])) {
		$week = $qteReste ="";
		$qte = 0;
		foreach ($listInfos[$cdes['id']] as $key => $value) {
			if ($listInfos[$cdes['id']][$key]['week_previ'] != "" && $listInfos[$cdes['id']][$key]['week_previ'] != " ") {
				// on écrase pour avoir la dernière semaine saisie
				$week = $listInfos[$cdes['id']][$key]['week_previ'];
				$qte = $listInfos[$cdes['id']][$key]['qte_previ'] + $qte;
			}
			if ($qte != 0) {
				$qteReste = $cdes['qte_uv_cde'] - $qte;
			}
		
			$sheet->setCellValue(getNameFromNumber(COL_QTE[$key]) . $row, $listInfos[$cdes['id']][$key]['qte_previ']);

			$datePrevi=($listInfos[$cdes['id']][$key]['date_previ']!=null && $listInfos[$cdes['id']][$key]['date_previ']!="")?date('d/m/y', strtotime($listInfos[$cdes['id']][$key]['date_previ'])):"";
			$sheet->setCellValue(getNameFromNumber(COL_DATE[$key]) . $row, $datePrevi);

			$sheet->setCellValue(getNameFromNumber(COL_ID_INFO[$key]) . $row, $listInfos[$cdes['id']][$key]['id']);		
		}
		$sheet->setCellValue('u' . $row, $week);
		if ($qte != 0) {
			$sheet->setCellValue('v' . $row, $qte);
		}
		if ($qteReste != "") {
			$sheet->setCellValue('w' . $row, $qteReste);
		}
		$spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(15, 'pt');
	}
	$row++;

}

// exit;
$filename = "commandes_en_cours.xlsx";


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();
