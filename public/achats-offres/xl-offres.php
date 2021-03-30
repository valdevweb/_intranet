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
require '../../Class/ProspectusDao.php';
require '../../Class/OffreDao.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$prospDao=new ProspectusDao($pdoDAchat);
$offreDao=new OffreDao($pdoDAchat);



$listFiles=$prospDao->getComingProspectusFiles();
$listLinks=$prospDao->getComingProspectusLinks();
$listProsp=$prospDao->getComingProspectusMag();
$listOffre=$offreDao->getOffreEncoursByProsp();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Code op');
$sheet->setCellValue('b1', 'Nom opération');

$sheet->setCellValue('d1', 'Date de début');
$sheet->setCellValue('e1', 'Date de fin');
$sheet->setCellValue('f1', 'fic');
$sheet->setCellValue('g1', 'produit');
$sheet->setCellValue('h1', 'référence');
$sheet->setCellValue('i1', 'ean');
$sheet->setCellValue('j1', 'gt');
$sheet->setCellValue('k1', 'marque');
$sheet->setCellValue('l1', 'pvc');
$sheet->setCellValue('m1', 'offre');
$sheet->setCellValue('n1', 'montant');
$sheet->setCellValue('o1', 'montant financé');
$sheet->setCellValue('p1', 'commentaire');
$sheet->setCellValue('q1', 'fichiers');
$sheet->setCellValue('r1', 'liens');

$row=2;
foreach ($listProsp as $key => $prosp) {
	if (isset($listOffre[$prosp['id']])){
		foreach ($listOffre[$prosp['id']] as $key => $offre){
			$sheet->setCellValue('A'.$row, $prosp['code_op']);
			$sheet->setCellValue('b'.$row, $prosp['prospectus']);

			$sheet->setCellValue('d'.$row, date('d/m/Y',strtotime($prosp['date_start'])));
			$sheet->setCellValue('e'.$row, date('d/m/Y',strtotime($prosp['date_end'])));
			$sheet->setCellValue('f'.$row, $prosp['fic']);
			$sheet->setCellValue('g'.$row, $offre['produit']);
			$sheet->setCellValue('h'.$row, $offre['reference']);
			$sheet->setCellValue('i'.$row, $offre['ean']);
			$sheet->setCellValue('j'.$row, $offre['gt']);
			$sheet->setCellValue('k'.$row, $offre['marque']);
			$sheet->setCellValue('l'.$row, $offre['pvc']);
			$sheet->setCellValue('m'.$row, ($offre['offre']==1)?"BRII":"TEL");
			$sheet->setCellValue('n'.$row, ($offre['euro']==1)?$offre['montant']:$offre['montant'].'%');
			$sheet->setCellValue('o'.$row, ($offre['euro']==1)?$offre['montant_finance']:$offre['montant_finance'].'%');
			$sheet->setCellValue('p'.$row, $offre['cmt']);

			$strLink="";
			$strFile="";
			if (isset($listLinks[$prosp['id']])) {
				if(count($listLinks[$prosp['id']])==1){
					foreach ($listLinks[$prosp['id']] as $key => $link){
						$strLink.=$link['link']."\n";
						$sheet->setCellValue('r'.$row, $strLink);
						$sheet->getCell('q'.$row)->getHyperlink()->setUrl($strLink);
					}
				}else{
					foreach ($listLinks[$prosp['id']] as $key => $link){
						$strLink.=$link['link']."\n";
					}
					$sheet->setCellValue('r'.$row, $strLink);

				}
			}
			if (isset($listFiles[$prosp['id']])) {
				if(count($listFiles[$prosp['id']])==1){
					foreach ($listFiles[$prosp['id']] as $key => $file){
						$strFile.=URL_UPLOAD."offres/".$file['file']."\n";
						$sheet->setCellValue('q'.$row, $strFile);
						$sheet->getCell('q'.$row)->getHyperlink()->setUrl(URL_UPLOAD."offres/".$file['file']);
					}
				}else{

					foreach ($listFiles[$prosp['id']] as $key => $file){
						$strFile.=URL_UPLOAD."offres/".$file['file']."\n";
					}
					$sheet->setCellValue('q'.$row, $strFile);
				}
			}
			$row++;
		}
	}
}
$highestRow = $sheet->getHighestRow();


$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);


for ($i=0; $i < $highestColumnIndex ; $i++){
	$sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
}
$sheet->getStyle('I2:I'.$highestRow)
->getNumberFormat()
->setFormatCode(
	'0000000000000'
);
$sheet->getStyle('q2:q'.$highestRow)->getAlignment()->setWrapText(true);
$sheet->getStyle('r2:r'.$highestRow)->getAlignment()->setWrapText(true);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="offres-tel-brii.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();





