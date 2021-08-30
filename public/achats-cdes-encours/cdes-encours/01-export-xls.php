<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('b1', 'GT');
$sheet->setCellValue('c1', 'Date cde');
$sheet->setCellValue('d1', 'Fournisseur');
$sheet->setCellValue('e1', 'Marque');
$sheet->setCellValue('f1', 'Article');
$sheet->setCellValue('g1', 'Dossier');
$sheet->setCellValue('h1', 'Réf');
$sheet->setCellValue('i1', 'EAN');
$sheet->setCellValue('j1', 'Désignation');
$sheet->setCellValue('k1', 'Cde');
$sheet->setCellValue('l1', 'Qte init colis');
$sheet->setCellValue('m1', 'Colis à recevoir');
$sheet->setCellValue('n1', 'UV à recevoir');
$sheet->setCellValue('o1', 'PCB');
$sheet->setCellValue('p1', '% reçu');
$sheet->setCellValue('q1', 'livraison initiale');
$sheet->setCellValue('r1', 'livraison');
$sheet->setCellValue('s1', 'date debut op');
$sheet->setCellValue('t1', 'Op');
$sheet->setCellValue('u1', 'Semaine prévi');
$sheet->setCellValue('v1', 'qte prévi');
$sheet->setCellValue('w1', 'Commentaires');
$sheet->setCellValue('x1', 'Saisie - date previ');
$sheet->setCellValue('y1', 'Saisie - qte prévi');
$sheet->setCellValue('z1', 'Saisie - commentaire');


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
	$sheet->setCellValue('A'.$row, $cdes['id']);
	$sheet->setCellValue('B'.$row, $cdes['gt']);
	$sheet->setCellValue('C'.$row, ($cdes['date_cde']!=null)?date('d/m/y', strtotime($cdes['date_cde'])):"");
	$sheet->setCellValue('D'.$row, $cdes['fournisseur']);
	$sheet->setCellValue('E'.$row, $cdes['marque']);
	$sheet->setCellValue('F'.$row, $cdes['article']);
	$sheet->setCellValue('G'.$row, $cdes['dossier']);
	$sheet->setCellValue('H'.$row, $cdes['ref']);
	$sheet->setCellValue('I'.$row, $cdes['ean']);
	$sheet->setCellValue('j'.$row, $cdes['libelle_art']);
	$sheet->setCellValue('k'.$row, $cdes['id_cde']);
	$sheet->setCellValue('l'.$row, $cdes['qte_init']);
	$sheet->setCellValue('m'.$row, $cdes['qte_cde']);
	$sheet->setCellValue('n'.$row, $cdes['qte_uv_cde']);
	$sheet->setCellValue('o'.$row, $cdes['cond_carton']);
	$sheet->setCellValue('p'.$row, $percentRecu);
	$sheet->setCellValue('q'.$row, ($cdes['date_liv_init']!=null)?date('d/m/y', strtotime($cdes['date_liv_init'])):"");
	$sheet->setCellValue('r'.$row, ($cdes['date_liv']!=null)?date('d/m/y', strtotime($cdes['date_liv'])):"");
	$sheet->setCellValue('s'.$row, ($cdes['date_start']!=null)?date('d/m/y', strtotime($cdes['date_start'])):"");
	$sheet->setCellValue('T'.$row, $cdes['libelle_op']);
	if (isset($listInfos[$cdes['id']])){
		$week="";
		$arrayWeek=[];
		$qte=0;
		$arrayCmt=[];
		$cmt="";
		$infoSize=count($listInfos[$cdes['id']]);
		foreach ($listInfos[$cdes['id']] as $key => $value){

			if($listInfos[$cdes['id']][$key]['week_previ']!="" && $listInfos[$cdes['id']][$key]['week_previ']!=" "){
				// on écrase pour avoir la dernière semaine saisie
				$week=$listInfos[$cdes['id']][$key]['week_previ'];
			}
			$qte=$listInfos[$cdes['id']][$key]['qte_previ']+$qte;

			if($listInfos[$cdes['id']][$key]['cmt']!="" && $listInfos[$cdes['id']][$key]['cmt']!=" "){
				$arrayCmt[]=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert'])). " : ". preg_replace("/\r|\n/", "",$listInfos[$cdes['id']][$key]['cmt']);
			}

		}

		$sheet->setCellValue('u'.$row, $week);
		$spreadsheet->getActiveSheet()->getStyle('u'.$row,)->getAlignment()->setWrapText(true);
		if(	$qte!=0){
			$sheet->setCellValue('v'.$row,$qte);
		}
		if(!empty($arrayCmt)){
			$cmt=implode(PHP_EOL, $arrayCmt);
		}
		$sheet->setCellValue('w'.$row,$cmt);
		$spreadsheet->getActiveSheet()->getStyle('w'.$row)->getAlignment()->setWrapText(true);

	}
	$spreadsheet->getActiveSheet()->getStyle('x'.$row)
	->getNumberFormat()
	->setFormatCode('DD/MM/YYYY');
	$spreadsheet->getActiveSheet()->getStyle('y'.$row)
	->getNumberFormat()
	->setFormatCode('0');
	$row++;

}
// $highestColumn = $sheet->getHighestColumn();
// $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);


// for ($i=0; $i < $highestColumnIndex ; $i++){
//     $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
// }

$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$spreadsheet->getActiveSheet()->getStyle('x1:z1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('dc3545');

$spreadsheet->getActiveSheet()->getStyle('x2:z'.$row)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('f5e3e4');
$filename="commandes_en_cours.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();