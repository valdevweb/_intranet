<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';

require '../../Class/Db.php';
require '../../Class/GesapDao.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gesapDao=new GesapDao($pdoDAchat);

$listGesap=$gesapDao->getListGesap();
$listFiles=$gesapDao->getListFiles();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('a1', 'Nom opération');
$sheet->setCellValue('b1', 'Salon');
$sheet->setCellValue('c1', 'Catalogue');
$sheet->setCellValue('d1', 'code op');
$sheet->setCellValue('e1', 'date de remontée');
$sheet->setCellValue('f1', 'Guide d\'achat');
$sheet->setCellValue('g1', 'Commentaire');
$sheet->setCellValue('h1', 'fichiers');


$row=2;
foreach ($listGesap as $key => $gesap) {

	$sheet->setCellValue('A'.$row, $gesap['op']);
	$sheet->setCellValue('b'.$row, $gesap['salon']);
	$sheet->setCellValue('c'.$row, $gesap['cata']);
	$sheet->setCellValue('d'.$row, $gesap['code_op']);
	$sheet->setCellValue('e'.$row, date('d/m/Y',strtotime($gesap['date_remonte'])));
	$sheet->setCellValue('f'.$row, $gesap['ga_num']);
	$sheet->getCell('f'.$row)->getHyperlink()->setUrl(URL_UPLOAD."gesap/".$gesap['ga_file']);

	$sheet->setCellValue('g'.$row, $gesap['cmt']);
	$strFile="";
	if(!empty($listFiles)){
		if (isset($listFiles[$gesap['id']])) {
			if(count($listFiles[$gesap['id']])==1){
				foreach ($listFiles[$gesap['id']] as $key => $file){
					$strFile.=URL_UPLOAD."gesap/".$file['file']."\n";
					$sheet->setCellValue('h'.$row, $strFile);
					$sheet->getCell('h'.$row)->getHyperlink()->setUrl(URL_UPLOAD."gesap/".$file['file']);
				}
			}else{

				foreach ($listFiles[$gesap['id']] as $key => $file){
					$strFile.=URL_UPLOAD."gesap/".$file['file']."\n";
				}
				$sheet->setCellValue('h'.$row, $strFile);
			}
		}
		$row++;

	}


}
$highestRow = $sheet->getHighestRow();


$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);


for ($i=0; $i < $highestColumnIndex ; $i++){
	$sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
}

$sheet->getStyle('h2:h'.$highestRow)->getAlignment()->setWrapText(true);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="offres-gesap.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();





