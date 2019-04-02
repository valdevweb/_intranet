<?php

require_once  '../vendor/autoload.php';

//----------------------------------------------
//  		excel
//----------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ------------------------------------
// JULIE
$spreadsheetJulie = new Spreadsheet();
$sheet = $spreadsheetJulie->getActiveSheet();
$sheet->setCellValue('A1', 'Centrale');
$sheet->setCellValue('B1', 'Magasin');
$sheet->setCellValue('C1', 'Numéro de retour');
$sheet->setCellValue('D1', 'Numéro d\'expédition');
$sheet->setCellValue('E1', 'Nb colis');
$sheet->setCellValue('F1', 'Date d\'expédition');
$sheet->setCellValue('G1', 'Date de réception');
$row=2;
foreach ($details as $key => $detail)
{
	$sheet->setCellValue('A'.$row, $centrale['centrale']);
	$sheet->setCellValue('B'.$row, $deno);
	$sheet->setCellValue('C'.$row, $detail['num_ret']);
	$sheet->setCellValue('D'.$row, $detail['num_exp']);
	$sheet->setCellValue('E'.$row, $detail['nb_colis']);
	$sheet->setCellValue('F'.$row, $detail['d_lim_exp']);
	$sheet->setCellValue('G'.$row, $detail['d_lim_recep']);
	$row++;
}
// dimensionnement des colonnes
$cols=['A','B','C','D','E','F','G'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$sheet->setTitle('Artemis');
$writer = new Xlsx($spreadsheetJulie);
$writer->save('demande-artemis-julie.xlsx');

// pour lancer le téléchargement sur le poste client

$writer = new Xlsx($spreadsheetJulie);
$writer->save('plan navette.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export.xlsx"');
$writer->save("php://output");
exit;



 ?>