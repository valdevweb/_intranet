<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



function getNameFromNumber($num) {
	$numeric = $num % 26;
	$letter = chr(65 + $numeric);
	$num2 = intval($num / 26);
	if ($num2 > 0) {
		return getNameFromNumber($num2 - 1) . $letter;
	} else {
		return $letter;
	}
}




$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('b1', 'GT');
$sheet->setCellValue('c1', 'Fournisseur');
$sheet->setCellValue('d1', 'Cde');
$sheet->setCellValue('e1', 'Date cde');
$sheet->setCellValue('f1', 'Article');
$sheet->setCellValue('g1', 'Dossier');
$sheet->setCellValue('h1', 'date debut op');
$sheet->setCellValue('i1', 'Op');
$sheet->setCellValue('j1', 'Réf');
$sheet->setCellValue('k1', 'EAN');
$sheet->setCellValue('l1', 'Désignation');
$sheet->setCellValue('m1', 'Marque');
$sheet->setCellValue('n1', 'PCB');
$sheet->setCellValue('o1', 'Qte init colis');
$sheet->setCellValue('p1', 'Engagement initial');
$sheet->setCellValue('q1', '% reçu');
$sheet->setCellValue('r1', 'UV à recevoir');
$sheet->setCellValue('s1', 'Colis à recevoir');
$sheet->setCellValue('t1', 'Date livraison initiale');
$sheet->setCellValue('u1', 'Semaine prévi');



for ($i=0; $i <6 ; $i++) {
	$colId=21+($i*4)+0;
	$colQte=21+($i*4)+1;
	$colDate=21+($i*4)+2;
	$colCmt=21+($i*4)+3;
	$sheet->setCellValue(getNameFromNumber($colId).'1', 'id_cdes_info'.$colId);
	$sheet->setCellValue(getNameFromNumber($colQte).'1', 'qte prévi '.$i+1);
	$sheet->setCellValue(getNameFromNumber($colDate).'1', 'date previ '.$i+1);
	$sheet->setCellValue(getNameFromNumber($colCmt).'1', 'Commentaires '.$i+1);
}

$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

for ($i=0; $i < 21 ; $i++){
	$sheet->getColumnDimension(getNameFromNumber($i))->setAutoSize(true);

}



$row=2;
foreach ($listCdes as $key => $cdes) {
	$percentRecu="";
	$week="";
	$id=0;
	if($cdes['qte_init']!=0){
		$recu=$cdes['qte_init']-$cdes['qte_cde'];
		if($recu!=0){
			$percentRecu=($recu*100)/$cdes['qte_init'];
			$percentRecu=floor ($percentRecu);
		}else{
			$percentRecu=0 ;
		}
		$percentRecu=$percentRecu."%";
	}

	$sheet->setCellValue('A'.$row, $cdes['id']);
	$sheet->setCellValue('B'.$row, $cdes['gt']);
	$sheet->setCellValue('c'.$row, $cdes['fournisseur']);
	$sheet->setCellValue('d'.$row, $cdes['id_cde']);
	$sheet->setCellValue('e'.$row, ($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):"");
	$sheet->setCellValue('f'.$row, $cdes['article']);
	$sheet->setCellValue('G'.$row, $cdes['dossier']);
	$sheet->setCellValue('h'.$row, ($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):"");
	$sheet->setCellValue('i'.$row, $cdes['libelle_op']);
	$sheet->setCellValue('j'.$row, $cdes['ref']);
	$sheet->setCellValue('k'.$row, $cdes['ean']);
	$sheet->setCellValue('l'.$row, $cdes['libelle_art']);
	$sheet->setCellValue('m'.$row, $cdes['marque']);
	$sheet->setCellValue('n'.$row, $cdes['cond_carton']);
	$sheet->setCellValue('o'.$row, $cdes['qte_init']);
	$sheet->setCellValue('p'.$row, 'Engagement initial');
	$sheet->setCellValue('q'.$row, $percentRecu);
	$sheet->setCellValue('r'.$row, $cdes['qte_uv_cde']);
	$sheet->setCellValue('s'.$row, $cdes['qte_cde']);
	$sheet->setCellValue('t'.$row, ($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):"");

// info saisies par achats
	// col 1 =v cad 22  // ordre : id qte date cmt
	if (isset($listInfos[$cdes['id']])){

		foreach ($listInfos[$cdes['id']] as $key => $value){
			if($listInfos[$cdes['id']][$key]['week_previ']!="" && $listInfos[$cdes['id']][$key]['week_previ']!=" "){
				// on écrase pour avoir la dernière semaine saisie
				$week=$listInfos[$cdes['id']][$key]['week_previ'];
			}

			if($key<6) {
				$colId=21+($key*4)+0; //v
				$colQte=21+($key*4)+1; //w
				$colDate=21+($key*4)+2; //x
				$colCmt=21+($key*4)+3;//y
				$sheet->setCellValue(getNameFromNumber($colId).$row,$listInfos[$cdes['id']][$key]['id']);
				$sheet->setCellValue(getNameFromNumber($colQte).$row,$listInfos[$cdes['id']][$key]['qte_previ']);
				$sheet->setCellValue(getNameFromNumber($colDate).$row,$listInfos[$cdes['id']][$key]['date_previ']);

				$sheet->setCellValue(getNameFromNumber($colCmt).$row,$listInfos[$cdes['id']][$key]['cmt']);
				$spreadsheet->getActiveSheet()->getStyle(getNameFromNumber($colCmt).$row)->getAlignment()->setWrapText(true);
				$spreadsheet->getActiveSheet()->getColumnDimension(getNameFromNumber($colDate))->setWidth(100, 'pt');
				$spreadsheet->getActiveSheet()->getColumnDimension(getNameFromNumber($colCmt))->setWidth(100, 'pt');
				$spreadsheet->getActiveSheet()->getColumnDimension(getNameFromNumber($colQte))->setWidth(100, 'pt');

			}

			// $qte=$listInfos[$cdes['id']][$key]['qte_previ']+$qte;
			// $datePrevi=$listInfos[$cdes['id']][$key]['date_previ'];
		}
	}
	$spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(15, 'pt');
	$row++;
}

$colors=["93b3d9","dc9497", "c3d69e","b29ecf", "92cce0","fcbf89"];
$colorsLight=["dce6f0","efdddb", "ecf0df","e3dfee", "dbeef4","fbe9db"];

for ($i=0; $i <6 ; $i++) {
	$colCmt=24+($i*4);
	$firstCol=21+($i*4);
	$secondeCol=22+($i*4);
	$lastCol=21+($i*4)+3;
	$firstColLetter=getNameFromNumber($firstCol);
	$lastColLetter=getNameFromNumber($lastCol);
	$secondeColLetter=getNameFromNumber($secondeCol);


	$spreadsheet->getActiveSheet()->getStyle($firstColLetter.'1:'.$lastColLetter.$row)->getFill()
	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()->setRGB($colors[$i]);
	for ($r = 2; $r < $row; $r++) {
		if ($r % 2 == 0) {
			$spreadsheet->getActiveSheet()->getStyle($firstColLetter. $r . ':'.$lastColLetter . $r)->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setRGB($colorsLight[$i]);
		}
	}

	$spreadsheet->getActiveSheet()->getColumnDimension($firstColLetter)->setVisible(false);

}

// $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
// $spreadsheet->getDefaultStyle()->getProtection()->setLocked(false);
// // $spreadsheet->getActiveSheet()->getColumnDimension('v')->setVisible(false);
// $sheet->getStyle('a:u')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);


$filename="commandes_en_cours.xlsx";


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();