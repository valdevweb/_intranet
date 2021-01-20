<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

require_once '../../Class/FormationDAO.php';

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;






$formationDOA=new FormationDAO($pdoBt);
$listCreneau=$formationDOA->getCreneaux($pdoBt);
$listInscrit=$formationDOA->getIncriptionByFormation($pdoBt, 1);



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('B1', 'Choix 1');
$sheet->setCellValue('J1', 'Choix 2');

$sheet->setCellValue('A2','Magasin');


$sheet->setCellValue('B2',"mardi\n10h30-11h00");
$sheet->setCellValue('C2',"mardi\n13h30-14h00");
$sheet->setCellValue('D2',"mardi\n15h00-15h30");
$sheet->setCellValue('E2',"mardi\n16h30-17h00");


$sheet->setCellValue('F2',"mercredi\n10h30-11h00");
$sheet->setCellValue('G2',"mercredi\n13h30-14h00");
$sheet->setCellValue('H2',"mercredi\n15h00-15h30");
$sheet->setCellValue('I2',"mercredi\n16h30-17h00");


$sheet->setCellValue('J2',"mardi\n10h30-11h00");
$sheet->setCellValue('K2',"mardi\n13h30-14h00");
$sheet->setCellValue('L2',"mardi\n15h00-15h30");
$sheet->setCellValue('M2',"mardi\n16h30-17h00");


$sheet->setCellValue('N2',"mercredi\n10h30-11h00");
$sheet->setCellValue('O2',"mercredi\n13h30-14h00");
$sheet->setCellValue('P2',"mercredi\n15h00-15h30");
$sheet->setCellValue('Q2',"mercredi\n16h30-17h00");

$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('D2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('E2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('F2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('H2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('I2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('K2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('L2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('M2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('N2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('O2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('P2')->getAlignment()->setWrapText(true);
$spreadsheet->getActiveSheet()->getStyle('Q2')->getAlignment()->setWrapText(true);


$row=3;
foreach ($listInscrit as $key => $inscr){
	if($inscr['mardi']==1){
		switch ($inscr['id_creneau_1']) {
			case 1:
			$colChoix1='B';
			break;
			case 2:
			$colChoix1='C';
			break;
			case 3:
			$colChoix1='B';
			break;
			case 4:
			$colChoix1='D';
			break;
		}
		switch ($inscr['id_creneau_2']) {
			case 1:
			$colChoix2='J';
			break;
			case 2:
			$colChoix2='K';
			break;
			case 3:
			$colChoix2='L';
			break;
			case 4:
			$colChoix2='M';
			break;
		}
	}
	if($inscr['mercredi']==1){
		switch ($inscr['id_creneau_1']) {
			case 1:
			$colChoix1='F';
			break;
			case 2:
			$colChoix1='F';
			break;
			case 3:
			$colChoix1='H';
			break;
			case 4:
			$colChoix1='I';
			break;
		}
		switch ($inscr['id_creneau_2']) {
			case 1:
			$colChoix2='N';
			break;
			case 2:
			$colChoix2='O';
			break;
			case 3:
			$colChoix2='P';
			break;
			case 4:
			$colChoix2='Q';
			break;
		}
	}
	$sheet->setCellValue('A'.$row,$inscr['deno']);

	$sheet->setCellValue($colChoix1.$row,$inscr['nb']);
	$sheet->setCellValue($colChoix2.$row,$inscr['nb']);

	$row++;
}


$spreadsheet->getActiveSheet()->getStyle('B1:i1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('3eb8f1');
$spreadsheet->getActiveSheet()->getStyle('j1:q1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setRGB('f5e672');
$spreadsheet->getActiveSheet()->getStyle('B2:e'.$row)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setRGB('b4e3f9');

$spreadsheet->getActiveSheet()->getStyle('f2:i'.$row)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setRGB('d2eefb');
$spreadsheet->getActiveSheet()->getStyle('j2:m'.$row)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setRGB('f9f3c4');
$spreadsheet->getActiveSheet()->getStyle('n2:q'.$row)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setRGB('fffbdb');



$writer = new Xlsx($spreadsheet);
$writer->save('formation.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export.xlsx"');
$writer->save("php://output");
?>