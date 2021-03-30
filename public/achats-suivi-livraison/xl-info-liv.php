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

require '../../Class/CataDao.php';
require '../../Class/InfoLivDao.php';
require '../../Class/ArticleAchatsDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/Helpers.php';



// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');


$inInfoLiv=1;

$cataDao= new CataDao($pdoQlik);
$infoLivDao=new infoLivDao($pdoDAchat);
$articleDao=new ArticleAchatsDao($pdoDAchat);



$listOpAVenir=$infoLivDao->getOpAVenir();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Code op');
$sheet->setCellValue('b1', 'Nom opération');
$sheet->setCellValue('c1', 'Code catalogue');
$sheet->setCellValue('d1', 'Date de début');
$sheet->setCellValue('e1', 'Date de fin');
$sheet->setCellValue('f1', 'article');
$sheet->setCellValue('g1', 'dossier');
$sheet->setCellValue('h1', 'libellé');
$sheet->setCellValue('i1', 'ean');
$sheet->setCellValue('j1', 'gt');
$sheet->setCellValue('k1', 'marque');
$sheet->setCellValue('l1', 'fournisseur');
$sheet->setCellValue('m1', 'ppi');
$sheet->setCellValue('n1', 'deee');
$sheet->setCellValue('o1', 'reçu 2 avant lundi');
$sheet->setCellValue('p1', 'commentaire 2 avant lundi');
$sheet->setCellValue('q1', 'reçu 1 avant lundi');
$sheet->setCellValue('r1', 'commentaire 1 avant lundi');
$sheet->setCellValue('s1', 'article de remplacement');
$sheet->setCellValue('t1', 'ean article de remplacement');
$sheet->setCellValue('u1', 'art remplacement, reçu 2 avant lundi');
$sheet->setCellValue('v1', 'art remplacement, commentaire 2 avant lundi');
$sheet->setCellValue('w1', 'art remplacement, reçu 1 avant lundi');
$sheet->setCellValue('x1', 'art remplacement, commentaire 1 avant lundi');

$row=2;
foreach ($listOpAVenir as $key => $op) {
	$infoLiv=$infoLivDao->getInfoLivByOp($op['code_op']);
	if(!empty($infoLiv)){
		foreach ($infoLiv as $key => $info) {
			$sheet->setCellValue('A'.$row, $op['code_op']);
			$sheet->setCellValue('b'.$row, $op['operation']);
			$sheet->setCellValue('c'.$row, $op['code_cata']);
			$sheet->setCellValue('d'.$row, date('d/m/Y',strtotime($op['date_start'])));
			$sheet->setCellValue('e'.$row, date('d/m/Y',strtotime($op['date_end'])));
			$sheet->setCellValue('f'.$row, $info['article']);
			$sheet->setCellValue('g'.$row, $info['dossier']);
			$sheet->setCellValue('h'.$row, $info['libelle']);
			$sheet->setCellValue('i'.$row, $info['ean']);
			$sheet->setCellValue('j'.$row, $info['gt']);
			$sheet->setCellValue('k'.$row, $info['marque']);
			$sheet->setCellValue('l'.$row, $info['fournisseur']);
			$sheet->setCellValue('m'.$row, $info['ppi']);
			$sheet->setCellValue('n'.$row, $info['deee']);
			$sheet->setCellValue('o'.$row, $info['recu_deux']);
			$sheet->setCellValue('p'.$row, $info['info_livraison_deux']);
			$sheet->setCellValue('q'.$row, $info['recu']);
			$sheet->setCellValue('r'.$row, $info['info_livraison']);
			$sheet->setCellValue('s'.$row, $info['article_remplace']);
			$sheet->setCellValue('t'.$row, $info['ean_remplace']);
			$sheet->setCellValue('u'.$row, $info['recu_deux_remplace']);
			$sheet->setCellValue('v'.$row, $info['info_livraison_deux_remplace']);
			$sheet->setCellValue('w'.$row, $info['recu_remplace']);
			$sheet->setCellValue('x'.$row, $info['info_livraison_remplace']);
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
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="info-livraison.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();





