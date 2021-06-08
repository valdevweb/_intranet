<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'GT');
$sheet->setCellValue('b1', 'Date cde');
$sheet->setCellValue('c1', 'Fournisseur');
$sheet->setCellValue('d1', 'Marque');
$sheet->setCellValue('e1', 'Article');
$sheet->setCellValue('f1', 'Dossier');
$sheet->setCellValue('g1', 'Réf');
$sheet->setCellValue('h1', 'EAN');
$sheet->setCellValue('i1', 'Désignation');
$sheet->setCellValue('j1', 'Cde');
$sheet->setCellValue('k1', 'Qte init colis');
$sheet->setCellValue('l1', 'Colis à recevoir');
$sheet->setCellValue('m1', 'UV à recevoir');
$sheet->setCellValue('n1', 'PCB');
$sheet->setCellValue('o1', '% reçu');
$sheet->setCellValue('p1', 'livraison initiale');
$sheet->setCellValue('q1', 'livraison');
$sheet->setCellValue('r1', 'date debut op');
$sheet->setCellValue('s1', 'Op');
$sheet->setCellValue('t1', 'Date commentaire');
$sheet->setCellValue('u1', 'Semaine prévi');
$sheet->setCellValue('v1', 'qte prévi');
$sheet->setCellValue('w1', 'Commentaires');


$row=2;
foreach ($listCdes as $key => $cdes) {
	$percentRecu="";

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
	$sheet->setCellValue('A'.$row, $cdes['gt']);
	$sheet->setCellValue('B'.$row, ($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):"");
	$sheet->setCellValue('C'.$row, $cdes['fournisseur']);
	$sheet->setCellValue('D'.$row, $cdes['marque']);
	$sheet->setCellValue('E'.$row, $cdes['article']);
	$sheet->setCellValue('F'.$row, $cdes['dossier']);
	$sheet->setCellValue('G'.$row, $cdes['ref']);
	$sheet->setCellValue('H'.$row, $cdes['ean']);
	$sheet->setCellValue('I'.$row, $cdes['libelle_art']);
	$sheet->setCellValue('j'.$row, $cdes['id_cde']);
	$sheet->setCellValue('k'.$row, $cdes['qte_init']);
	$sheet->setCellValue('l'.$row, $cdes['qte_cde']);
	$sheet->setCellValue('m'.$row, $cdes['qte_uv_cde']);
	$sheet->setCellValue('n'.$row, $cdes['cond_carton']);
	$sheet->setCellValue('o'.$row, $percentRecu);
	$sheet->setCellValue('p'.$row, ($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):"");
	$sheet->setCellValue('q'.$row, ($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):"");
	$sheet->setCellValue('r'.$row, ($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):"");
	$sheet->setCellValue('s'.$row, $cdes['libelle_op']);
	if (isset($listInfos[$cdes['id']])){
		foreach ($listInfos[$cdes['id']] as $key => $value){
			$sheet->setCellValue('t'.$row, date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert'])));
			$sheet->setCellValue('u'.$row, $listInfos[$cdes['id']][$key]['week_previ']);
			$sheet->setCellValue('v'.$row,$listInfos[$cdes['id']][$key]['qte_previ']);
			$sheet->setCellValue('w'.$row,$listInfos[$cdes['id']][$key]['cmt']);
		}
	}
	$row++;

}


$filename="commandes_en_cours.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();